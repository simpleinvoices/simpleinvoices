<?php

$menu = false;

if ( (checkTableExists("si_customers") == true) AND ($install_data_exists == false) )
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
