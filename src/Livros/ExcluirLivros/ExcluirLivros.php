<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die(mysql_error());

    $usuario = $_REQUEST['usuario'];
    
    $query = "SELECT * FROM Livro WHERE Proprietario='".$usuario."'
                                        AND ID NOT IN (SELECT Livro_ID FROM Livros_Adquiridos);";
    $listaLivros = mysqli_query($connection, $query);
    include('ExcluirLivros.html');
?>