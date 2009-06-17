<?php

$menu = false;


//if (checkTableExists() == false)
//{
//	echo "SCHEME";
	//SQL import
	//JSON import
	$importjson = new importjson();
	$importjson->file = "./databases/JSON/SampleData.json";
	$importjson->debug = true;
	$importjson->pattern_find = "si_";
	$importjson->pattern_replace = TB_PREFIX;
	//dbQuery($importjson->collate());
	$db->query($importjson->collate());
//}

