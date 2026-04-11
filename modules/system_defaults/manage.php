<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//gets the long language name out of the short name
$lang = getDefaultLanguage();
$languages = getLanguageList();
foreach($languages as $language) {
	if($language->shortname == $lang) {
		$lang = $language->name;
		break;
	}
}


$sysDefaults = getSystemDefaults();
$bladeView -> assign("defaults", $sysDefaults);
$bladeView -> assign("defaultBiller", getDefaultBiller());
$bladeView -> assign("defaultCustomer", getDefaultCustomer());
$bladeView -> assign("defaultTax", getDefaultTax());
$bladeView -> assign("defaultPreference", getDefaultPreference());
$bladeView -> assign("defaultPaymentType", getDefaultPaymentType());
$bladeView -> assign("defaultDelete", getDefaultDelete());
$bladeView -> assign("defaultLogging", getDefaultLogging());
$bladeView -> assign("defaultInventory", getDefaultInventory());
$bladeView -> assign("defaultProductAttributes", getDefaultProductAttributes());
$bladeView -> assign("defaultLargeDataset", getDefaultLargeDataset());
$bladeView -> assign("defaultLanguage", $lang);
$bladeView -> assign("defaultConfirmDeleteLineItem", getDefaultGeneric('confirm_delete_line_item'));

$bladeView -> assign('pageActive', 'system_default');
$bladeView -> assign('active_tab', '#setting');
?>
