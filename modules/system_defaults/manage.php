<?php
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// gets the long language name out of the short name
$lang = getDefaultLanguage();
$languages = getLanguageList();
foreach ($languages as $language) {
    if ($language->shortname == $lang) {
        $lang = $language->name;
        break;
    }
}

// @formatter:off
$smarty->assign("defaults"                , getSystemDefaults());
$smarty->assign("defaultBiller"           , Biller::getDefaultBiller());
$smarty->assign("defaultCustomer"         , Customer::getDefaultCustomer());
$smarty->assign("defaultTax"              , Taxes::getDefaultTax());
$smarty->assign("defaultPreference"       , Preferences::getDefaultPreference());
$smarty->assign("defaultPaymentType"      , PaymentType::getDefaultPaymentType());
$smarty->assign("defaultDelete"           , getDefaultDelete());
$smarty->assign("defaultLogging"          , getDefaultLogging());
$smarty->assign("defaultInventory"        , getDefaultInventory());
$smarty->assign("defaultProductAttributes", getDefaultProductAttributes());
$smarty->assign("defaultLargeDataset"     , getDefaultLargeDataset());
$smarty->assign("defaultLanguage"         , $lang);

$smarty->assign('pageActive', 'system_default');
$smarty->assign('active_tab', '#setting');
// @formatter:on

