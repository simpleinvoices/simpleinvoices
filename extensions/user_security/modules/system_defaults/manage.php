<?php
global $smarty;

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

$smarty->assign("defaults"                , getSystemDefaults());
$smarty->assign("defaultBiller"           , Biller::getDefaultBiller());
$smarty->assign("defaultCustomer"         , Customer::getDefaultCustomer());
$smarty->assign("defaultDelete"           , getDefaultDelete());
$smarty->assign("defaultInventory"        , getDefaultInventory());
$smarty->assign("defaultLanguage"         , $lang);
$smarty->assign("defaultLargeDataset"     , getDefaultLargeDataset());
$smarty->assign("defaultLogging"          , getDefaultLogging());
$smarty->assign("defaultPaymentType"      , getDefaultPaymentType());
$smarty->assign("defaultPasswordLower"    , getDefaultPasswordLower());
$smarty->assign("defaultPasswordMinLength", getDefaultPasswordMinLength());
$smarty->assign("defaultPasswordNumber"   , getDefaultPasswordNumber());
$smarty->assign("defaultPasswordSpecial"  , getDefaultPasswordSpecial());
$smarty->assign("defaultPasswordUpper"    , getDefaultPasswordUpper());
$smarty->assign("defaultPreference"       , getDefaultPreference());
$smarty->assign("defaultProductAttributes", getDefaultProductAttributes());
$smarty->assign("defaultTax"              , getDefaultTax());

$smarty->assign('pageActive', 'system_default');
$smarty->assign('active_tab', '#setting');
