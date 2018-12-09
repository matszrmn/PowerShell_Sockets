<?php
    function generateKeys() {
        $ssl = openssl_pkey_new();
        $privateKey;
        $publicKey;
        
        openssl_pkey_export($ssl, $privateKey);
        $publicKey = openssl_pkey_get_details($ssl)["key"];
        
        return array($publicKey, $privateKey);
    }
    function encrypt_string($string, $publicKey) {
        $encrypted;
        $encrypted_fragment;
        
        for($i=0; $i < ceil(strlen($string)/245); $i++) {
            openssl_public_encrypt(substr($string, $i*245, 245), $encrypted_fragment, $publicKey);
            $encrypted = $encrypted.$encrypted_fragment;
        }
        return $encrypted;
    }
    function decrypt_string($encrypted, $privateKey) {
        $decrypted;
        $decrypted_fragment;
        
        for($i=0; $i < ceil(strlen($encrypted)/256); $i++) {
            openssl_private_decrypt(substr($encrypted, $i*256, 256), $decrypted_fragment, $privateKey);
            $decrypted = $decrypted.$decrypted_fragment;
        }
        return $decrypted;
    }
    function encryptOrDecrypt_files($fileDirs, $fileNames, $publicKey, $privateKey, $dir, $encrypt_opt) {
        include("files.php");
        include("strings.php");
        
        $diskSpace = 0;
        $fileDirsResponse = array();
        
        for($i = 0; $i < count($fileDirs); $i++) {
            $diskSpace += filesize($fileDirs[$i]);
            if($diskSpace >= 300000000) return false;
            
            if($encrypt_opt) {
                createResponseFile($dir.$fileNames[$i], encrypt_string(getFileContent($fileDirs[$i]), $publicKey));
                array_push($fileDirsResponse, $dir.$fileNames[$i]);
                $fileNames[$i] = "KRYPT".$fileNames[$i];
            }
            else {
                createResponseFile($dir.$fileNames[$i], decrypt_string(getFileContent($fileDirs[$i]), $privateKey));
                array_push($fileDirsResponse, $dir.$fileNames[$i]);
                $fileNames[$i] = replace_first("KRYPT", "", $fileNames[$i]);
            }
            deleteFile($fileDirs[$i]);
        }
        if($encrypt_opt) {
            if(!$privateKey) download_files($fileDirsResponse, $fileNames, $dir, "encrypt.zip", false);
            else download_files($fileDirsResponse, $fileNames, $dir, "encrypt.zip", array($publicKey, $privateKey));
        }
        else {
            if(!$publicKey) download_files($fileDirsResponse, $fileNames, $dir, "decrypt.zip", false);
            else download_files($fileDirsResponse, $fileNames, $dir, "decrypt.zip", array($publicKey, $privateKey));
        }
    }
?>
