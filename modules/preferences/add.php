<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['p_description'] != "" ) {
	include("./modules/preferences/save.php");
}
$bladeView -> assign('save',$save);

$defaults = getSystemDefaults();
$preferences = getActivePreferences();
$paymentTerms = getPaymentTerms();
require_once __DIR__ . '/../../include/class/LocaleHelper.php';
require_once __DIR__ . '/../../include/class/CurrencySignHelper.php';
require_once __DIR__ . '/../../include/class/siCurrencies.php';
$localelist = LocaleHelper::getLocaleList();
$currencies = siCurrencies::getForDomain();

$bladeView->assign('preferences',$preferences);
$bladeView->assign('paymentTerms',$paymentTerms);
$bladeView->assign('defaults',$defaults);
$bladeView->assign('localelist',$localelist);
$bladeView->assign('currencies', $currencies);
$bladeView->assign('defaultSystemLocale', getDefaultLanguage());

$bladeView -> assign('pageActive', 'preference');
$bladeView -> assign('subPageActive', 'preferences_add');
$bladeView -> assign('active_tab', '#setting');
?>
