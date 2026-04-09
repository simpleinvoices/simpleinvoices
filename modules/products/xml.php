<?php

header("Content-type: text/xml");

//$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_REQUEST['sortorder'])) ? $_REQUEST['sortorder'] : "ASC" ;
$sort = (isset($_REQUEST['sortname'])) ? $_REQUEST['sortname'] : "description" ;
$rp = (isset($_REQUEST['rp'])) ? $_REQUEST['rp'] : "25" ;
$page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : "1" ;

$defaults = getSystemDefaults();
$smarty -> assign("defaults",$defaults);

$products = new product();
$sth = $products->select_all('', $dir, $sort, $rp, $page);
$sth_count_rows = $products->select_all('count',$dir, $sort, $rp, $page);

$products_all = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = count($sth_count_rows->fetchAll());

//echo sql2xml($customers, $count);
$xml .= "<rows>";

$xml .= "<page>$page</page>";

$xml .= "<total>$count</total>";

foreach ($products_all as $row) {
	$desc_esc = htmlspecialchars($row['description']);
	$action  = '<div class="dropdown">';
	$action .= '<a class="btn btn-outline-secondary dropdown-toggle btn-sm-mobile" data-bs-toggle="dropdown" aria-expanded="false"><span class="d-none d-sm-inline-flex align-items-center"><i class="ti ti-settings me-1"></i>'.$LANG['actions'].'</span><span class="d-sm-none"><i class="ti ti-dots-vertical" aria-hidden="true"></i></span></a>';
	$action .= '<div class="dropdown-menu dropdown-menu-end">';
	$action .= '<a class="dropdown-item" href="index.php?module=products&amp;view=details&amp;id='.$row['id'].'&amp;action=view"><i class="ti ti-eye me-2"></i>'.$LANG['view'].' '.$desc_esc.'</a>';
	$action .= '<a class="dropdown-item" href="index.php?module=products&amp;view=details&amp;id='.$row['id'].'&amp;action=edit"><i class="ti ti-edit me-2"></i>'.$LANG['edit'].' '.$desc_esc.'</a>';
	$action .= '</div></div>';
	$xml .= "<row id='".$row['iso']."'>";
	$xml .= "<cell><![CDATA[".$action."]]></cell>";
	$xml .= "<cell><![CDATA[".$row['description']."]]></cell>";
	$xml .= "<cell><![CDATA[".siLocal::number($row['unit_price'])."]]></cell>";
    if($defaults['inventory'] == '1')
    {
      	$xml .= "<cell><![CDATA[".siLocal::number_trim($row['quantity'])."]]></cell>";
    }

	if ($row['enabled']==$LANG['enabled']) {
		$xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";				
	}	
	else {
		$xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";				
	}
	$xml .= "</row>";		
}

$xml .= "</rows>";

echo $xml;

?> 
