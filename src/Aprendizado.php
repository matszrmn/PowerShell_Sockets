<?php
    $host = 'fdb15.biz.nf';
    $user = '2144608_projeto';                     
    $pass = 'hummerh10'; 
    $db = '2144608_projeto';
    $port = 3306;

    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die (mysql_error());
    
    $Usuario = $_REQUEST['Usuario'];
    $Extensao = $_REQUEST['Extensao'];
    $action = $_REQUEST['action'];
    
    $mensagemErro = '';
    $mensagemSucesso = '';
    $descricaoErro = '';
    
    if($action=='Deletar') {
        $query = "SELECT * 
                  FROM Aprendizado_Optativo 
                  WHERE RNA=".$Usuario." AND Extensao_Codigo=".$Extensao.";";
        $result = mysqli_query($connection, $query);
        $existeTupla = false;
        while($row = mysqli_fetch_assoc($result)) {
            $existeTupla = true;
        }
        if(!$existeTupla) {
            $mensagemErro = "Nao existem tuplas com esses valores";
            $connection->close();
            include('AprendizadoIni.php');
            return;
        }
        
        $query = "DELETE FROM Aprendizado_Optativo 
                  WHERE RNA=".$Usuario." AND Extensao_Codigo=".$Extensao;
                  
        if ($connection->query($query) === TRUE) {
            $mensagemSucesso = "Delecao Bem-Sucedida!";
        } 
        else {
            $descricaoErro = mysqli_error($connection);
            $mensagemErro = "Erro ao deletar Aprendizado Optativo";
        }
        include('AprendizadoIni.php');
    }
    else {
        $query = "INSERT INTO Aprendizado_Optativo VALUES(".$Extensao.",".$Usuario.");";
        if ($connection->query($query) === TRUE) {
            $mensagemSucesso = "Insercao Bem-Sucedida!";
        } 
        else {
            $descricaoErro = mysqli_error($connection);
            $mensagemErro = "Erro ao inserir Aprendizado Optativo";
        }
        include('AprendizadoIni.php');
    }
?>