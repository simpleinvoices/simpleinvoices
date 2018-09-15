<?php

if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $file = fopen($filename, 'w');
        fwrite($file, $data);
        fclose($file);
    }
}