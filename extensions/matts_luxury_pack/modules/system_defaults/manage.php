<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/system_defaults/manage.php
 * 	manage System Preferences
 *
 * Authors:
 *	 yumatechnical@gmail.com
 *
 * Last edited:
 * 	 2016-09-22
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//gets the long language name out of the short name
$lang = getDefaultLanguage();
$languages = getLanguageList();
foreach ($languages as $language) {
	if ($language->shortname == $lang) {
		$lang = $language->name;
		break;
	}
}
global $pagerows;//Matt

$defaults = getSystemDefaults();
$smarty -> assign("defaults", $defaults);
$smarty -> assign("defaultLanguage", $lang);
$smarty -> assign("defaultBiller", getDefaultBiller());
$smarty -> assign("defaultCustomer", getDefaultCustomer());
$smarty -> assign("defaultTax", getDefaultTax());
$smarty -> assign("defaultPreference", getDefaultPreference());
$smarty -> assign("defaultPaymentType", getDefaultPaymentType());
$smarty -> assign("defaultDelete", getDefaultDelete());
$smarty -> assign("defaultLogging", getDefaultLogging());
$smarty -> assign("defaultInventory", getDefaultInventory());
$smarty -> assign("defaultProductAttributes", getDefaultProductAttributes());
$smarty -> assign("defaultLargeDataset", getDefaultLargeDataset());
$smarty -> assign("defaultProductLWHW", getDefaultGeneric('product_lwhw'));//Matt
//$smarty -> assign("defaultNrows", getDefaultGeneric('default_nrows'));//$defaultNrows_value);//Matt
$smarty -> assign("price_list", getDefaultGeneric('price_list'));//Matt
$smarty -> assign("use_modal", getDefaultGeneric('use_modal'));//Matt
$smarty -> assign("use_ship_to", getDefaultGeneric('use_ship_to'));//Matt
$smarty -> assign("use_terms", getDefaultGeneric('use_terms'));//Matt
$smarty -> assign('pageActive', 'system_default');
$smarty -> assign('active_tab', '#setting');
//echo print_r($defaults,true) . getDefaultTax();
