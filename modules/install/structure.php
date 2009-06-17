<?php
$menu = false;


if (checkTableExists() == false)
{
//	echo "SCHEME";
	//SQL import
	$import = new import();
	$import->file = "./databases/MySQL/1-Structure.sql";
	$import->pattern_find = "si_";
	$import->pattern_replace = TB_PREFIX;
	//dbQuery($import->collate());
	$db->query($import->collate());
}

