<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());
    
    $titulo = $_REQUEST['titulo'];
    $autor = $_REQUEST['autor'];
    $editora = $_REQUEST['editora'];
    $edicao = $_REQUEST['edicao'];
    $genero = $_REQUEST['genero'];
    
    $usuario = $_REQUEST['usuario'];
    
    $mensagemErro = "";
    $mensagemSucesso = "";
    
    if ($titulo==null||$autor==null||$editora==null||$edicao==null) {
        $connection -> close();
        $mensagemErro = "Preencha todos os campos assinalados com '*'";
        include('NovoLivro.html');
    }
    else if(strpos($titulo, "'") !== false || strpos($autor, "'") !== false || strpos($editora, "'") !== false ||
            strpos($genero, "'") !== false) {
        $connection -> close();
        $mensagemErro = "Digite apenas caracteres alfanumericos nos campos";
        include('NovoLivro.html');
    }
    else if(!is_numeric($edicao)) {
        $connection -> close();
        $mensagemErro = "Digite apenas caracteres numericos no campo 'Edicao'";
        include('NovoLivro.html');
    }
    else {
        $query = "SELECT COUNT(ID)
                  FROM Livro
                  WHERE Proprietario='".$usuario."'
                  GROUP BY Proprietario;";
        $result = mysqli_query($connection, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $quantidadeLivros = $row['COUNT(ID)'];
            break;
        }
        if($quantidadeLivros >= 10) {
            $connection -> close();
            $mensagemErro = "Voce ja excedeu o maximo de 20 livros criados";
            include('NovoLivro.html');
        }
        else {
            if($genero==null) $genero="N/A";
            
            $query = "SELECT ID
                      FROM Livros_Deletados LIMIT 1;";
            $result = mysqli_query($connection, $query);
        
            $IDLivro = null;
            while ($row = mysqli_fetch_assoc($result)) {
                $IDLivro = $row['ID'];
                break;
            }
            if($IDLivro==null) {
                $query = "SELECT Numero_Atual
                          FROM Numeros_Cadastramento;";
                $result = mysqli_query($connection, $query);
        
                while ($row = mysqli_fetch_assoc($result)) {
                    $IDLivro = $row['Numero_Atual'];
                    break;
                }
                $update = $IDLivro+1;
                $query = "UPDATE Numeros_Cadastramento 
                          SET Numero_Atual=".$update."
                          WHERE Nome='Livro';";
                $connection->query($query);
            }
            else {
                $query = "DELETE 
                          FROM Livros_Deletados 
                          WHERE ID=".$IDLivro.";";
                $connection->query($query);
            }
            
            $query = "INSERT INTO Livro VALUES('".$IDLivro."','".$titulo."','".$autor."','".$editora."','".$edicao."','".$genero."','".$usuario."');";
            if ($connection->query($query) === TRUE) {
                $connection -> close();
                $mensagemSucesso = "Livro cadastrado com sucesso!";
                include('NovoLivro.html');
            } 
            else {
                $connection -> close();
                $mensagemErro = "Houve alguma operacao invalida, tente novamente por favor";
                include('NovoLivro.html');
            }
        }
    }
?>