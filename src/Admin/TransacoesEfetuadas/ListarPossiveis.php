<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die(mysql_error());
    $usuario = $_REQUEST['usuario'];
    
    $maxTamanho = 0;
    $maxPaginas = 0;
    $paginaAtual = 1;
    
    //$listaPaginas
    
    
    
    //$dataAtual = strtotime(date("Y-m-d H:i:s"). '-3 Hours');
    //$dataDB = date('Y-m-d', $dataAtual);
    //$dataBrasil = date('d/m/Y', $dataAtual);
    //echo $dataDB;
    //echo(date("H")-3);
    


    $query = "CREATE OR REPLACE VIEW adminTransacao AS (SELECT Admin.ID AS ID,Proprietario,Prop.Telefone AS Prop_Tel,Beneficiado,
                                                        Benef.Telefone AS Benef_Tel,Tipo_Transacao,Data_Transacao
                                                        FROM Admin, Usuario AS Prop, Usuario AS Benef
                                                        WHERE Proprietario=Prop.ID AND Beneficiado=Benef.ID);";
    $connection -> query($query);    
    $query = "SELECT *
              FROM adminTransacao LIMIT 0,10;";
    $listaTransacoes = mysqli_query($connection, $query);
    
    $query = "SELECT COUNT(*)
              FROM adminTransacao;";
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