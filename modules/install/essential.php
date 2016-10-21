<?php
global $databaseBuilt, $databasePopulated, $db;
$menu = false;
if ($menu) {} // eliminates unused warning.

if ($databaseBuilt && !$databasePopulated) {
    $importjson = new ImportJson();
    $importjson->file = "./databases/json/essential_data.json";
    $importjson->pattern_find = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
    $importjson->pattern_replace = array(TB_PREFIX,'1','en_US','en_US');
    $db->query($importjson->collate());
}
