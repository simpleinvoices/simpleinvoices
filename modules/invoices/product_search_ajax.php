<?php
/**
 * AJAX endpoint: search products for Tom Select autocomplete
 * URL: index.php?module=invoices&view=product_search_ajax&q=<term>
 * Returns JSON array of {id, description}
 */

$q = trim($_GET['q'] ?? '');

if ($q === '') {
	$sql = "SELECT id, description FROM " . TB_PREFIX . "products "
	     . "WHERE enabled AND domain_id = :domain_id "
	     . "ORDER BY description LIMIT 10000";
	$states = dbQuery($sql, ':domain_id', $auth_session->domain_id);
} else {
	$sql = "SELECT id, description FROM " . TB_PREFIX . "products "
	     . "WHERE enabled AND domain_id = :domain_id AND description LIKE :q "
	     . "ORDER BY description LIMIT 10000";
	$states = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':q', '%' . $q . '%');
}

$output = [];
while ($row = $states->fetch()) {
	$output[] = ['id' => $row['id'], 'description' => $row['description']];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($output);
exit;
