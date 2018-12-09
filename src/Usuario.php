<?php
    $host = 'fdb15.biz.nf';
    $user = '2144608_projeto';                     
    $pass = 'hummerh10'; 
    $db = '2144608_projeto';                            //Your database name you want to connect to
    $port = 3306;                               //The port #. It is always 3306
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());
    
    $RNA = $_REQUEST['RNA'];
    $action = $_REQUEST['action'];
    $mensagemErro = '';
    $mensagemSucesso = '';
    $descricaoErro = '';
    
    
    if($action=='Deletar') {
        $query = "SELECT * FROM Usuario WHERE RNA=".$RNA.";";
        $result = mysqli_query($connection, $query);
        $existeTupla = false;
        while ($row = mysqli_fetch_assoc($result)) {
            $existeTupla = true;
            break;
        }
        if(!$existeTupla) {
            $connection->close();
            $mensagemErro = "Nao existe uma tupla com esse RNA";
            include('index.html');
            return;
        }
        
        $query = "DELETE
                  FROM Usuario
                  WHERE RNA=".$RNA.";";
        if ($connection->query($query) === TRUE) {
            $query = "DELETE
                      FROM Aprendizado_Optativo
                      WHERE RNA=".$RNA.";";
            $connection->query($query);
            $mensagemSucesso = "Delecao Bem-Sucedida!";
        } 
        else {
            $descricaoErro = mysqli_error($connection);
            $mensagemErro = "Erro ao deletar usuario";
        }
        include('index.html');
    }
    else if($action=='Atualizar') {
        $CPF = $_REQUEST['CPF'];
        $Email = $_REQUEST['Email'];
        $Nome = $_REQUEST['Nome'];
        $DataNasc = $_REQUEST['DataNasc'];
        $Telefone = $_REQUEST['Telefone'];
        $Endereco = $_REQUEST['Endereco'];
        $Genero = $_REQUEST['Genero'];
        $Grau = $_REQUEST['Grau'];
        
        
        $query = "SELECT * 
                  FROM Usuario
                  WHERE RNA=".$RNA.";";
        $result = mysqli_query($connection, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $CPFAntigo = $row['CPF'];
            $EmailAntigo = $row['Email'];
            $NomeAntigo = $row['Nome'];
            $DataNascAntiga = $row['Data_Nasc'];
            $TelefoneAntigo = $row['Telefone'];
            $EnderecoAntigo = $row['Endereco'];
            $GeneroAntigo = $row['Genero'];
            $GrauAntigo = $row['Grau_Ensino'];
            break;
        }
        if($CPF=='' || $CPF==null) {
            $CPF = $CPFAntigo;
        }
        if($Email=='' || $Email==null) {
            $Email = $EmailAntigo;
        }
        if($Nome=='' || $Nome==null) {
            $Nome= $NomeAntigo;
        }
        if($DataNasc=='' || $DataNasc==null) {
            $DataNasc = $DataNascAntiga;
        }
        if($Telefone=='' || $Telefone==null) {
            $Telefone = $TelefoneAntigo;
        }
        if($Endereco=='' || $Endereco==null) {
            $Endereco = $EnderecoAntigo;
        }
        if($Genero=='' || $Genero==null) {
            $Genero = $GeneroAntigo;
        }
        if($Grau=='' || $Grau==null) {
            $Grau = $GrauAntigo;
        }
        $query = "UPDATE Usuario
                  SET CPF=".$CPF.",Email='".$Email."',Nome='".$Nome."',Data_Nasc='".$DataNasc."',Telefone='".$Telefone."',Endereco='".$Endereco."',
                      Genero='".$Genero."',Grau_Ensino='".$Grau."'
                  WHERE RNA=".$RNA.";";
        
        if ($connection->query($query) === TRUE) {
            $mensagemSucesso = "Atualizacao Bem-Sucedida!";
        } 
        else {
            $descricaoErro = mysqli_error($connection);
            $mensagemErro = "Erro ao atualizar usuario";
        }
        include('index.html');
    }
    else {
        $CPF = $_REQUEST['CPF'];
        $Email = $_REQUEST['Email'];
        $Nome = $_REQUEST['Nome'];
        $DataNasc = $_REQUEST['DataNasc'];
        $Telefone = $_REQUEST['Telefone'];
        $Endereco = $_REQUEST['Endereco'];
        $Genero = $_REQUEST['Genero'];
        $Grau = $_REQUEST['Grau'];
        
        
        if($DataNasc=='' || $DataNasc==null) {
            $descricaoErro = mysqli_error($connection);
            $mensagemErro = "Erro ao inserir usuario";
            $connection -> close();
            include('index.html');
            return;
        }
        /*$date = date_create_from_format('d/m/Y', $DataNasc);
        $DataNasc = date_format($date, 'Y-m-d');*/
        $Genero = strtoupper($Genero);
    
        $query = "INSERT INTO Usuario VALUES (".$RNA.",".$CPF.",'".$Email."','".$Nome."','".$DataNasc."','".$Telefone."','".$Endereco."',
                                              '".$Genero."','".$Grau."');";
        
        if ($connection->query($query) === TRUE) {
            $mensagemSucesso = "Insercao Bem-Sucedida!";
        } 
        else {
            $descricaoErro = mysqli_error($connection);
            $mensagemErro = "Erro ao inserir usuario";
        }
        include('index.html');
    }
    $connection -> close();
?>