<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die(mysql_error());
    $usuario = $_REQUEST['usuario'];
    
    $buscaTitulo = $_REQUEST['buscaTitulo'];
    $buscaAutor = $_REQUEST['buscaAutor'];
    $buscaEditora = $_REQUEST['buscaEditora'];
    $buscaGenero = $_REQUEST['buscaGenero'];
    $buscaProprietario = $_REQUEST['buscaProprietario'];
    $buscaDataEntrega = $_REQUEST['buscaDataEntrega'];
    $buscaHoraLimite = $_REQUEST['buscaHoraLimite'];
    
    if($buscaTitulo==null) $buscaTitulo="";
    if($buscaAutor==null) $buscaAutor="";
    if($buscaEditora==null) $buscaEditora="";
    if($buscaGenero==null) $buscaGenero="";
    if($buscaProprietario==null) $buscaProprietario="";
    if($buscaDataEntrega==null) $buscaDataEntrega="";
    
    if($buscaHoraLimite==null) $buscaHoraLimite="";
    else if(!is_numeric($buscaHoraLimite)) {
        $mensagemErro="Digite apenas caracteres numericos no campo 'Hora limite de entrega'";
    }
    
    $maxTamanho = 0;
    $maxPaginas = 0;
    $paginaAtual = 1;
    //$listaLivros
    //$listaPaginas
    

    $query = "CREATE OR REPLACE VIEW livrosPegos".$usuario." 
                                        AS (SELECT *
                                            FROM Livros_Adquiridos, Livro
                                            WHERE Usuario_ID='".$usuario."' AND
                                            
                                            Titulo LIKE '%".$buscaTitulo."%' AND
                                            Autor LIKE '%".$buscaAutor."%' AND
                                            Editora LIKE '%".$buscaEditora."%' AND
                                            Genero LIKE '%".$buscaGenero."%' AND
                                            Max_Data_Entrega LIKE '%".$buscaDataEntrega."%' AND
                                            Hora_Limite LIKE '%".$buscaHoraLimite."%' AND
                                            Livro_ID IN (SELECT ID
                                                         FROM Livro
                                                         WHERE Proprietario LIKE '%".$buscaProprietario."%') AND 
                                            Livro_ID = ID);";
    $connection -> query($query);    
    $query = "SELECT *
              FROM livrosPegos".$usuario." LIMIT 0,10;";
    $listaLivros = mysqli_query($connection, $query);
    
    $query = "SELECT COUNT(*)
              FROM livrosPegos".$usuario.";";
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $maxTamanho = $row['COUNT(*)'];
        break;
    }
    $connection -> close();
    
    $maxPaginas = ceil($maxTamanho/10);
    $auxMaxTamanho = $maxTamanho-10; 
    if($auxMaxTamanho <= 0) { //10 Livros disponiveis ou menos
        include('ListarPossiveis.html'); //Apenas 1 pagina
    }
    else {
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
    }
?>