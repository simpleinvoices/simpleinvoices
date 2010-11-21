<?php

$host = $_SERVER['HTTP_HOST'];

preg_match("#^(.*)/[^/]*$#", $_SERVER['REQUEST_URI'], $matches);
$uri = $matches[1];

header("Location: http://${host}${uri}/demo/index.php");

?>