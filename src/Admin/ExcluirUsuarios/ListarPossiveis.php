<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die(mysql_error());
    
    $maxTamanho = 0;
    $maxPaginas = 0;
    $paginaAtual = 1;


    $query = "CREATE OR REPLACE VIEW adminExclusao AS (SELECT ID,Nome,Email,Nota_Qualificacao,Total_Qualificantes
                                                       FROM Usuario);";
    $connection -> query($query);    
    $query = "SELECT *
              FROM adminExclusao LIMIT 0,10;";
    $listaUsuarios = mysqli_query($connection, $query);
    
    $query = "SELECT COUNT(*)
              FROM adminExclusao;";
    $result = mysqli_query($connection, $query);
    
    if($result!=null) {
        while ($row = mysqli_fetch_assoc($result)) {
            $maxTamanho = $row['COUNT(*)'];
            break;
        }
    }
    else $maxTamanho = 0;
    $connection -> close();
    
    $auxMaxTamanho = $maxTamanho-10; 
    $maxPaginas = ceil($maxTamanho/10);
    if($auxMaxTamanho <= 0) { //10 disponiveis ou menos
        include('ListarPossiveis.html');
        return; //Apenas 1 pagina
    }

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