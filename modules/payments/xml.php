<?php

header("Content-type: text/xml");

$dir  = $_REQUEST['sortorder'] ?? 'DESC';
$sort = $_REQUEST['sortname'] ?? 'ap.id';
$rp   = max(10, (int)($_REQUEST['rp'] ?? 10));
$page = max(1, (int)($_REQUEST['page'] ?? 1));

/**
 * @param  string  $type  '' for grid rows, 'count' for SQL COUNT(*) (no ORDER/LIMIT)
 */
function sql($type = '', $dir, $sort, $rp, $page)
{
	global $db_server;
	global $auth_session;

	$valid_search_fields = ['ap.id', 'b.name', 'c.name'];

	if (! preg_match('/^(asc|desc)$/iD', $dir)) {
		$dir = 'DESC';
	}

	$start = (($page - 1) * $rp);
	$limit = "LIMIT $rp OFFSET $start";

	$where = '';
	$query = $_REQUEST['query'] ?? null;
	$qtype = $_REQUEST['qtype'] ?? null;
	if (! (empty($qtype) || empty($query))) {
		if (in_array($qtype, $valid_search_fields)) {
			$where = " AND $qtype LIKE :query ";
		} else {
			$qtype = null;
			$query = null;
		}
	}

	// Map grid column names to safe SQL expressions
	$sortMap = [
		'id'        => 'ap.id',
		'ap.id'     => 'ap.id',
		'ac_inv_id' => 'ap.ac_inv_id',
		'customer'  => 'c.name',
		'biller'    => 'b.name',
		'ac_amount' => 'ap.ac_amount',
		'date'      => 'ap.ac_date',
	];
	$sort = $sortMap[$sort] ?? 'ap.id';

	$index_name_expr = ($db_server === 'mysql')
		? "CONCAT(pr.pref_inv_wording, ' ', iv.index_id)"
		: "(pr.pref_inv_wording || ' ' || CAST(iv.index_id AS TEXT))";
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
			INNER JOIN ' . TB_PREFIX . 'invoices iv ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
			INNER JOIN ' . TB_PREFIX . 'customers c ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
			INNER JOIN ' . TB_PREFIX . 'biller b ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
			INNER JOIN ' . TB_PREFIX . 'preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = ap.domain_id)
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
			$countSql = $count_head . $from_join . ' AND c.id = :cid ' . $where;
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
				, c.name as cname
				, $index_name_expr as index_name
				, b.name as bname
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
		$sql = $select . $from_join . "
			AND c.id = :cid
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
	$xml .= "<cell><![CDATA[".siLocal::number($row['ac_amount'])."]]></cell>";
	$xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";
	$xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;
