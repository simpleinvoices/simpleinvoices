<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$pageActive = "options";
$smarty->assign('pageActive', $pageActive);

$smarty -> assign("defaults", getSystemDefaults());
$smarty -> assign("defaultBiller", getDefaultBiller());
$smarty -> assign("defaultCustomer", getDefaultCustomer());
$smarty -> assign("defaultTax", getDefaultTax());
$smarty -> assign("defaultPreference", getDefaultPreference());
$smarty -> assign("defaultPaymentType", getDefaultPaymentType());
$smarty -> assign("defaultDelete", getDefaultDelete());
$smarty -> assign("defaultLogging", getDefaultLogging());
$smarty -> assign("defaultLanguage", getDefaultLanguage());

?>
