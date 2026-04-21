<?php
checkLogin();

$description = trim($_POST['description'] ?? '');
if ($description === '') {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Description is required']);
    exit;
}

$domain_id      = domain_id::get();
$unit_price     = $_POST['unit_price']     ?? '0.00';
$notes          = $_POST['notes']          ?? '';
$raw_tax        = $_POST['default_tax_id'] ?? '';
$default_tax_id = ($raw_tax !== '') ? (int) $raw_tax : null;

if (productDescriptionExists($description)) {
	if (ob_get_length()) {
		ob_clean();
	}
	header('Content-Type: application/json');
	echo json_encode([
		'success' => false,
		'error' => $LANG['duplicate_product_description'] ?? 'A product or service with this description already exists.',
	]);
	exit;
}

$sql = "INSERT INTO " . TB_PREFIX . "products
    (domain_id, description, unit_price, cost, reorder_level,
     custom_field1, custom_field2, custom_field3, custom_field4,
     notes, default_tax_id, enabled, visible, attribute,
     notes_as_description, show_description)
    VALUES
    (:domain_id, :description, :unit_price, 0, NULL,
     NULL, NULL, NULL, NULL,
     :notes, :default_tax_id, 1, 1, '[]',
     NULL, NULL)";

$sth    = dbQuery($sql,
    ':domain_id',      $domain_id,
    ':description',    $description,
    ':unit_price',     $unit_price,
    ':notes',          $notes,
    ':default_tax_id', $default_tax_id
);
$newId  = ($sth && $sth->rowCount() > 0) ? (int) lastInsertId() : 0;

// Wipe any PHP warnings / dbQuery error echoes that may have been buffered,
// then send clean JSON.
if (ob_get_length()) ob_clean();
header('Content-Type: application/json');
if ($newId > 0) {
    echo json_encode(['success' => true, 'id' => $newId, 'description' => $description]);
} else {
    echo json_encode(['success' => false, 'error' => 'Save failed']);
}
exit;
