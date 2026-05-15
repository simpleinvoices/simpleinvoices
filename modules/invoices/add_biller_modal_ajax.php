<?php
checkLogin();

$name = trim($_POST['name'] ?? '');
if ($name === '') {
	if (ob_get_length()) {
		ob_clean();
	}
	header('Content-Type: application/json');
	echo json_encode(['success' => false, 'error' => 'Name is required']);
	exit;
}

if (billerNameExists($name)) {
	if (ob_get_length()) {
		ob_clean();
	}
	header('Content-Type: application/json');
	echo json_encode([
		'success' => false,
		'error' => $LANG['duplicate_biller_name'] ?? 'A biller with this name already exists.',
	]);
	exit;
}

$_POST['name']            = $name;
$_POST['email']           = trim($_POST['email'] ?? '');
$_POST['phone']           = trim($_POST['phone'] ?? '');
$_POST['street_address']  = '';
$_POST['street_address2'] = '';
$_POST['city']            = '';
$_POST['state']           = '';
$_POST['zip_code']        = '';
$_POST['country']         = '';
$_POST['mobile_phone']    = '';
$_POST['fax']              = '';
$_POST['logo']             = '';
$_POST['footer']           = '';
$_POST['paymentsgateway_api_id'] = '';
$_POST['notes']            = '';
$_POST['custom_field1']    = '';
$_POST['custom_field2']    = '';
$_POST['custom_field3']    = '';
$_POST['custom_field4']    = '';
$_POST['enabled']          = 1;
$_POST['stripe_secret_key'] = '';
$_POST['stripe_webhook_secret'] = '';
$_POST['stripe_test_mode'] = 1;
$_POST['paypal_client_id'] = '';
$_POST['paypal_client_secret'] = '';
$_POST['paypal_test_mode'] = 1;
$_POST['mollie_api_key'] = '';
$_POST['authorizenet_login_id'] = '';
$_POST['authorizenet_transaction_key'] = '';
$_POST['authorizenet_signature_key'] = '';
$_POST['authorizenet_test_mode'] = 1;
$_POST['eway_api_key'] = '';
$_POST['eway_api_password'] = '';
$_POST['eway_test_mode'] = 1;
$_POST['kofi_username'] = '';
$_POST['coinbase_api_key'] = '';
$_POST['coinbase_webhook_secret'] = '';
$_POST['adyen_api_key'] = '';
$_POST['adyen_merchant_account'] = '';
$_POST['adyen_hmac_key'] = '';
$_POST['adyen_live_prefix'] = '';
$_POST['adyen_test_mode'] = 1;

$ok    = (bool) insertBiller();
$newId = $ok ? (int) lastInsertId() : 0;
if ($ok && $newId > 0) {
	invoice_denorm::refreshAllForBiller($newId);
}

if (ob_get_length()) {
	ob_clean();
}
header('Content-Type: application/json');
if ($newId > 0) {
	$stmt = dbQuery('SELECT biller_invoice_prefix FROM ' . TB_PREFIX . 'biller WHERE id = :id AND domain_id = :domain_id',
		':id', $newId, ':domain_id', $auth_session->domain_id);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	echo json_encode(['success' => true, 'id' => $newId, 'name' => $name, 'biller_invoice_prefix' => $row['biller_invoice_prefix'] ?? '']);
} else {
	echo json_encode(['success' => false, 'error' => 'Save failed']);
}
exit;
