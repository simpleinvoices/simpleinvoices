<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_TAX = new SimpleInvoices_Db_Table_Tax();

#table

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("tax_description",$LANG['tax_description']);
jsValidateifNum("tax_percentage",$LANG['tax_percentage']);
jsFormValidationEnd();
jsEnd();



#get the invoice id
$tax_rate_id = $_GET['id'];

$tax = $SI_TAX->getTaxRateById($tax_rate_id);

$types = $types = array(
    '%' => '%',
    '$' => '$'
);

$smarty -> assign("tax",$tax);
$smarty -> assign("types",$types);
$smarty -> assign('pageActive', 'tax_rate');
$subPageActive = $_GET['action'] =="view"  ? "tax_rates_view" : "tax_rates_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#setting');
?>