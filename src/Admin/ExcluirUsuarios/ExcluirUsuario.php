<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die(mysql_error());

    $usuariosPedidos = $_REQUEST['IDUsuario'];
    
    $paginaAtual = $_REQUEST['paginaAtual'];
    $maxTamanho = $_REQUEST['maxTamanho'];
    $maxPaginas = $_REQUEST['maxPaginas'];
    
    if($usuariosPedidos==null) {
        $mensagemErro = "Selecione um ou mais usuarios!";
        $primeiroElemento = ($paginaAtual-1)*10;
    
        $query = "SELECT *
                  FROM adminExclusao LIMIT ".$primeiroElemento.",10;";
        $listaUsuarios = mysqli_query($connection, $query);

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
    
    
    foreach($usuariosPedidos as $k => $usuarioPedido) {
        $query = "DELETE
                   FROM Livro
                   WHERE Proprietario='".$usuarioPedido."'";
        $query2 = "DELETE
                   FROM Livros_Adquiridos
                   WHERE Usuario_ID='".$usuarioPedido."';";
        $query3 = "DELETE
                  FROM Usuario
                  WHERE ID='".$usuarioPedido."';";
        if ($connection->query($query) === TRUE) {
            if($connection->query($query2) === TRUE && $connection->query($query3) === TRUE) $mensagemSucesso = "Usuario(s) excluido(s)!";
            else $mensagemErro = "Ainda nao foram excluidas as transacoes do usuario";
        }
        else $mensagemErro = "Ainda nao foram excluidas as transacoes do usuario";
    }
    
    $primeiroElemento = ($paginaAtual-1)*10;
    $query = "SELECT *
              FROM adminExclusao LIMIT ".$primeiroElemento.",10;";
    $listaUsuarios = mysqli_query($connection, $query);
    
    
    $query = "SELECT COUNT(*)
              FROM adminExclusao;";
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