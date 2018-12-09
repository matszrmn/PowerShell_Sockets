<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());
    
    $usuario = $_REQUEST['usuario'];
    
    $query = "DROP VIEW emprestar".$usuario.";";
    $connection -> query($query);
    
    $query = "DROP VIEW livrosPegos".$usuario.";";
    $connection -> query($query);
    
    $query = "DROP VIEW minhasSolicitacoes".$usuario.";";
    $connection -> query($query);
    
    $query = "DROP VIEW livrosRecebidos".$usuario.";";
    $connection -> query($query);
    
    $query = "DROP VIEW pedir".$usuario.";";
    $connection -> query($query);
    
    $query = "DELETE FROM Usuario WHERE ID='".$usuario."';";
    $connection -> query($query);
    $connection -> close();
    $mensagemSucesso = "Conta excluida com sucesso";
    include('../../Usuario/Autentica/Autentica.html');
    return;
?>