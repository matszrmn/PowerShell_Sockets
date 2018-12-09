<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());
    
    $usuario = $_REQUEST['usuario'];
    $Nota = $_REQUEST['Nota'];
    $IDDevolvedor = $_REQUEST['IDDevolvedor'];
    $Livro_ID = $_REQUEST['Livro_ID'];
    
    $mensagemErro = null;
    if(!is_numeric($Nota)) {
        $mensagemErro = "Digite apenas caracteres numericos no campo da nota";
    }
    else if($Nota<0 || $Nota>10) {
        $mensagemErro = "Digite apenas numeros entre 0 e 10 no campo da nota";
    }
    if($mensagemErro!=null) {
        include ('AtribuirNota.html');
        $connection -> close();
        return;
    }
    else {
        $query = "SELECT Nota_Qualificacao, Total_Qualificantes
                  FROM Usuario
                  WHERE ID='".$IDDevolvedor."';";
        $result = mysqli_query($connection, $query);
        if($result==null) {
            $mensagemErro = "Voce ja classificou esse emprestimo";
            include('AtribuirNota.html');
            return;
        }
        $Nota_Antiga = 0;
        $Total_Qualificacoes = 0;
        while($row = mysqli_fetch_assoc($result)) {
            $Nota_Antiga = $row['Nota_Qualificacao'];
            $Total_Qualificacoes = $row['Total_Qualificantes'];
        }
        if($Total_Qualificacoes==0) {
            $Total_Qualificacoes=1;
            $NotaAtual = $Nota;
        }
        else {
            $SomaNotasAntigas = $Total_Qualificacoes*$Nota_Antiga;
            $SomaTotalNotas = $SomaNotasAntigas + $Nota;
            
            $Total_Qualificacoes = $Total_Qualificacoes+1;
            $NotaAtual = $SomaTotalNotas/$Total_Qualificacoes;
        }
        $query = "UPDATE Usuario
                  SET Nota_Qualificacao=".$NotaAtual.",Total_Qualificantes=".$Total_Qualificacoes."
                  WHERE ID='".$IDDevolvedor."';";
        if ($connection->query($query) === TRUE) {
            $query = "DELETE
                      FROM Livros_Recebidos_De_Volta
                      WHERE Usuario_Que_Devolveu='".$IDDevolvedor."' AND
                            Livro_ID=".$Livro_ID.";";
            $connection->query($query);
            $connection -> close();
            include('ClassificacaoSucesso.html');
        }
        else {
            $mensagemErro = "Voce ja classificou esse emprestimo";
            include('AtribuirNota.html');
        }
    }
?>