<?php
    include("Util/crypt.php");
    
    $crypt = generateKeys();
    $publicKey = $_POST['publicKey'];
    $privateKey = $_POST['privateKey'];
    if($publicKey === NULL) $publicKey = $crypt[0];
    if($privateKey === NULL) $privateKey = $crypt[1];
    unset($crypt);
    
    $fileNames = $_FILES['files']['name'];
    $fileDirs = $_FILES['files']['tmp_name'];
    $dir_res = "Files_Res/";
    $currentFile;
    $content;
    
    if(count($fileNames) != 0) {
        $downloadKeys = $_POST['downloadKeys'];
        
        if(isset($_POST['encript'])) {
            if($downloadKeys) encryptOrDecrypt_files($fileDirs, $fileNames, $publicKey, $privateKey, $dir_res, 1);
            else              encryptOrDecrypt_files($fileDirs, $fileNames, $publicKey, false, $dir_res, 1);
        }
        elseif(isset($_POST['decript'])) {
            if($downloadKeys) encryptOrDecrypt_files($fileDirs, $fileNames, $publicKey, $privateKey, $dir_res, 0);
            else              encryptOrDecrypt_files($fileDirs, $fileNames, false, $privateKey, $dir_res, 0);
        }
    }
    include("View/index.html");
?>
