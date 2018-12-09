<?php
    function isBinary($str) {
        return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
    }
    function get_bytes($str) {
        return array_slice(unpack("C*", "\0".$str), 1);
    }
    function get_str($bytes) {
        return implode("", $bytes);
    }
    function replace_first($find, $replace, $str) {
        return implode($replace, explode($find, $str, 2));
    }
?>
