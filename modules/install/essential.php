<?php

$menu = false;

if ( (checkTableExists("si_customers") == true) AND ($install_data_exists == false) )
{
//	echo "ESSENTIAL";
	//JSON import
	$importjson = new importjson();
	$importjson->file = "./databases/json/essential_data.json";
	//$importjson->debug = true;
	$importjson->pattern_find = array('si_','DOMAIN-ID');
	$importjson->pattern_replace = array(TB_PREFIX,'1');
	//dbQuery($importjson->collate());
	$db->query($importjson->collate());
}
