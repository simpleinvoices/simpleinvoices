<?php

checkLogin();

jsBegin();
jsFormValidationBegin('frmpost');
jsValidateRequired('term_code', $LANG['payment_term_code'] ?? 'Code');
jsValidateRequired('term_label', $LANG['payment_term_label'] ?? 'Label');
jsFormValidationEnd();
jsEnd();

$termId = (int) ($_GET['id'] ?? 0);
$domain_id = domain_id::get();
$term = getPaymentTerm($termId, $domain_id);
si_check_record_access($term);

$action = $_GET['action'] ?? 'view';
if (!in_array($action, ['view', 'edit'], true)) {
	$action = 'view';
}

$bladeView->assign('calcKinds', getPaymentTermCalcKindCodes());
$bladeView->assign('term', $term);
$bladeView->assign('detailsAction', $action);
$bladeView->assign('pageActive', 'payment_term');
$subPageActive = $action === 'view' ? 'payment_terms_view' : 'payment_terms_edit';
$bladeView->assign('subPageActive', $subPageActive);
$bladeView->assign('active_tab', '#setting');
