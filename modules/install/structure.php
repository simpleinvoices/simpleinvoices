<?php
global $databaseBuilt;

$menu = false;
if ($menu) {} // eliminates unused warning

// Check if a table that MUST exist in all versions, does exist.
if (!$databaseBuilt) {
    $db = db::getInstance();
    $import = new Import();
    $import->file = "./databases/mysql/structure.sql";
    $import->pattern_find = array('si_', 'DOMAIN-ID', 'LOCALE', 'LANGUAGE');
    $import->pattern_replace = array(TB_PREFIX, '1', 'en_US', 'en_US');
    $db->query($import->collate());
}
