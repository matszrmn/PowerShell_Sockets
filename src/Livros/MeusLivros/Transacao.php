<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());
    
    $usuario = $_REQUEST['usuario'];
    $transacao = $_REQUEST['transacao'];
    
    
    $query = "DROP VIEW livrosRecebidos".$usuario.";";
    $connection -> query($query);
    $query = "DROP VIEW emprestar".$usuario.";";
    $connection -> query($query);
    
    $connection -> close();
    include($transacao);
?>