<?php
checkLogin();

$name = trim($_POST['name'] ?? '');
if ($name === '') {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Name is required']);
    exit;
}

$_POST['name']            = $name;
$_POST['attention']       = $_POST['attention'] ?? '';
$_POST['email']           = $_POST['email']     ?? '';
$_POST['phone']           = $_POST['phone']     ?? '';
$_POST['department']      = '';
$_POST['street_address']  = '';
$_POST['street_address2'] = '';
$_POST['city']            = '';
$_POST['state']           = '';
$_POST['zip_code']        = '';
$_POST['country']         = '';
$_POST['mobile_phone']    = '';
$_POST['fax']             = '';
$_POST['notes']           = '';
$_POST['custom_field1']   = '';
$_POST['custom_field2']   = '';
$_POST['custom_field3']   = '';
$_POST['custom_field4']   = '';
$_POST['enabled']         = 1;

if (customerNameExists($name)) {
	if (ob_get_length()) {
		ob_clean();
	}
	header('Content-Type: application/json');
	echo json_encode([
		'success' => false,
		'error' => $LANG['duplicate_customer_name'] ?? 'A customer with this name already exists.',
	]);
	exit;
}

$ok    = (bool) insertCustomer();
$newId = $ok ? (int) lastInsertId() : 0;
if ($ok && $newId > 0) {
    invoice_denorm::refreshAllForCustomer($newId);
}

if (ob_get_length()) ob_clean();
header('Content-Type: application/json');
if ($newId > 0) {
    echo json_encode(['success' => true, 'id' => $newId, 'name' => $name]);
} else {
    echo json_encode(['success' => false, 'error' => 'Save failed']);
}
exit;
