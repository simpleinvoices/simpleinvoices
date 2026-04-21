<?php

header('Content-type: text/xml');

global $LANG;

$dir = isset($_REQUEST['sortorder']) ? $_REQUEST['sortorder'] : 'ASC';
$sort = isset($_REQUEST['sortname']) ? $_REQUEST['sortname'] : 'sort_order';
$rp = isset($_REQUEST['rp']) ? (int) $_REQUEST['rp'] : 25;
$page = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;
if ($rp < 1) {
	$rp = 25;
}
if ($page < 1) {
	$page = 1;
}

if (!preg_match('/^(asc|desc)$/iD', $dir)) {
	$dir = 'ASC';
}

$valid_search_fields = ['term_id', 'term_code', 'term_label'];
$where = '';
$query = $_REQUEST['query'] ?? null;
$qtype = $_REQUEST['qtype'] ?? null;
if (!empty($qtype) && !empty($query) && in_array($qtype, $valid_search_fields, true)) {
	$where = " AND $qtype LIKE :query ";
} else {
	$qtype = null;
	$query = null;
}

$validFields = ['term_id', 'term_code', 'term_label', 'calc_kind', 'param_int', 'sort_order'];
if (!in_array($sort, $validFields, true)) {
	$sort = 'sort_order';
}

$start = ($page - 1) * $rp;
$limit = 'LIMIT ' . $rp . ' OFFSET ' . $start;

$sqlList = 'SELECT term_id, term_code, term_label, calc_kind, param_int, sort_order'
	.' FROM '.TB_PREFIX.'payment_terms'
	.' WHERE 1=1 '.$where
	.' ORDER BY '.$sort.' '.$dir.' '.$limit;

$sqlCount = 'SELECT COUNT(*) FROM '.TB_PREFIX.'payment_terms WHERE 1=1 '.$where;

if (empty($query)) {
	$sth = dbQuery($sqlList);
	$sthCount = dbQuery($sqlCount);
} else {
	$sth = dbQuery($sqlList, ':query', '%'.$query.'%');
	$sthCount = dbQuery($sqlCount, ':query', '%'.$query.'%');
}

$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
$count = (int) $sthCount->fetchColumn();

$xml = '';
$xml .= '<rows>';
$xml .= '<page>'.(int) $page.'</page>';
$xml .= '<total>'.$count.'</total>';

$confirmJs = htmlspecialchars($LANG['payment_term_delete_confirm'] ?? 'Delete this payment term?', ENT_QUOTES);

foreach ($rows as $row) {
	$desc_esc = htmlspecialchars($row['term_label'] ?? '');
	$paramDisp = (($row['calc_kind'] ?? '') === 'EOM') ? '-' : (string) ($row['param_int'] ?? '');
	$action = '<div class="dropdown">';
	$action .= '<a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
	$action .= '<div class="dropdown-menu dropdown-menu-end">';
	$action .= '<a class="dropdown-item" href="index.php?module=payment_terms&amp;view=details&amp;id='.$row['term_id'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$desc_esc.'</a>';
	$action .= '<a class="dropdown-item" href="index.php?module=payment_terms&amp;view=details&amp;id='.$row['term_id'].'&amp;action=edit"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$desc_esc.'</a>';
	$action .= '<form method="post" action="index.php?module=payment_terms&amp;view=save" class="d-inline" onsubmit="return confirm(\''.$confirmJs.'\');">';
	$action .= '<input type="hidden" name="op" value="delete_payment_term" />';
	$action .= '<input type="hidden" name="term_id" value="'.(int) $row['term_id'].'" />';
	$action .= '<button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-2"></i>'.$LANG['delete'].'</button>';
	$action .= '</form>';
	$action .= '</div></div>';
	$xml .= "<row id='".$row['term_id']."'>";
	$xml .= '<cell><![CDATA['.$action.']]></cell>';
	$xml .= '<cell><![CDATA['.htmlspecialchars($row['term_code']).']]></cell>';
	$xml .= '<cell><![CDATA['.htmlspecialchars($row['term_label']).']]></cell>';
	$xml .= '<cell><![CDATA['.htmlspecialchars($row['calc_kind']).']]></cell>';
	$xml .= '<cell><![CDATA['.htmlspecialchars($paramDisp).']]></cell>';
	$xml .= '<cell><![CDATA['.(int) $row['sort_order'].']]></cell>';
	$xml .= '</row>';
}
$xml .= '</rows>';

echo $xml;
