<?php

$product_group = array();
$product_group[0]['name'] = "Labor";
//$product_group[0]['markup'] = "29%";
$product_group[1]['name']= "Equipment";
//$product_group[1]['markup'] = "21%";
$product_group[2]['name'] = "Materials";
//$product_group[2]['markup'] = "21%";
$product_group[3]['name'] = "Subcontractors";
//$product_group[3]['markup'] = "12%";

$smarty->assign('product_group',$product_group);
?>
