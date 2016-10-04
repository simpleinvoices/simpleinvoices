<?php
/*
 * Script: manage.php
 * Biller manage page
 *
 * Authors:
 * Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 2016-01-16 by Rich Rowley to add signature field
 * 2007-07-19
 *
 * License:
 * GPL v2 or above
 *
 * Website:
 * http://www.simpleinvoices.org
 */
// Stop the direct browsing to this file.
// Let index.php handle which files get displayed
checkLogin();

// If not already present, include the signature_field include
// and include/class directories in the path.
$path1 = './extensions/signature_field/include';
$curr_path = get_include_path();
if (!strstr($curr_path, $path1)) {
    $path2 = $path1 . '/class';
    set_include_path(get_include_path() . PATH_SEPARATOR . $path1 . PATH_SEPARATOR . $path2);
}

// Add the signature field to the si_billers table if not present.
SignatureField::addSignatureField();

$number_of_rows = 0;
$sql = "SELECT count(*) AS count FROM " . TB_PREFIX . "biller WHERE domain_id = :domain_id";
if ($sth = dbQuery($sql, ':domain_id', domain_id::get())) {
    $number_of_rows = $sth->fetch(PDO::FETCH_ASSOC);
}
// @formatter:off
$smarty->assign('number_of_rows', $number_of_rows);
$smarty->assign('pageActive'    , 'biller');
$smarty->assign('active_tab'    , '#people');
// @formatter:on
