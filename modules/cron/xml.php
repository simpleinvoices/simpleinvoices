<?php

header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_REQUEST['sortorder'])) ? $_REQUEST['sortorder'] : "DESC" ;
$sort = (isset($_REQUEST['sortname'])) ? $_REQUEST['sortname'] : "id" ;
$rp = (isset($_REQUEST['rp'])) ? $_REQUEST['rp'] : "25" ;
$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : "1" ;

//$sql = "SELECT * FROM ".TB_PREFIX."cron LIMIT $start, $limit";

$cron = new cron();
$cron->sort = $sort;
$crons = $cron->select_all('', $dir, $rp, $page);
$sth_count_rows = $cron->select_all('count', $dir, $rp, $page);
unset($cron);

$count = $sth_count_rows;

$xml ="";

	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";

	foreach ($crons as $row) {
		$row['email_biller_nice'] = $row['email_biller']==1?$LANG['yes']:$LANG['no'];
		$row['email_customer_nice'] = $row['email_customer']==1?$LANG['yes']:$LANG['no'];
		$name_esc = htmlspecialchars($row['name'] ?? $row['index_name'] ?? (string)$row['id']);
		$action  = '<div class="dropdown">';
		$action .= '<a class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline"><i class="ti ti-bolt me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
		$action .= '<div class="dropdown-menu dropdown-menu-end">';
		$action .= '<a class="dropdown-item" href="index.php?module=cron&amp;view=view&amp;id='.$row['id'].'"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$name_esc.'</a>';
		$action .= '<a class="dropdown-item" href="index.php?module=cron&amp;view=edit&amp;id='.$row['id'].'"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$name_esc.'</a>';
		$action .= '<div class="dropdown-divider"></div>';
		$action .= '<a class="dropdown-item" href="index.php?module=cron&amp;view=delete&amp;id='.$row['id'].'"><i class="ti ti-trash me-2"></i>'.$LANG['delete'].' '.$name_esc.'</a>';
		$action .= '</div></div>';
		$xml .= "<row id='".$row['id']."'>";
		$xml .= "<cell><![CDATA[".$action."]]></cell>";
		#$xml .= "<cell><![CDATA[".siLocal::date($row['start_date'])."]]></cell>";
		#$xml .= "<cell><![CDATA[".siLocal::date($row['end_date'])."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['start_date']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['end_date']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['recurrence']." ".$row['recurrence_type']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['email_biller_nice']."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['email_customer_nice']."]]></cell>";
		$xml .= "</row>";		
	}

	$xml .= "</rows>";

echo $xml;
?> 
