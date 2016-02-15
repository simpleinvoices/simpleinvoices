<?php

$menu = false;

if ($databaseBuilt && !$databasePopulated) {
    $importjson = new importjson();
    $importjson->file = "./databases/json/essential_data.json";
    $importjson->pattern_find = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
    $importjson->pattern_replace = array(TB_PREFIX,'1','en_US','en_US');
    $db->query($importjson->collate());
}
