<?php
    $host = 'fdb15.biz.nf';
    $user = '2144608_projeto';                     
    $pass = 'hummerh10'; 
    $db = '2144608_projeto';                            //Your database name you want to connect to
    $port = 3306;                               //The port #. It is always 3306
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());
    $mensagemErro = '';
    $paginaAnterior = $_REQUEST['paginaAnterior'];
    
    
    $query = "SELECT * FROM Usuario;";
    $usuarios = mysqli_query($connection, $query);
    
    $query = "SELECT * FROM Aprendizado_Optativo;";
    $relacionamentos = mysqli_query($connection, $query);
    
    $query = "SELECT * FROM Extensao;";
    $extensoes = mysqli_query($connection, $query);
    
    /*$date = date_create_from_format('d/m/Y', '12/02/2001');
    $dataBrasil = date_format($date, 'Y-m-d');*/
    include('Visualizar.html');
?>