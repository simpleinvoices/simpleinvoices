<?php
$menu = false;


if (SimpleInvoices_Db::tableExists("billers") == false)
{
//	echo "SCHEME";
	//SQL import
	$import = new import();
	$import->file = $include_dir . "/sys/databases/mysql/structure.sql";
	$import->pattern_find = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
	$import->pattern_replace = array(TB_PREFIX,'1','en_GB','en_GB');
	//dbQuery($import->collate());
	$db->query($import->collate());
}

