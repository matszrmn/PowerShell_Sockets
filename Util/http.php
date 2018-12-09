<?php
    function send_headerOf_download($file_name, $file_dir, $content) {
        header('Content-Disposition: attachment; filename="'.$file_name.'"');
        header('Pragma: no-cache');
        if($file_dir) {
            header('Content-Length: '.filesize($file_dir));
            echo(getFileContent($file_dir));
        }
        else {
            header('Content-Length: '.strlen($content));
            echo($content);
        }
    }
?>
