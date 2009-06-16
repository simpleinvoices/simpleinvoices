<?php
$menu = false;

$db = db::getInstance();

if (checkTableExists() == false)
{
//	echo "SCHEME";
	//SQL import
	$import = new import();
	$import->file = "./databases/MySQL/1-Structure.sql";
	$import->pattern_find = "si_";
	$import->pattern_replace = TB_PREFIX;
	//dbQuery($import->collate());
	$db = new db();
	$db->query($import->collate());
}
if (checkTableExists("si_customers") == true)
{
//	echo "ESSENTIAL";
	//JSON import
	$importjson = new importjson();
	$importjson->file = "./databases/JSON/EssentialData.json";
	//$importjson->debug = true;
	$importjson->pattern_find = "si_";
	$importjson->pattern_replace = TB_PREFIX;
	//dbQuery($importjson->collate());
	$db->query($importjson->collate());
}
