<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die(mysql_error());

    $livrosPedidos = $_REQUEST['IDLivro'];
    $usuario = $_REQUEST['usuario'];
    
    if($livrosPedidos==null) {
        $mensagemErro = "Selecione uma ou mais livros!";
    }
    else {
        $connection->query("BEGIN;");
    
        foreach($livrosPedidos as $k => $livroPedido) { 
            $query = "DELETE
                      FROM Livro
                      WHERE ID=".$livroPedido.";";
            if ($connection->query($query) === TRUE) {
                $mensagemSucesso = "Livro(s) excluido(s)!";
                $query = "INSERT INTO Livros_Deletados VALUES(".$livroPedido.");";
                $connection -> query($query);
            }
            else $mensagemErro = "Este livro ja foi excluido!";
            
            $connection->query("COMMIT;");
        }
    }
    $query = "SELECT * FROM Livro WHERE Proprietario='".$usuario."'
                                        AND ID NOT IN (SELECT Livro_ID FROM Livros_Adquiridos);";
    $listaLivros = mysqli_query($connection, $query);
    
    include('ExcluirLivros.html');
?>