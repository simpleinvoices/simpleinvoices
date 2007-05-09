<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#table

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("tax_description",$LANG['tax_description']);
jsValidateifNum("tax_percentage",$LANG['tax_percentage']);
jsFormValidationEnd();
jsEnd();



#get the invoice id
$tax_rate_id = $_GET['submit'];

$tax = getTaxRate($tax_rate_id);

$smarty -> assign("tax",$tax);
?>
