<?php

header("Content-type: text/xml");

$dir  = $_REQUEST['sortorder'] ?? 'DESC';
$sort = $_REQUEST['sortname'] ?? 'ap.id';
$rp   = max(10, (int)($_REQUEST['rp'] ?? 10));
$page = max(1, (int)($_REQUEST['page'] ?? 1));

// ── Payment grid XML cache ───────────────────────────────────────────────────
$_pmt_cache_dir  = dirname(dirname(__DIR__)) . '/tmp/cache/payments_xml';
$_pmt_cache_key  = md5(serialize([
    $auth_session->domain_id,
    $auth_session->role_name ?? '',
    $auth_session->user_id   ?? '',
    $dir, $sort, $rp, $page,
    $_REQUEST['query'] ?? '',
    $_REQUEST['qtype'] ?? '',
    $_GET['id']  ?? '',
    $_GET['c_id'] ?? '',
]));
$_pmt_cache_file = $_pmt_cache_dir . '/pmt_' . (int) $auth_session->domain_id . '_' . $_pmt_cache_key . '.xml';
$_pmt_cache_ttl  = 300; // 5 minutes; also cleared immediately on any invoice/payment write

if (file_exists($_pmt_cache_file) && (time() - filemtime($_pmt_cache_file)) < $_pmt_cache_ttl) {
    echo file_get_contents($_pmt_cache_file);
    exit;
}
// ── end cache check ──────────────────────────────────────────────────────────

/**
 * @param  string  $type  '' for grid rows, 'count' for SQL COUNT(*) (no ORDER/LIMIT)
 */
function sql($type = '', $dir, $sort, $rp, $page)
{
	global $db_server;
	global $auth_session;

	$search_map = [
		'ap.id'   => 'ap.id',
		'b.name'  => 'ap.denorm_biller_name',
		'c.name'  => 'ap.denorm_customer_name',
	];

	if (! preg_match('/^(asc|desc)$/iD', $dir)) {
		$dir = 'DESC';
	}

	$start = (($page - 1) * $rp);
	$limit = "LIMIT $rp OFFSET $start";

	$where = '';
	$query = $_REQUEST['query'] ?? null;
	$qtype = $_REQUEST['qtype'] ?? null;
	if (! (empty($qtype) || empty($query))) {
		if (isset($search_map[$qtype])) {
			$where = ' AND ' . $search_map[$qtype] . ' LIKE :query ';
		} else {
			$qtype = null;
			$query = null;
		}
	}

	// Map grid column names to safe SQL expressions (denormalised list columns on si_payment)
	$sortMap = [
		'id'        => 'ap.id',
		'ap.id'     => 'ap.id',
		'ac_inv_id' => 'ap.ac_inv_id',
		'customer'  => 'ap.denorm_customer_name',
		'biller'    => 'ap.denorm_biller_name',
		'ac_amount' => 'ap.ac_amount',
		'date'      => 'ap.ac_date',
	];
	$sort = $sortMap[$sort] ?? 'ap.id';

	$date_expr = ($db_server === 'sqlite')
		? "strftime('%Y-%m-%d', ap.ac_date)"
		: ($db_server === 'pgsql'
			? "TO_CHAR(ap.ac_date, 'YYYY-MM-DD')"
			: "DATE_FORMAT(ap.ac_date,'%Y-%m-%d')");
	$desc_expr = ($db_server === 'mysql')
		? "COALESCE(pt.pt_description, '')"
		: "COALESCE(pt.pt_description, '')";

	$from_join = '
			FROM ' . TB_PREFIX . 'payment ap
			LEFT JOIN ' . TB_PREFIX . 'payment_types pt ON (pt.pt_id = ap.ac_payment_type AND pt.domain_id = ap.domain_id)
			WHERE ap.domain_id = :domain_id ';

	// Customer-scoped payment list needs invoice row for customer_id match
	$from_join_iv = '
			FROM ' . TB_PREFIX . 'payment ap
			INNER JOIN ' . TB_PREFIX . 'invoices iv ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
			LEFT JOIN ' . TB_PREFIX . 'payment_types pt ON (pt.pt_id = ap.ac_payment_type AND pt.domain_id = ap.domain_id)
			WHERE ap.domain_id = :domain_id ';

	$domain_id = $auth_session->domain_id;

	if ($type === 'count') {
		$count_head = 'SELECT COUNT(*) AS cnt ';
		if (! empty($_GET['id'])) {
			$id       = $_GET['id'];
			$countSql = $count_head . $from_join . ' AND ap.ac_inv_id = :invoice_id ' . $where;
			if (empty($query)) {
				return dbQuery($countSql, ':domain_id', $domain_id, ':invoice_id', $id);
			}

			return dbQuery($countSql, ':domain_id', $domain_id, ':invoice_id', $id, ':query', "%$query%");
		}
		if (! empty($_GET['c_id'])) {
			$id       = $_GET['c_id'];
			$countSql = $count_head . $from_join_iv . ' AND iv.customer_id = :cid ' . $where;
			if (empty($query)) {
				return dbQuery($countSql, ':domain_id', $domain_id, ':cid', $id);
			}

			return dbQuery($countSql, ':domain_id', $domain_id, ':cid', $id, ':query', "%$query%");
		}
		$countSql = $count_head . $from_join . $where;
		if (empty($query)) {
			return dbQuery($countSql, ':domain_id', $domain_id);
		}

		return dbQuery($countSql, ':domain_id', $domain_id, ':query', "%$query%");
	}

	$select = "SELECT ap.*
				, ap.denorm_customer_name AS cname
				, ap.denorm_invoice_index_name AS index_name
				, ap.denorm_biller_name AS bname
				, $desc_expr AS description
				, ap.ac_notes AS notes
				, $date_expr AS date ";

	if (! empty($_GET['id'])) {
		$id  = $_GET['id'];
		$sql = $select . $from_join . "
			AND ap.ac_inv_id = :invoice_id
			$where
			ORDER BY $sort $dir
			$limit";
		if (empty($query)) {
			return dbQuery($sql, ':domain_id', $domain_id, ':invoice_id', $id);
		}

		return dbQuery($sql, ':domain_id', $domain_id, ':invoice_id', $id, ':query', "%$query%");
	}
	if (! empty($_GET['c_id'])) {
		$id  = $_GET['c_id'];
		$sql = $select . $from_join_iv . "
			AND iv.customer_id = :cid
			$where
			ORDER BY $sort $dir
			$limit";
		if (empty($query)) {
			return dbQuery($sql, ':domain_id', $domain_id, ':cid', $id);
		}

		return dbQuery($sql, ':domain_id', $domain_id, ':cid', $id, ':query', "%$query%");
	}

	$sql = $select . $from_join . "
			$where
			ORDER BY $sort $dir
			$limit";
	if (empty($query)) {
		return dbQuery($sql, ':domain_id', $domain_id);
	}

	return dbQuery($sql, ':domain_id', $domain_id, ':query', "%$query%");
}

