<?php
    $host = 'fdb15.biz.nf';
    $user = '2144608_projeto';                     
    $pass = 'hummerh10'; 
    $db = '2144608_projeto';
    $port = 3306;

    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die (mysql_error());
    //$mensagemErro = $_REQUEST['$mensagemErro'];
    //$mensagemSucesso = $_REQUEST['$mensagemSucesso'];
    
    $query = "SELECT RNA FROM Usuario;";
    $usuarios = mysqli_query($connection, $query);
    
    $query = "SELECT Extensao_Codigo FROM Extensao;";
    $extensoes = mysqli_query($connection, $query);
    
    include('Aprendizado.html');
?>