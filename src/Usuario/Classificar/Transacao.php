<?php
    $usuario = $_REQUEST['usuario'];
    $transacao = $_REQUEST['transacao'];
    $IDDevolvedor = $_REQUEST['IDDevolvedor'];
    $Livro_ID = $_REQUEST['Livro_ID'];
    $mensagemErro=null;
    
    include($transacao);
?>