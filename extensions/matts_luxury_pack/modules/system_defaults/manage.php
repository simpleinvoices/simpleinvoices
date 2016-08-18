<?php
/* extensions/product_add_LxWxH_weight/modules/system_defaults/manage.php */

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

/**/
$_defaultNrows = array(5, 10, 15, 20, 25, 30, 35, 50, 100, 500);

$sysdefaults = getSystemDefaults();
function getdefaultNrowsRecord() {
	$domain_id = 1;
	$domain_id = domain_id::get($domain_id);
	$record_sql = "SELECT * FROM `" . TB_PREFIX . "system_defaults`
                   WHERE `name` = 'default_nrows' and `domain_id` = ".$domain_id;
	$sth = dbQuery($record_sql);
	return $sth->fetch();
}
$defaultNrows = getdefaultNrowsRecord();
$defaultNrows_value = $_defaultNrows[$defaultNrows['value']];
/**/
$smarty -> assign("defaults", $sysdefaults);
$smarty -> assign("defaultBiller", getDefaultBiller());
$smarty -> assign("defaultCustomer", getDefaultCustomer());
$smarty -> assign("defaultTax", getDefaultTax());
$smarty -> assign("defaultPreference", getDefaultPreference());
$smarty -> assign("defaultPaymentType", getDefaultPaymentType());
$smarty -> assign("defaultDelete", getDefaultDelete());
$smarty -> assign("defaultLogging", getDefaultLogging());
$smarty -> assign("defaultInventory", getDefaultInventory());
$smarty -> assign("defaultProductAttributes", getDefaultProductAttributes());
/**/
//$smarty -> assign("defaultProductLWHW", getDefaultProductLWHW());
$smarty -> assign("defaultProductLWHW", getDefaultGeneric('product_lwhw'));
/**/
$smarty -> assign("defaultLargeDataset", getDefaultLargeDataset());
/**/
$smarty -> assign("defaultNrows", $defaultNrows_value);
$smarty -> assign("price_list", getDefaultGeneric('price_list'));
$smarty -> assign("use_modal", getDefaultGeneric('use_modal'));
$smarty -> assign("use_ship_to", getDefaultGeneric('use_ship_to'));
$smarty -> assign("use_terms", getDefaultGeneric('use_terms'));
/**/
$smarty -> assign("defaultLanguage", $lang);
$smarty -> assign('pageActive', 'system_default');
$smarty -> assign('active_tab', '#setting');
?>
