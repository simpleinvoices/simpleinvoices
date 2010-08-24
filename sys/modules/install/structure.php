<?php
$menu = false;


if (checkTableExists() == false)
{
//	echo "SCHEME";
	//SQL import
	$import = new import();
	$import->file = "./databases/mysql/structure.sql";
	$import->pattern_find = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
	$import->pattern_replace = array(TB_PREFIX,'1','en_GB','en_GB');
	//dbQuery($import->collate());
	$db->query($import->collate());
}

