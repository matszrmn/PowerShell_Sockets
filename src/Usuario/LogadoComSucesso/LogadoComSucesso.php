<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());
    
    $usuario = $_REQUEST['usuario'];
    
    $nome = "pattern";
    $query = "SELECT *
              FROM Usuario 
              WHERE ID='".$usuario."';";
              $result = mysqli_query($connection, $query);
        
    while ($row = mysqli_fetch_assoc($result)) {
        $nome = $row['Nome'];
    }
    include('LogadoComSucesso.html');
?>