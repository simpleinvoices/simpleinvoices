<?php
$menu = false;

// Step 1: Create tables if they don't exist
if (checkTableExists() == false) {
	$import = new import();
	$import->file = "./databases/mysql/structure.sql";
	$import->pattern_find = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
	$import->pattern_replace = array(TB_PREFIX,'1','en_GB','en_GB');
	$db->query($import->collate());
}

// Step 2: Import essential data if tables exist but data is missing.
// Run when: customers table exists AND (install_data_exists not set or false).
// On first visit to this page, init ran before tables existed so $install_data_exists is never set.
if (checkTableExists(TB_PREFIX."customers") == true) {
	$need_essential = !isset($install_data_exists) || $install_data_exists == false;
	if ($need_essential) {
		$importjson = new importjson();
		$importjson->file = "./databases/json/essential_data.json";
		$importjson->pattern_find = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
		$importjson->pattern_replace = array(TB_PREFIX,'1','en_GB','en_GB');
		$db->query($importjson->collate());
	}
}

