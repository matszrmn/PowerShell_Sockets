<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());
    
    $usuario = $_REQUEST['usuario'];
    $senha = $_REQUEST['senha'];
    $mensagemErro = '';
    
    if($usuario=='admin' && $senha=='admin') {
        include('../../Admin/LogadoComSucesso.html');
        return;
    }
    
    if ($usuario==null && $senha==null) {
        $connection -> close();
        $mensagemErro = 'Digite um usuario e uma senha';
        include('../../Usuario/Autentica/Autentica.html');
    }
    else if(strpos($usuario, "'") !== false || strpos($senha, "'") !== false) {
        $connection -> close();
        $mensagemErro = 'Digite apenas caracteres alfanumericos nos campos';
        include('../../Usuario/Autentica/Autentica.html');
    }
    else if($usuario==null) {
        $connection -> close();
        $mensagemErro = 'Digite um usuario ou email';
        include('../../Usuario/Autentica/Autentica.html');
    }
    else if($senha==null) {
        $connection -> close();
        $mensagemErro = 'Digite uma senha';
        include('../../Usuario/Autentica/Autentica.html');
    }
    else {
        $query = "SELECT *
                  FROM Usuario 
                  WHERE Email='".$usuario."'";
        $result = mysqli_query($connection, $query);
        $existeCadastro = false;
        while ($row = mysqli_fetch_assoc($result)) {
            $existeCadastro = true;
            break;
        }
        if(!$existeCadastro) {
            $query = "SELECT *
                  FROM Usuario 
                  WHERE ID='".$usuario."'";
            $result = mysqli_query($connection, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $existeCadastro = true;
                break;
            }
            if(!$existeCadastro) {
                $mensagemErro = 'Conta inexistente do usuario ou email';
                include ('../../Usuario/Autentica/Autentica.html');
                return;
            }
        }
        
        $query = "SELECT *
                  FROM Usuario 
                  WHERE Email='".$usuario."' AND Senha='".$senha."';";
        $result = mysqli_query($connection, $query);
        $existeTupla = false;
        
        if($result!=null) { //Procurando dados pelo Email
            while ($row = mysqli_fetch_assoc($result)) {
                $nome = $row['Nome'];
                $usuario = $row['ID'];
                $existeTupla = true;
                break;
            }
        }
        if($existeTupla==false) { //Procurando dados Pelo Usuario
            $query = "SELECT *
                      FROM Usuario 
                      WHERE ID='".$usuario."' AND Senha='".$senha."';";
            $result = mysqli_query($connection, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $nome = $row['Nome'];
                $existeTupla = true;
            }
        }    
        $connection -> close();
        
        if($existeTupla==false) {
            $mensagemErro = 'Usuario ou senha incorreto(s)';
            include('../../Usuario/Autentica/Autentica.html');
        }
        else {
            include('../../Usuario/LogadoComSucesso/LogadoComSucesso.html');
        }        
    }
?>