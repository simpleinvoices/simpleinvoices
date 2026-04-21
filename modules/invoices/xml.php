<?php

// Include necessary classes for standalone XML endpoint
$root_path = dirname(dirname(__DIR__));
require_once($root_path . '/include/init.php');
require_once($root_path . '/include/class/CurrencySignHelper.php');

// Ensure clean XML output (no warnings or stray output)
while (ob_get_level()) ob_end_clean();
header("Content-type: text/xml; charset=utf-8");

// Grid params: support GET (default) and POST for compatibility
$dir = strtoupper($_REQUEST['sortorder'] ?? "DESC");
$sort = $_REQUEST['sortname'] ?? "index_id";
$rp = (int)($_REQUEST['rp'] ?? 10);
$having = $_REQUEST['having'] ?? "";
$page = (int)($_REQUEST['page'] ?? 1);
if ($page < 1) $page = 1;
if ($rp < 10) $rp = 10;

// ── Invoice grid XML cache ───────────────────────────────────────────────────
$_inv_cache_dir  = $root_path . '/tmp/cache/invoices_xml';
$_inv_cache_key  = md5(serialize([
    $auth_session->domain_id,
    $auth_session->role_name ?? '',
    $auth_session->user_id   ?? '',
    $dir, $sort, $rp, $page, $having,
    $_REQUEST['query'] ?? '',
    $_REQUEST['qtype'] ?? '',
    getDefaultLargeDataset(),
]));
$_inv_cache_file = $_inv_cache_dir . '/inv_' . (int) $auth_session->domain_id . '_' . $_inv_cache_key . '.xml';
$_inv_cache_ttl  = 300; // 5 minutes; also cleared immediately on any invoice/payment write

if (file_exists($_inv_cache_file) && (time() - filemtime($_inv_cache_file)) < $_inv_cache_ttl) {
    echo file_get_contents($_inv_cache_file);
    exit;
}
// ── end cache check ──────────────────────────────────────────────────────────

//$sql = "SELECT * FROM ".TB_PREFIX."invoices LIMIT $start, $limit";
$invoice = new invoice();
$invoice->domain_id = $auth_session->domain_id ?? domain_id::get();
$invoice->sort = $sort;

if($auth_session->role_name =='customer') {
	$invoice->customer = $auth_session->user_id;
} elseif ($auth_session->role_name =='biller') {
	$invoice->biller = $auth_session->user_id;
}

$invoice->query=$_REQUEST['query'] ?? null;
$invoice->qtype=$_REQUEST['qtype'] ?? null;

$large_dataset = getDefaultLargeDataset();
if($large_dataset == $LANG['enabled'])
{
  $sth = $invoice->select_all('large', $dir, $rp, $page, $having);
  $sth_count_rows = $invoice->count();
  $invoice_count = $sth_count_rows->fetch(PDO::FETCH_ASSOC);
  $invoice_count = $invoice_count['count'];
} else {
  $sth = $invoice->select_all('', $dir, $rp, $page, $having);
  $sth_count_rows = $invoice->select_all('grid_total', $dir, $rp, $page, $having);
  $cnt_row = $sth_count_rows->fetch(PDO::FETCH_ASSOC);
  $invoice_count = (int) ($cnt_row['cnt'] ?? 0);
}
$invoices = $sth->fetchAll(PDO::FETCH_ASSOC);

