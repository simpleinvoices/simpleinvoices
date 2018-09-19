<?php

/*
 * Script: add.php
 * Billers add page
 *
 * Authors:
 * Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 2007-07-19
 *
 * License:
 * GPL v2 or above
 *
 * Website:
 * https://simpleinvoices.group/doku.php?id=si_wiki:menu */
global $smarty;

checkLogin();

$files = getLogoList();
$smarty->assign("files", $files);

$domain_id = domain_id::get();
$smarty->assign("domain_id", $domain_id);

// Only load labels if they are defined. Screen will only
// show what is loaded.
$customFieldLabel = getCustomFieldLabels("", true);

if (!empty($_POST['name'])) {
    include ("extensions/signature_field/modules/billers/save.php");
}
// @formatter:off
$smarty->assign('customFieldLabel', $customFieldLabel);
$smarty->assign('pageActive'      , 'biller');
$smarty->assign('subPageActive'   , 'biller_add');
$smarty->assign('active_tab'      , '#people');
// @formatter:on
