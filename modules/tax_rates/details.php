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
$tax_rate_id = (int)$_GET['id'];

$tax = getTaxRate($tax_rate_id);
si_check_record_access($tax);
$types = getTaxTypes();

$bladeView -> assign("tax",$tax);
$bladeView -> assign("types",$types);

$bladeView -> assign('pageActive', 'tax_rate');
$subPageActive = $_GET['action'] =="view"  ? "tax_rates_view" : "tax_rates_edit" ;
$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('active_tab', '#setting');
?>
