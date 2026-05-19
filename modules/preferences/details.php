<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

//if valid then do save
if ($_POST['p_description'] != "" ) {
	include("./modules/preferences/save.php");
}

#get the invoice id
$preference_id = (int)$_GET['id'];

$preference = getPreference($preference_id);
si_check_record_access($preference);
$index_group = getPreference($preference['index_group']);

$paymentTerms = getPaymentTerms();
$prefPaymentTermLabel = '';
if (!empty($preference['payment_term_id'])) {
	$pt = getPaymentTerm($preference['payment_term_id'], domain_id::get());
	if ($pt) {
		$prefPaymentTermLabel = $pt['term_label'];
	}
}

$preferences = getActivePreferences();
$defaults = getSystemDefaults();
$status = array(array('id'=>'0','status'=>$LANG['draft']), array('id'=>'1','status'=>$LANG['real']));
require_once __DIR__ . '/../../include/class/LocaleHelper.php';
require_once __DIR__ . '/../../include/class/CurrencySignHelper.php';
require_once __DIR__ . '/../../include/class/siCurrencies.php';
$localelist = LocaleHelper::getLocaleList();
$currencies = siCurrencies::getForDomain();
$languageList = getLanguageList();

// Next invoice number for current preference's index_group
$next_invoice_number = index::next('invoice', $preference['index_group'], $auth_session->domain_id);

// Max existing index_id (validation floor)
$sql_max = "SELECT MAX(index_id) AS max_idx
	FROM " . TB_PREFIX . "invoices
	WHERE domain_id = :domain_id AND preference_id IN (
		SELECT pref_id FROM " . TB_PREFIX . "preferences
		WHERE index_group = :index_group AND domain_id = :domain_id2
	)";
$sth = dbQuery($sql_max,
	':domain_id', $auth_session->domain_id,
	':index_group', $preference['index_group'],
	':domain_id2', $auth_session->domain_id);
$r_max = $sth->fetch();
$max_existing_index_id = (int) ($r_max['max_idx'] ?? 0);

// Build per-pref_id → next_number map for JS dynamic lookup
$index_next_map = [];
if (is_array($preferences)) {
	foreach ($preferences as $p) {
		$gid = (int) ($p['index_group'] ?? 0);
		if ($gid > 0 && !isset($index_next_map[$gid])) {
			$index_next_map[$gid] = index::next('invoice', $gid, $auth_session->domain_id);
		}
	}
}

$saved_flag = isset($_GET['saved']) && $_GET['saved'] === '1';
$start_err_flag = isset($_GET['start_err']) && $_GET['start_err'] === '1';
$starting_number_error = '';
if ($start_err_flag && isset($_SESSION['si_starting_number_error'])) {
	$starting_number_error = (string) $_SESSION['si_starting_number_error'];
	unset($_SESSION['si_starting_number_error']);
}

$bladeView->assign('preference',$preference);
$bladeView->assign('paymentTerms',$paymentTerms);
$bladeView->assign('prefPaymentTermLabel',$prefPaymentTermLabel);
$bladeView->assign('defaults',$defaults);
$bladeView->assign('index_group',$index_group);
$bladeView->assign('preferences',$preferences);
$bladeView->assign('status',$status);
$bladeView->assign('localelist',$localelist);
$bladeView->assign('languageList', is_array($languageList) ? $languageList : []);
$bladeView->assign('currencies', $currencies);
$bladeView->assign('next_invoice_number', $next_invoice_number);
$bladeView->assign('max_existing_index_id', $max_existing_index_id);
$bladeView->assign('index_next_map', json_encode($index_next_map));
$bladeView->assign('saved_flag', $saved_flag);
$bladeView->assign('starting_number_error', $starting_number_error);

$bladeView -> assign('pageActive', 'preference');
$subPageActive = $_GET['action'] =="view"  ? "preferences_view" : "preferences_edit" ;
$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('active_tab', '#setting');
?>
