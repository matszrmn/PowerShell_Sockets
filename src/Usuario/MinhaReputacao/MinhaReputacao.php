<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());
    
    $usuario = $_REQUEST['usuario'];
    $query = "SELECT ID, Nota_Qualificacao, Total_Qualificantes
              FROM Usuario
              WHERE ID='".$usuario."';";
    $result = mysqli_query($connection, $query);
    include('MinhaReputacao.html');
?>