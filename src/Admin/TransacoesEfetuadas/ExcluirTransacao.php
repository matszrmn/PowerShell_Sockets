<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die(mysql_error());

    $transacoesPedidas = $_REQUEST['IDTransacao'];
    
    $paginaAtual = $_REQUEST['paginaAtual'];
    $maxTamanho = $_REQUEST['maxTamanho'];
    $maxPaginas = $_REQUEST['maxPaginas'];
    
    if($transacoesPedidas==null) {
        $mensagemErro = "Selecione uma ou mais transacoes!";
        $primeiroElemento = ($paginaAtual-1)*10;
    
        $query = "SELECT *
                  FROM adminTransacao LIMIT ".$primeiroElemento.",10;";
        $listaTransacoes = mysqli_query($connection, $query);

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
    
    
    foreach($transacoesPedidas as $k => $transacaoPedida) {
        $query = "DELETE
                  FROM Admin
                  WHERE ID=".$transacaoPedida.";";
        if ($connection->query($query) === TRUE) $mensagemSucesso = "Transacao(oes) excluida(s)!";
        else $mensagemErro = "Esta transacao ja foi excluida";
    }
    
    $primeiroElemento = ($paginaAtual-1)*10;
    $query = "SELECT *
              FROM adminTransacao LIMIT ".$primeiroElemento.",10;";
    $listaTransacoes = mysqli_query($connection, $query);
    
    
    $query = "SELECT COUNT(*)
              FROM adminTransacao;";
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