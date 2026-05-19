<?php

header("Content-type: text/xml");

checkLogin();

global $auth_session;
global $LANG;

$start = (int) ($_REQUEST['start'] ?? 0);
$dir = ($_REQUEST['sortorder'] ?? 'ASC');
$sort = ($_REQUEST['sortname'] ?? 'currency_code');
$rp = (int) ($_REQUEST['rp'] ?? 25);
$page = (int) ($_REQUEST['page'] ?? 1);

function sql($type = '', $dir, $sort, $rp, $page)
{
	global $auth_session;

	$valid_search_fields = ['id', 'currency_code', 'currency_sign'];

	if (!preg_match('/^(asc|desc)$/i', $dir)) {
		$dir = 'ASC';
	}

	$where = '';
	$query = $_REQUEST['query'] ?? null;
	$qtype = $_REQUEST['qtype'] ?? null;
	if (!empty($qtype) && !empty($query)) {
		if (in_array($qtype, $valid_search_fields)) {
			$where = " AND $qtype LIKE :query ";
		} else {
			$qtype = null;
			$query = null;
		}
	}

	$validFields = ['id', 'currency_code', 'currency_sign', 'currency_position'];
	if (!in_array($sort, $validFields)) {
		$sort = 'currency_code';
	}

	$start = (($page - 1) * $rp);
	$limit = "LIMIT $rp OFFSET $start";

	if ($type === 'count') {
		unset($limit);
	}

	$sql = "SELECT
				id,
				currency_code,
				currency_sign,
				currency_position
			FROM
				" . TB_PREFIX . "currency
			WHERE domain_id = :domain_id AND enabled = 1
				$where
			ORDER BY
				$sort $dir
			$limit";

	if (empty($query)) {
		$result = dbQuery($sql, ':domain_id', $auth_session->domain_id);
	} else {
		$result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
	}

	return $result;
}

$sth = sql('', $dir, $sort, $rp, $page);
$sth_count_rows = sql('count', $dir, $sort, $rp, $page);

$currencies = $sth->fetchAll(PDO::FETCH_ASSOC);
$count = count($sth_count_rows->fetchAll());

$xml = "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($currencies as $row) {
	$code_esc = htmlspecialchars($row['currency_code'] ?? '');
	$sign_esc = htmlspecialchars($row['currency_sign'] ?? '');
	$position_esc = htmlspecialchars($row['currency_position'] ?? '');

	$action = '<div class="dropdown">';
	$action .= '<a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>' . ($LANG['actions'] ?? 'Actions') . '</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
	$action .= '<div class="dropdown-menu dropdown-menu-end">';
	$action .= '<a class="dropdown-item" href="index.php?module=currencies&amp;view=details&amp;id=' . (int)$row['id'] . '&amp;action=view"><i class="ti ti-eye me-2"></i>' . ($LANG['view'] ?? 'View') . '</a>';
	$action .= '<a class="dropdown-item" href="index.php?module=currencies&amp;view=details&amp;id=' . (int)$row['id'] . '&amp;action=edit"><i class="ti ti-edit me-2"></i>' . ($LANG['edit'] ?? 'Edit') . '</a>';
	$action .= '</div></div>';

	$xml .= "<row id='" . (int)$row['id'] . "'>";
	$xml .= "<cell><![CDATA[" . $action . "]]></cell>";
	$xml .= "<cell><![CDATA[" . $code_esc . "]]></cell>";
	$xml .= "<cell><![CDATA[" . $sign_esc . "]]></cell>";
	$xml .= "<cell><![CDATA[" . $position_esc . "]]></cell>";
	$xml .= "</row>";
}

$xml .= "</rows>";

echo $xml;
