<?php

$menu = false;


//if (checkTableExists() == false)
//{
//	echo "SCHEME";
	//SQL import
	//JSON import
	$samplejson = new importjson();
	$samplejson->file = "./databases/JSON/SampleData.json";
	//$samplejson->debug = true;
	$samplejson->pattern_find = "si_";
	$samplejson->pattern_replace = TB_PREFIX;
	//dbQuery($importjson->collate());
	if($db->query($samplejson->collate()) )
	{
		$saved=true;
	} else {
		$saved=false;
	}
//}

$smarty -> assign("saved",$saved);

