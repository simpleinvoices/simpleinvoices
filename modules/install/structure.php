<?php
$menu = false;
// Check if a table that MUST exist in all versions, does exist.
if (checkTableExists(TB_PREFIX . 'biller') == false) {
    $import = new import();
    $import->file = "./databases/mysql/structure.sql";
    $import->pattern_find = array('si_', 'DOMAIN-ID', 'LOCALE', 'LANGUAGE');
     $import->pattern_replace = array(TB_PREFIX, '1', 'en_US', 'en_US');
    $db->query($import->collate());
}
