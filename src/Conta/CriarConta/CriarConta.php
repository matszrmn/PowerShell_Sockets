<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die(mysql_error());

    $nome = $_REQUEST['nome'];
    $email = $_REQUEST['email'];
    $usuario = $_REQUEST['usuario'];
    $telefone = $_REQUEST['telefone'];
    $senha = $_REQUEST['senha'];
    $repeticaoSenha = $_REQUEST['repeticaoSenha'];

    $error = false;
    $mensagemErro = null;
    if(!is_numeric($telefone) && $telefone!=null) {
        $telefone=null;
        $mensagemErro = "Digite apenas caracteres numericos no campo Telefone";
    }
    if(strpos($nome, "'") !== false) {
        $nome=null;
        $mensagemErro = "Digite apenas caracteres alfanumericos nos campos";
    }
    if(strpos($email, "'") !== false) {
        $email=null;
        $mensagemErro = "Digite apenas caracteres alfanumericos nos campos";
    }
    if(strpos($usuario, "'") !== false) {
        $usuario=null;
        $mensagemErro = "Digite apenas caracteres alfanumericos nos campos";
    }
    if(strpos($senha, "'") !== false) {
        $mensagemErro = "Digite apenas caracteres alfanumericos nos campos";
    }
    if($senha != $repeticaoSenha) {
        $mensagemErro =  "As senhas digitadas sao diferentes";
    }
    if($nome==null || $email==null || $usuario==null || $senha==null || $repeticaoSenha==null) {
        if($error==false) {
            $mensagemErro = "Os campos assinalados com '*' devem ser preenchidos";
        }
    }
    
    if($mensagemErro!=null) {
        $connection -> close();
        include('CriarConta.html');
    }
    else {
        $query = "INSERT INTO Usuario VALUES('".$usuario."','".$nome."','".$email."','".$senha."','".$telefone."',10,0);";
        if ($connection->query($query) === TRUE) {
            $connection -> close();
            
            include('CriacaoBemSucedida.html');
        }
        else {
            $query = "SELECT *
                  FROM Usuario 
                  WHERE Email='".$email."'";
            $result = mysqli_query($connection, $query);
            $existeTupla = false;
            while ($row = mysqli_fetch_assoc($result)) {
                $nome = $row['Nome'];
                $existeTupla = true;
            }
            $connection -> close();
            
            if($existeTupla==true) { //Email ja existente
                $mensagemErro = "Este Email ja esta cadastrado";
                include('CriarConta.html');
            }
            else { //Usuario ja existente
                $mensagemErro = "Este Usuario ja esta cadastrado";
                include('CriarConta.html');   
            }
        }
    }
    
    /*if($nome==null || $email==null || $usuario==null || $senha==null || $repeticaoSenha==null) {
        $connection -> close();
        $mensagemErro = "Os campos assinalados com '*' devem ser preenchidos";
        include('../Conta/CriarConta/CriarConta.html');
    }*/
    
    /*else if(strpos($nome, "'") !== false || strpos($email, "'") !== false || strpos($usuario, "'") !== false ||
            strpos($senha, "'") !== false || strpos($repeticaoSenha, "'") !== false) {
        
        /*$queryTrigger = "CREATE TRIGGER Integ BEFORE UPDATE ON Pedir_Emprestado
                         FOR EACH ROW
                         BEGIN
		                    signal sqlstate '45000' set message_text = 'Integridade_Pedidr';
                         END;";
        $connection->query($queryTrigger);*/
        
        /*$connection -> close();
        $mensagemErro = "Digite apenas caracteres alfanumericos nos campos";
        
        include('../Conta/CriarConta/CriarConta.html');
    }*/
    /*else if($senha != $repeticaoSenha) {
        $connection -> close();
        $mensagemErro =  "As senhas digitadas sao diferentes";
        include('../Conta/CriarConta/CriarConta.html');
    }
    else if(!is_numeric($telefone) && $telefone!=null) {
        $connection -> close();
        $mensagemErro = "Digite apenas caracteres numericos no campo Telefone";
        include('../Conta/CriarConta/CriarConta.html');
    }
    else {
        $query = "INSERT INTO Usuario VALUES('".$usuario."','".$nome."','".$email."','".$senha."','".$telefone."',default,default);";
        if ($connection->query($query) === TRUE) {
            $connection -> close();
            
            include('../Conta/CriarConta/CriacaoBemSucedida.html');
        } 
        else {
            $query = "SELECT *
                  FROM Usuario 
                  WHERE Email='".$email."'";
            $result = mysqli_query($connection, $query);
            $existeTupla = false;
            while ($row = mysqli_fetch_assoc($result)) {
                $nome = $row['Nome'];
                $existeTupla = true;
            }
            $connection -> close();
            
            if($existeTupla==true) { //Email ja existente
                $mensagemErro = "Este Email ja esta cadastrado";
                include('../Conta/CriarConta/CriarConta.html');
            }
            else { //Usuario ja existente
                $mensagemErro = "Este Usuario ja esta cadastrado";
                include('../Conta/CriarConta/CriarConta.html');   
            }
        }
    }*/
?>