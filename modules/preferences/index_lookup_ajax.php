<?php
/**
 * AJAX endpoint: look up next invoice number for a given index_group
 * URL: index.php?module=preferences&view=index_lookup_ajax&index_group=<pref_id>
 * Returns JSON: {next, max_existing}
 */

$index_group = (int) ($_GET['index_group'] ?? 0);

if ($index_group <= 0) {
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(['next' => 1, 'max_existing' => 0]);
	exit;
}

$domain_id = $auth_session->domain_id;
$next = index::next('invoice', $index_group, $domain_id);

$sql = "SELECT MAX(index_id) AS max_idx
	FROM " . TB_PREFIX . "invoices
	WHERE domain_id = :domain_id AND preference_id IN (
		SELECT pref_id FROM " . TB_PREFIX . "preferences
		WHERE index_group = :index_group AND domain_id = :domain_id2
	)";
$sth = dbQuery($sql,
	':domain_id', $domain_id,
	':index_group', $index_group,
	':domain_id2', $domain_id);
$row = $sth->fetch();
$max_existing = (int) ($row['max_idx'] ?? 0);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['next' => $next, 'max_existing' => $max_existing]);
exit;
