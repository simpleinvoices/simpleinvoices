<?php
/*
* Script: invoice.php
* 	invoice page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


$billers = getActiveBillers();
$customers = getActiveCustomers();
$taxes = getActiveTaxes();
$products = getActiveProducts();
$preferences = getActivePreferences();
$defaults = getSystemDefaults();

$inv_hasInvoices = (bool) dbQuery(
    "SELECT COUNT(*) FROM " . TB_PREFIX . "invoices WHERE domain_id = :domain_id",
    ':domain_id', $auth_session->domain_id
)->fetchColumn();
$bladeView->assign('inv_hasInvoices', $inv_hasInvoices);

if ($billers == null OR $customers == null OR $products == null)
{
    $first_run_wizard =true;
    $bladeView -> assign("first_run_wizard",$first_run_wizard);
}

$defaults['biller'] = (isset($_GET['biller'])) ? $_GET['biller'] : $defaults['biller'];
$defaults['customer'] = (isset($_GET['customer'])) ? $_GET['customer'] : $defaults['customer'];
$defaults['preference'] = (isset($_GET['preference'])) ? $_GET['preference'] : $defaults['preference'];

// When this domain has only one biller or customer, pre-select it for new invoices (unless overridden via URL).
if (!isset($_GET['biller']) && is_array($billers) && count($billers) === 1) {
	$defaults['biller'] = $billers[0]['id'];
}
if (!isset($_GET['customer']) && is_array($customers) && count($customers) === 1) {
	$defaults['customer'] = $customers[0]['id'];
}
$defaultTax = getDefaultTax();
$defaultPreference = getDefaultPreference();
$nextInvoiceId = '';
if ($defaultPreference && !empty($defaultPreference['index_group'])) {
    $nextInvoiceId = (string) index::next('invoice', $defaultPreference['index_group'], $auth_session->domain_id);
}
$bladeView->assign('nextInvoiceId', $nextInvoiceId);
$bladeView->assign('showInvoiceIdPreview', true);

// Template expects an array of row indices (0, 1, 2, ...); DB stores the count (e.g. 5)
if (!empty($_GET['line_items'])) {
	$num_line_items = max(1, (int) $_GET['line_items']);
} else {
	$num_line_items = max(1, (int) ($defaults['line_items'] ?? 5));
}
$dynamic_line_items = range(0, $num_line_items - 1);

for($i=1;$i<=4;$i++) {
	$show_custom_field[$i] = show_custom_field("invoice_cf$i",'',"write",'',"details_screen",'','','');
}

global $db_server;
$matrix_domain_id = domain_id::get();
if ($db_server === 'mysql') {
    $sql = "SELECT CONCAT(a.id, '-', v.id) AS id
                 , CONCAT(a.name, '-', v.value) AS display
            FROM ".TB_PREFIX."products_attributes a
                LEFT JOIN ".TB_PREFIX."products_values v
                    ON (a.id = v.attribute_id AND a.domain_id = v.domain_id)
            WHERE a.domain_id = :domain_id";
} else {
    $sql = "SELECT (CAST(a.id AS TEXT) || '-' || CAST(v.id AS TEXT)) AS id
                 , (a.name || '-' || v.value) AS display
            FROM ".TB_PREFIX."products_attributes a
                LEFT JOIN ".TB_PREFIX."products_values v
                    ON (a.id = v.attribute_id AND a.domain_id = v.domain_id)
            WHERE a.domain_id = :domain_id";
}
$sth = dbQuery($sql, ':domain_id', $matrix_domain_id);
$matrix = $sth->fetchAll();
$bladeView -> assign("matrix", $matrix);

$bladeView -> assign("billers",$billers);
$bladeView -> assign("customers",$customers);
$bladeView -> assign("taxes",$taxes);
$bladeView -> assign("products",$products);
$bladeView -> assign("preferences",$preferences);
$bladeView -> assign("paymentTerms", getPaymentTerms());
$bladeView -> assign("dynamic_line_items",$dynamic_line_items);
$bladeView -> assign("show_custom_field",$show_custom_field);

$bladeView -> assign("defaultCustomerID", $defaults['customer'] ?? '');
$bladeView -> assign("defaults",$defaults);

$bladeView -> assign('active_tab', '#money');

?>
