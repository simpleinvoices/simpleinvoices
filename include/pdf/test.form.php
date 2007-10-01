<?php

ob_start();
var_dump($_POST);
$content = ob_get_contents();
error_log($content);
ob_end_clean();

print($content); die();

// ouput an empty FPF file

$outfdf  = fdf_create();
$tmpname = tempnam('/cache/',"FDF_");
fdf_set_status($outfdf, "Thank you!");
fdf_save($outfdf, $tmpname);
fdf_close($outfdf);

fdf_header();
$fp = fopen($tmpname, "r");
fpassthru($fp);
unlink($tmpname);

?>
