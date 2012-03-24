<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//gets the long language name out of the short name
$SI_SYSTEM_DEFAULTS = new SimpleInvoices_SystemDefaults();
$SI_PAYMENT_TYPES = new SimpleInvoices_PaymentTypes();

$lang = $SI_SYSTEM_DEFAULTS->findByName('language');
$languages = getLanguageList($include_dir . 'sys/lang/');
foreach($languages as $language) {
	if($language->shortname == $lang) {
		$lang = $language->name;
		break;
	}
}

// Default delete
$defaults['delete'] = $system_defaults->findByName('delete');
$defaults['delete'] = $defaults['delete']==1?$LANG['enabled']:$LANG['disabled'];

// Default Logging
$defaults['logging'] = $system_defaults->findByName('logging');
$defaults['logging'] = $defaults['logging']==1?$LANG['enabled']:$LANG['disabled'];

// Default inventory
$defaults['inventory'] = $system_defaults->findByName('inventory');
$defaults['inventory'] = $defaults['inventory']==1?$LANG['enabled']:$LANG['disabled'];


$smarty -> assign("defaults", $SI_SYSTEM_DEFAULTS->fetchAll());
$smarty -> assign("defaultBiller", getDefaultBiller());
$smarty -> assign("defaultCustomer", getDefaultCustomer());
$smarty -> assign("defaultTax", getDefaultTax());
$smarty -> assign("defaultPreference", getDefaultPreference());
$smarty -> assign("defaultPaymentType", $SI_PAYMENT_TYPES->getDefault());
$smarty -> assign("defaultDelete", $defaults['delete']);
$smarty -> assign("defaultLogging", $defaults['logging']);
$smarty -> assign("defaultInventory", $defaults['inventory']);
$smarty -> assign("defaultLanguage", $lang);

$smarty -> assign('pageActive', 'system_default');
$smarty -> assign('active_tab', '#setting');
?>