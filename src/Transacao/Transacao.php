<?php
    $usuario = $_REQUEST['usuario'];
    $transacao = $_REQUEST['transacao'];
    
    if($usuario==null) {
        include('../../Usuario/Autentica/Autentica.html');
        return;
    }
    include($transacao);
?>