<?php
//JSON import

//SQL import

$import = new import();
$import->file = "./databases/MySQL/1-Structure.sql";
$import->pattern_find = "si_";
$import->pattern_replace = TB_PREFIX;
$result = dbQuery($import->collate());
//$result->closeCursor();


$importjson = new importjson();
$importjson->file = "./databases/JSON/EssentialData.json";
//$importjson->debug = true;
$importjson->pattern_find = "si_";
$importjson->pattern_replace = TB_PREFIX;
dbQuery($importjson->collate());

//dbQuery($importjson->collate() . $importjson->collate());
$menu = false;
?>
