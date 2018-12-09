<?php
    function getFileContent($fileName) {
        $file = fopen($fileName, "rb");
        $bytes = fread($file, filesize($fileName));
        fclose($file);
        return $bytes;
    }
    function createResponseFile($fileName, $bytes) {
        $file = fopen($fileName, "wb") or die("Unable to open file!");
        fwrite($file, $bytes);
        fclose($file);
    }
    function deleteFile($fileName) {
        unlink($fileName) or die("Couldn't delete file");
    }
    function download_files($fileDirsResponse, $fileNames, $dir, $zipName, $keys) {
        include("http.php");
        if($keys) {
            createResponseFile($dir."publicKey.txt", $keys[0]);
            createResponseFile($dir."privateKey.txt", $keys[1]);
            array_push($fileDirsResponse, $dir."publicKey.txt");
            array_push($fileDirsResponse, $dir."privateKey.txt");
            array_push($fileNames, "publicKey.txt");
            array_push($fileNames, "privateKey.txt");
        }
        
        $zip = false;
        if(count($fileDirsResponse) > 1) {
            $zip = new ZipArchive;
            $zip->open($dir.$zipName, ZipArchive::CREATE);
            for($i=0; $i < count($fileDirsResponse); $i++) $zip->addFile($fileDirsResponse[$i], $fileNames[$i]);
            $zip->close();
        }
        if($zip) send_headerOf_download($zipName, $dir.$zipName, null);
        else     send_headerOf_download($fileNames[0], $fileDirsResponse[0], null);
        
        for($i=0; $i < count($fileDirsResponse); $i++) deleteFile($fileDirsResponse[$i]);
        deleteFile($dir.$zipName);
        exit();
    }
?>
