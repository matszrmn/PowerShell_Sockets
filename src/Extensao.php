<?php
    $host = 'fdb15.biz.nf';
    $user = '2144608_projeto';                     
    $pass = 'hummerh10'; 
    $db = '2144608_projeto';                            //Your database name you want to connect to
    $port = 3306;                               //The port #. It is always 3306
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());
    
    $Codigo = $_REQUEST['Codigo'];
    $action = $_REQUEST['action'];
    $mensagemErro = '';
    $mensagemSucesso = '';
    $descricaoErro = '';
    
    
    if($action=='Deletar') {
        $query = "SELECT * FROM Extensao WHERE Extensao_Codigo=".$Codigo.";";
        $result = mysqli_query($connection, $query);
        $existeTupla = false;
        while ($row = mysqli_fetch_assoc($result)) {
            $existeTupla = true;
            break;
        }
        if(!$existeTupla) {
            $connection->close();
            $mensagemErro = "Nao existe uma tupla com esse codigo";
            include('Extensao.html');
            return;
        }
        
        $query = "DELETE
                  FROM Extensao
                  WHERE Extensao_Codigo=".$Codigo.";";
        if ($connection->query($query) === TRUE) {
            $query = "DELETE
                      FROM Aprendizado_Optativo
                      WHERE Extensao_Codigo=".$Codigo.";";
            $connection->query($query);
            $mensagemSucesso = "Delecao Bem-Sucedida!";
        } 
        else {
            $descricaoErro = mysqli_error($connection);
            $mensagemErro = "Erro ao deletar extensao";
        }
        include('Extensao.html');
    }
    else if($action=='Atualizar') {
        $Nome = $_REQUEST['Nome'];
        $DataInicio = $_REQUEST['DataInicio'];
        $HoraInicio = $_REQUEST['HoraInicio'];
        $HoraFim = $_REQUEST['HoraFim'];
        $Iniciativa = $_REQUEST['Iniciativa'];
        $Sala = $_REQUEST['Sala'];
        $Edificio = $_REQUEST['Edificio'];
        $Prof = $_REQUEST['Prof'];
        $Disc = $_REQUEST['Disc'];
        $Unidade = $_REQUEST['Unidade'];
        $Verba = $_REQUEST['Verba'];
        
        $query = "SELECT *
                  FROM Extensao
                  WHERE Extensao_Codigo=".$Codigo.";";
        $result = mysqli_query($connection, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $NomeAntigo = $row['Nome'];
            $DataInicioAntiga = $row['Data_Inicio'];
            $HoraInicioAntiga = $row['Hora_Inicio'];
            $HoraFimAntiga = $row['Hora_Fim'];
            $IniciativaAntiga = $row['Iniciativa_privada'];
            $SalaAntiga = $row['Sala_Codigo'];
            $EdificioAntigo = $row['Edificio_Codigo'];
            $ProfAntigo = $row['Professor_RNA'];
            $DiscAntiga = $row['Disciplina_Codigo'];
            $UnidadeAntiga = $row['Unidade_Codigo'];
            $VerbaAntiga = $row['Verba_Mensal'];
        }
        //echo "HoraFimAntiga=".$HoraFimAntiga;
        if($Nome=='' || $Nome==null) {
            $Nome = $NomeAntigo;
        }
        if($DataInicio=='' || $DataInicio==null) {
            $DataInicio = $DataInicioAntiga;
        }
        if($HoraInicio=='' || $HoraInicio==null) {
            $HoraInicio= $HoraInicioAntiga;
        }
        if($HoraFim=='' || $HoraFim==null) {
            $HoraFim = $HoraFimAntiga;
        }
        if($Iniciativa=='' || $Iniciativa==null) {
            $Iniciativa = $IniciativaAntiga;
        }
        if($Sala=='' || $Sala==null) {
            $Sala = $SalaAntiga;
        }
        if($Edificio=='' || $Edificio==null) {
            $Edificio = $EdificioAntigo;
        }
        if($Prof=='' || $Prof==null) {
            $Prof = $ProfAntigo;
        }
        if($Disc=='' || $Disc==null) {
            $Disc = $DiscAntiga;
        }
        if($Unidade=='' || $Unidade==null) {
            $Unidade = $UnidadeAntiga;
        }
        if($Verba=='' || $Verba==null) {
            $Verba = $VerbaAntiga;
        }
        
        $query = "UPDATE Extensao
                  SET Nome='".$Nome."',Data_Inicio='".$DataInicio."',Hora_Inicio=".$HoraInicio.",Hora_Fim=".$HoraFim.",Iniciativa_privada=".$Iniciativa.",
                      Sala_Codigo=".$Sala.",Edificio_Codigo='".$Edificio."',Professor_RNA=".$Prof.",Disciplina_Codigo='".$Disc."',
                      Unidade_Codigo=".$Unidade.",Verba_Mensal=".$Verba."
                  WHERE Extensao_Codigo=".$Codigo.";";
        
        if ($connection->query($query) === TRUE) {
            $mensagemSucesso = "Atualizacao Bem-Sucedida!";
        } 
        else {
            $descricaoErro = mysqli_error($connection);
            $mensagemErro = "Erro ao atualizar extensao";
        }
        include('Extensao.html');
    }
    else {
        $Nome = $_REQUEST['Nome'];
        $DataInicio = $_REQUEST['DataInicio'];
        $HoraInicio = $_REQUEST['HoraInicio'];
        $HoraFim = $_REQUEST['HoraFim'];
        $Iniciativa = $_REQUEST['Iniciativa'];
        $Sala = $_REQUEST['Sala'];
        $Edificio = $_REQUEST['Edificio'];
        $Prof = $_REQUEST['Prof'];
        $Disc = $_REQUEST['Disc'];
        $Unidade = $_REQUEST['Unidade'];
        $Verba = $_REQUEST['Verba'];
        
        
        if($DataInicio=='' || $DataInicio==null) {
            $descricaoErro = mysqli_error($connection);
            $mensagemErro = "Erro ao inserir extensao";
            $connection -> close();
            include('Extensao.html');
            return;
        }
        $query = "INSERT INTO Extensao VALUES (".$Codigo.",'".$Nome."','".$DataInicio."',".$HoraInicio.",".$HoraFim.",".$Iniciativa.",".$Sala.",
                                              '".$Edificio."',".$Prof.",'".$Disc."',".$Unidade.",".$Verba.");";
        
        if ($connection->query($query) === TRUE) {
            $mensagemSucesso = "Insercao Bem-Sucedida!";
        } 
        else {
            $descricaoErro = mysqli_error($connection);
            $mensagemErro = "Erro ao inserir extensao";
        }
        include('Extensao.html');
    }
    $connection -> close();
?>