<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die(mysql_error());

    $usuariosDevolucoes = $_REQUEST['IDDevolvedor'];
    $usuario = $_REQUEST['usuario'];
    
    $paginaAtual = $_REQUEST['paginaAtual'];
    $maxTamanho = $_REQUEST['maxTamanho'];
    $maxPaginas = $_REQUEST['maxPaginas'];
    
    //$horaLimite = date('H');
    //$dataLimite = date('d/m/Y', strtotime("+3 days"));
    //echo $dataLimite;
    //echo $horaLimite;
    
    if($usuariosDevolucoes==null) {
        $mensagemErro = "Selecione um ou mais livros!";
        $primeiroElemento = ($paginaAtual-1)*10;
    
        $query = "SELECT *
            FROM livrosRecebidos".$usuario." LIMIT ".$primeiroElemento.",10;";
        $listaLivros = mysqli_query($connection, $query);

        $listaPaginas = array($paginaAtual);
        $valorInicio = $paginaAtual - 1;
        $valorFim = $paginaAtual + 1;
    
        while(count($listaPaginas) < 5) {
            if($valorInicio < 1 && $valorFim > $maxPaginas) break;
        
            if($valorInicio >= 1) array_unshift($listaPaginas, $valorInicio); //Inserir no inicio da lista
            if($valorFim <= $maxPaginas) array_push($listaPaginas, $valorFim); //Inserir no fim da lista
            $valorInicio--;
            $valorFim++;
        }
        $connection->close();
        include('ListarPossiveis.html');
        return;
    }
    
    $connection->query("BEGIN;");
    
    $stringDuplicada;
    foreach($usuariosDevolucoes as $k => $usuarioDevolveu) { 
        $stringDuplicada = $usuarioDevolveu;
        $stringDuplicada = explode("'",$stringDuplicada);
        
        $usuarioDevolveuID = $stringDuplicada[0];
        $livroDevolveuID = $stringDuplicada[1];
        
        $query = "DELETE
                  FROM Livros_Recebidos_De_Volta
                  WHERE Usuario_Que_Devolveu='".$usuarioDevolveuID."' 
                        AND Livro_ID=".$livroDevolveuID.";";
                                        
        if ($connection->query($query) === TRUE) $mensagemSucesso = "Livro(s) removido(s)!";
        else $mensagemErro = "Este livro ja foi removido";
    }
    /*$query = "SELECT * 
              FROM Pedir_Emprestado, Livro
              WHERE Usuario_ID='".$usuario."' AND
                    Livro_ID=".$livroPedido.";";
    $result = mysqli_query($connection, $query);
    $existeTupla = false;
    while($row = mysqli_fetch_assoc($result)) {
        $existeTupla=true;
    }
    if($existeTupla==false) {
        $mensagemErro = "Esta solicitacao ja foi removida!";
    }
    else {*/
        
    $connection->query("COMMIT;");
    
    
    $primeiroElemento = ($paginaAtual-1)*10;
    $query = "SELECT *
              FROM livrosRecebidos".$usuario." LIMIT ".$primeiroElemento.",10;";
    $listaLivros = mysqli_query($connection, $query);
    
    $query = "SELECT COUNT(*)
              FROM livrosRecebidos".$usuario.";";
    $result = mysqli_query($connection, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $maxTamanho = $row['COUNT(*)'];
        break;
    }
    $connection -> close();
    
    $auxMaxTamanho = $maxTamanho-10; 
    $maxPaginas = ceil($maxTamanho/10);
    $primeiroElemento = ($paginaAtual-1)*10;
    
    $listaPaginas = array($paginaAtual);
    $valorInicio = $paginaAtual - 1;
    $valorFim = $paginaAtual + 1;
    
    while(count($listaPaginas) < 5) {
        if($valorInicio < 1 && $valorFim > $maxPaginas) break;
        
        if($valorInicio >= 1) array_unshift($listaPaginas, $valorInicio); //Inserir no inicio da lista
        if($valorFim <= $maxPaginas) array_push($listaPaginas, $valorFim); //Inserir no fim da lista
        $valorInicio--;
        $valorFim++;
    }
    include('ListarPossiveis.html');
?>