$sth              = sql('', $dir, $sort, $rp, $page);
$sth_count_rows   = sql('count', $dir, $sort, $rp, $page);
$payments         = $sth->fetchAll(PDO::FETCH_ASSOC);
$count_row = $sth_count_rows->fetch(PDO::FETCH_ASSOC);
$count     = (int) ($count_row['cnt'] ?? 0);

$xml  = "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($payments as $row) {
	$label_esc = htmlspecialchars($row['index_name'] ?? (string)$row['id']);
	$action    = '<div class="dropdown">';
	$action .= '<a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
	$action .= '<div class="dropdown-menu dropdown-menu-end">';
	$action .= '<a class="dropdown-item" href="index.php?module=payments&amp;view=details&amp;id='.$row['id'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$label_esc.'</a>';
	$pay_pdf_url = htmlspecialchars('index.php?module=export&view=payment&id='.$row['id'].'&format=pdf', ENT_QUOTES);
	$action .= '<a class="dropdown-item si-preview-link" href="index.php?module=payments&amp;view=print&amp;id='.$row['id'].'" data-preview-title="'.htmlspecialchars($LANG['print_preview_tooltip'].' '.$label_esc, ENT_QUOTES).'" data-preview-pdf="'.$pay_pdf_url.'"><i class="ti ti-printer me-2"></i>'.$LANG['print_preview_tooltip'].' '.$label_esc.'</a>';
	$action .= '</div></div>';
	$xml .= "<row id='".$row['id']."'>";
	$xml .= "<cell><![CDATA[".$action."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['index_name']."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['cname']."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['bname']."]]></cell>";
	$amt_cell = htmlspecialchars(CurrencySignHelper::format($row['ac_amount'], $row['denorm_currency_sign'] ?? '', $row['denorm_currency_position'] ?? '', $row['denorm_currency_code'] ?? ''), ENT_QUOTES, 'UTF-8');
	$xml .= "<cell><![CDATA[".$amt_cell."]]></cell>";
	$xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";
	$xml .= "</row>";
}
$xml .= "</rows>";

// ── Write xml cache ───────────────────────────────────────────────────────────
if (! is_dir($_pmt_cache_dir)) {
    @mkdir($_pmt_cache_dir, 0755, true);
}
@file_put_contents($_pmt_cache_file, $xml, LOCK_EX);
// ── end cache write ───────────────────────────────────────────────────────────

echo $xml;
