<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Display language as "Name (shortname)" on the manage row
$langCode = getDefaultLanguage();
$lang     = $langCode;
$languages = getLanguageList();
foreach ($languages as $language) {
	if ($language->shortname == $langCode) {
		$lang = $language->name . ' (' . $language->shortname . ')';
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
