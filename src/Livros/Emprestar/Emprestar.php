<?php
    $host = 'fdb15.biz.nf';
    $user = '2155673_project';                     
    $pass = 'Viruspc7@'; 
    $db = '2155673_project';
    $port = 3306;
    
    $connection = mysqli_connect($host, $user, $pass, $db, $port) or die(mysql_error());

    $emprestarUsuarios = $_REQUEST['IDLivro'];
    $usuario = $_REQUEST['usuario'];
    
    $paginaAtual = $_REQUEST['paginaAtual'];
    $maxTamanho = $_REQUEST['maxTamanho'];
    $maxPaginas = $_REQUEST['maxPaginas'];
    $action = $_REQUEST['action'];
    
    if($action == '     Remover') {
        if($emprestarUsuarios==null) {
            $mensagemErro = "Selecione um ou mais livros!";
            $primeiroElemento = ($paginaAtual-1)*10;
    
            $query = "SELECT *
                FROM emprestar".$usuario." LIMIT ".$primeiroElemento.",10;";
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
        
        foreach($emprestarUsuarios as $k => $emprestarLivro) {
            $stringDuplicada = $emprestarLivro;
            $stringDuplicada = explode("'",$stringDuplicada);
                
            $usuarioPedidoID = $stringDuplicada[0];
            $livroPedido = $stringDuplicada[1];
                
            $query = "DELETE FROM Pedir_Emprestado
                      WHERE Usuario_ID='".$usuarioPedidoID."' AND
                            Livro_ID=".$livroPedido.";";
            if ($connection->query($query) === TRUE) {
                $mensagemSucesso = "Livro(s) removido(s) com sucesso!";
            }
            else $mensagemErro = "Algum livro ja foi removido";
        }
        
        
        $primeiroElemento = ($paginaAtual-1)*10;
        $query = "SELECT *
                  FROM emprestar".$usuario." LIMIT ".$primeiroElemento.",10;";
        $listaLivros = mysqli_query($connection, $query);
        
    
        $query = "SELECT COUNT(*)
                  FROM emprestar".$usuario.";";
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
    }
    
    
    
    
    else {
        if($emprestarUsuarios==null) {
            $mensagemErro = "Selecione um ou mais livros!";
            $primeiroElemento = ($paginaAtual-1)*10;
    
            $query = "SELECT *
                FROM emprestar".$usuario." LIMIT ".$primeiroElemento.",10;";
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
        
        $existeTupla = false;
        foreach($emprestarUsuarios as $k => $emprestarLivro) {
            $stringDuplicada = $emprestarLivro;
            $stringDuplicada = explode("'",$stringDuplicada);
            $livroPedido = $stringDuplicada[1];
            
            $query = "SELECT * 
                      FROM Livros_Adquiridos
                      WHERE Livro_ID=".$livroPedido.";";
            $result = mysqli_query($connection, $query);
            
            while($row = mysqli_fetch_assoc($result)) {
                $existeTupla=true;
            }
        }
        if($existeTupla==true) {
            $mensagemErro = "Algum livro nao esta mais disponivel";
        }
        else {
            $horaLimite = date('H', strtotime("-3 hours +1 day"));
            $dataLimite = date('Y-m-d', strtotime("-3 hours +1 day"));
            $dataAtual = date('Y-m-d', strtotime("-3 hours"));
            
            foreach($emprestarUsuarios as $k => $emprestarLivro) {
                $stringDuplicada = $emprestarLivro;
                $stringDuplicada = explode("'",$stringDuplicada);
                
                $usuarioPedidoID = $stringDuplicada[0];
                $livroPedido = $stringDuplicada[1];
                
                $query = "INSERT INTO Livros_Adquiridos VALUES('".$usuarioPedidoID."',".$livroPedido.",
                                                        '".$dataLimite."',".$horaLimite.");";
                if ($connection->query($query) === TRUE) {
                    $query = "INSERT INTO Admin VALUES(default,'".$usuario."','".$usuarioPedidoID."','Emprestimo',
                                                           '".$dataAtual."');";
                    $connection -> query($query);
                    
                    $mensagemSucesso = "Livro(s) emprestado(s) com sucesso!";
                    $query = "DELETE FROM Pedir_Emprestado
                              WHERE Usuario_ID='".$usuarioPedidoID."' AND
                                    Livro_ID=".$livroPedido.";";
                    $connection->query($query);
                }
                else $mensagemErro = "Algum livro ja foi emprestado";
            }
        }
        $connection->query("COMMIT;");
    
   
        $primeiroElemento = ($paginaAtual-1)*10;
        $query = "SELECT *
                  FROM emprestar".$usuario." LIMIT ".$primeiroElemento.",10;";
        $listaLivros = mysqli_query($connection, $query);
        
    
        $query = "SELECT COUNT(*)
                  FROM emprestar".$usuario.";";
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
    }
?>