$xml ="";
	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>". $invoice_count ."</total>";
	
	foreach ($invoices as $row) {
		$xml .= "<row id='".$row['id']."'>";
		$inv_label = htmlspecialchars($row['preference'] . ' ' . $row['index_id']);
		$action  = '<div class="dropdown">';
		$action .= '<a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
		$action .= '<div class="dropdown-menu dropdown-menu-end">';
		$action .= '<a class="dropdown-item" href="index.php?module=invoices&amp;view=quick_view&amp;id='.$row['id'].'"><i class="ti ti-eye me-2"></i>'.$LANG['quick_view_tooltip'].' '.$inv_label.'</a>';
		if ($auth_session->role_name !== 'customer') {
			$action .= '<a class="dropdown-item" href="index.php?module=invoices&amp;view=details&amp;id='.$row['id'].'&amp;action=view"><i class="ti ti-edit me-2"></i>'.$LANG['edit_view_tooltip'].' '.$inv_label.'</a>';
		}
		$action .= '<div class="dropdown-divider"></div>';
		$pdf_url = htmlspecialchars('index.php?module=export&view=invoice&id='.$row['id'].'&format=pdf', ENT_QUOTES);
		$action .= '<a class="dropdown-item si-preview-link" href="index.php?module=export&amp;view=invoice&amp;id='.$row['id'].'&amp;format=print" data-preview-title="'.htmlspecialchars($LANG['print_preview_tooltip'].' '.$inv_label, ENT_QUOTES).'" data-preview-pdf="'.$pdf_url.'"><i class="ti ti-printer me-2"></i>'.$LANG['print_preview_tooltip'].' '.$inv_label.'</a>';
		$action .= '<a class="dropdown-item invoice_export_dialog" href="#" rel="'.$row['id'].'"><i class="ti ti-file-export me-2"></i>'.$LANG['export_tooltip'].' '.$inv_label.'</a>';
		$action .= '<div class="dropdown-divider"></div>';
		if ($row['status'] && $row['owing'] > 0) {
			$action .= '<a class="dropdown-item" href="index.php?module=payments&amp;view=process&amp;id='.$row['id'].'&amp;op=pay_selected_invoice"><i class="ti ti-cash me-2"></i>'.$LANG['process_payment_for'].' '.$inv_label.'</a>';
		} elseif ($row['status']) {
			$action .= '<a class="dropdown-item" href="index.php?module=payments&amp;view=details&amp;id='.$row['id'].'&amp;action=view"><i class="ti ti-receipt me-2"></i>'.$LANG['process_payment_for'].' '.$inv_label.'</a>';
		}
		if ($auth_session->role_name !== 'customer') {
			$action .= '<a class="dropdown-item" href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id='.$row['id'].'"><i class="ti ti-mail-forward me-2"></i>'.$LANG['email'].' '.$inv_label.'</a>';
		}
		$action .= '</div></div>';
		$xml .= "<cell><![CDATA[".$action."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['index_name']."]]></cell>";		
		$xml .= "<cell><![CDATA[".$row['biller']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['customer']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";
		$xml .= "<cell><![CDATA[".htmlspecialchars(CurrencySignHelper::forDisplay($row['currency_sign'] ?? '')).siLocal::number($row['invoice_total'])."]]></cell>";
		if ($row['status']) {
			if ($row['owing'] <= 0) {
				$status_html = '<span class="d-none d-sm-inline"><span class="status status-green">Paid</span></span><span class="d-sm-none"><span class="status status-green"><span class="status-dot"></span></span></span>';
			} else {
				$aging = $row['aging'];
				$dot_color = 'secondary';
				if ($aging === '0-30') {
					$dot_color = 'secondary';
				} elseif ($aging === '31-60') {
					$dot_color = 'yellow';
				} elseif ($aging === '61-90') {
					$dot_color = 'orange';
				} elseif ($aging === '90+') {
					$dot_color = 'red';
				}
				$status_html = '<span class="d-none d-sm-inline"><span class="status status-'.$dot_color.'">'.($LANG['unpaid'] ?? 'Unpaid').'</span></span><span class="d-sm-none"><span class="status status-'.$dot_color.'"><span class="status-dot"></span></span></span>';
			}
			$xml .= "<cell><![CDATA[".$status_html."]]></cell>";
		} else {
			$xml .= "<cell><![CDATA[<span class=\"d-none d-sm-inline\"><span class=\"status status-secondary\"><span class=\"status-dot\"></span>Draft</span></span><span class=\"d-sm-none\"><span class=\"status status-secondary\"><span class=\"status-dot\"></span></span></span>]]></cell>";
		}
		$xml .= "<cell><![CDATA[".$row['preference']."]]></cell>";				
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

// ── Write xml cache ───────────────────────────────────────────────────────────
if (! is_dir($_inv_cache_dir)) {
    @mkdir($_inv_cache_dir, 0755, true);
}
@file_put_contents($_inv_cache_file, $xml, LOCK_EX);
// ── end cache write ───────────────────────────────────────────────────────────

echo $xml;
?>
