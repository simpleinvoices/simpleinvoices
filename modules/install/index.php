<?php

//JSON import

$importjson = new importjson();
$importjson->file = "./databases/JSON/EssentialData.json";
$importjson->debug = true;
$importjson->pattern_find = "si_";
$importjson->pattern_replace = TB_PREFIX;
$test = $importjson->collate();
echo "###########<br><br>";
print($test);
//SQL import
echo "<br /><br />###########<br><br>";

$import = new import();
$import->file = "./databases/MySQL/1-Structure.sql";
$import->pattern_find = "si_";
$import->pattern_replace = TB_PREFIX;
echo $import->collate();

?>
