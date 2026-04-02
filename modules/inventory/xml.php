<?php

header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_REQUEST['sortorder'])) ? $_REQUEST['sortorder'] : "DESC" ;
$sort = (isset($_REQUEST['sortname'])) ? $_REQUEST['sortname'] : "id" ;
$rp = (isset($_REQUEST['rp'])) ? $_REQUEST['rp'] : "25" ;
$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : "1" ;

//$sql = "SELECT * FROM ".TB_PREFIX."invoices LIMIT $start, $limit";
$inventory = new inventory();
$inventory->sort=$sort;
$inventory_all = $inventory->select_all('', $dir, $rp, $page);
$sth_count_rows = $inventory->select_all('count',$dir, $rp, $page);


$xml ="";
$count = $sth_count_rows;

	$xml .= "<rows>";
	$xml .= "<page>$page</page>";
	$xml .= "<total>$count</total>";
	
	foreach ($inventory_all as $row) {
		$name_esc = htmlspecialchars($row['description'] ?? $row['name'] ?? (string)$row['id']);
		$action  = '<div class="dropdown">';
		$action .= '<a class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
		$action .= '<div class="dropdown-menu dropdown-menu-end">';
		$action .= '<a class="dropdown-item" href="index.php?module=inventory&amp;view=view&amp;id='.$row['id'].'"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$name_esc.'</a>';
		$action .= '<a class="dropdown-item" href="index.php?module=inventory&amp;view=edit&amp;id='.$row['id'].'"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$name_esc.'</a>';
		$action .= '</div></div>';
		$xml .= "<row id='".$row['id']."'>";
		$xml .= "<cell><![CDATA[".$action."]]></cell>";
		$xml .= "<cell><![CDATA[".$row['date']."]]></cell>";		
		$xml .= "<cell><![CDATA[".$row['description']."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['quantity'])."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['cost'])."]]></cell>";
		$xml .= "<cell><![CDATA[".siLocal::number($row['total_cost'])."]]></cell>";
		$xml .= "</row>";		
	}
	$xml .= "</rows>";

echo $xml;
?> 
