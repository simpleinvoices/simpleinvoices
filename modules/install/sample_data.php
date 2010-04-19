<?php

$menu = false;


//if (checkTableExists() == false)
//{
//	echo "SCHEME";
	//SQL import
	//JSON import
	$samplejson = new importjson();
	$samplejson->file = "./databases/json/sample_data.json";
	//$samplejson->debug = true;
	$samplejson->pattern_find = array('si_','DOMAIN-ID');
	$samplejson->pattern_replace = array(TB_PREFIX,'1');
	if($db->query($samplejson->collate()) )
	{
		$saved=true;
	} else {
		$saved=false;
	}
//}

$smarty -> assign("saved",$saved);

