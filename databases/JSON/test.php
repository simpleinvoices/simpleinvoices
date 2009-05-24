<?php

$myFile = "EssentialData.json";
$json = file_get_contents($myFile, true);

var_dump(json_decode($json));


?>
