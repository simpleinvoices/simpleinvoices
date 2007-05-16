<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$smarty -> assign("defaults", getSystemDefaults());
$smarty -> assign("defaultBiller", getDefaultBiller());
$smarty -> assign("defaultCustomer", getDefaultCustomer());
$smarty -> assign("defaultTax", getDefaultTax());
$smarty -> assign("defaultPreference", getDefaultPreference());
$smarty -> assign("defaultPaymentType", getDefaultPaymentType());

?>
