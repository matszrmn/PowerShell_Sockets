function verify_send_file_params($split_message) {
    $send_file_ok = $false
    
    if(!($split_message.Length -eq 3))       { Write-Host "Parametros faltando na chamada de 'send'" }
    elseif(!($split_message[1] -like "*\*")) { Write-Host "Digite o diretorio completo do arquivo local" }
    elseif(!(Test-Path $split_message[1]))   { Write-Host "Arquivo local inexistente" }
    else { $send_file_ok = $true }
    
    return $send_file_ok
}
function send_file($IP, $port_file, $split_message) {
    $content = [System.Convert]::ToBase64String([io.file]::ReadAllBytes($split_message[1]));
    $tcp_socket = New-Object System.Net.Sockets.TcpClient($IP, $port_file)
    $stream_send = $tcp_socket.GetStream();
    $writer_send = New-Object System.IO.StreamWriter $stream_send
    $writer_send.AutoFlush = $true #Talvez tirar esta linha
    $writer_send.WriteLine($content);
    
    $writer_send.Close()
    $stream_send.dispose()
    $tcp_socket.Close()
}
function new_socket($IP, $port) {
    $connection = New-Object System.Net.Sockets.TcpClient($IP, $port)
    $stream = $connection.GetStream()

    $reader = New-Object System.IO.StreamReader $stream
    $writer = New-Object System.IO.StreamWriter $stream
    $writer.AutoFlush = $true
    
    Return $connection, $reader, $writer
}
function main() {
    $IP = Read-Host -Prompt "Digite o IP"
    $port = 6666
    $port_file = 6667
    $current_dir = "Envie algum comando"
    $separator = "sepsep"

    $new_socket_params = new_socket $IP $port
    $connection = $new_socket_params[0]
    $reader =     $new_socket_params[1]
    $writer =     $new_socket_params[2]

    while ($true) {
        $command = Read-Host -Prompt $current_dir
        $split_message = $command -split " "
	    
        if($split_message[0] -eq "dd") { continue }
        if($split_message[0] -eq "end") { break }
        if($split_message[0] -eq "send" -And !(verify_send_file_params $split_message)) { continue }

        $writer.WriteLine($command)
        
        if($split_message[0] -eq "exit") { break }
        if($split_message[0] -eq "send") { 
            Start-Sleep -s 1
            send_file $IP $port_file $split_message
        }
        $command = $reader.ReadLine()
        $command = $command -split $separator
        Write-Host $command[0]
        $current_dir = $command[1]
    }
    $reader.Close()
    $writer.Close()
    $connection.Close()
}
main