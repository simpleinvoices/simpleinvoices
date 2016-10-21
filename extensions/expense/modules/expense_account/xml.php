<?php
function sql($type='', $dir, $sort, $rp, $page ) {
    global $pdoDb;

    $validFields = array('id', 'name');

    // Set up WHERE clause which is needed for both count and data access modes.
    $domain_id = domain_id::get();
    if (!empty($_POST['qtype']) && !empty($_POST['query'])) {
        $qtype = $_POST['qtype'];
        if (in_array($qtype, $validFields)) {
            $query = $_POST['query'];
            $pdoDb->addToWhere(new WhereItem(false, $qtype, 'LIKE', "%$query%", false, "AND"));
        }
    }
    $pdoDb->addSimpleWhere("domain_id", $domain_id);

    if($type =="count") {
        $pdoDb->addToFunctions("count(*) AS count");
        $rows = $pdoDb->request("SELECT", "expense_account");
        return $rows[0]['count'];
    }

    if (!in_array($sort, $validFields)) $sort = "id";

    // Set up start offset.
    if (empty($page) || intval($page) != $page) $page = 1;

    if (intval($rp) != $rp) $rp = 25;

    $start = (($page-1) * $rp);
    $pdoDb->setLimit($rp, $start);

    if (!preg_match('/^(asc|desc)$/iD', $dir)) $sort .= ' DESC';

    return $pdoDb->request("SELECT", "expense_account");
}

global $LANG;

header("Content-type: text/xml");

// @formatter:off
$dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "id" ;
$rp   = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25" ;
$page = (isset($_POST['page'])     ) ? $_POST['page']      : "1" ;

$expense_accounts = sql('', $dir, $sort, $rp, $page);
$count = sql('count',$dir, $sort, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";
foreach ($expense_accounts as $row) {
    $xml .= "<row id='".$row['id']."'>";
    $xml .= 
        "<cell><![CDATA[
           <a class='index_table' title='$LANG[view]' href='index.php?module=expense_account&view=details&id=$row[id]&action=view'>
             <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
           <a class='index_table' title='$LANG[edit]' href='index.php?module=expense_account&view=details&id=$row[id]&action=edit'>
             <img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
         ]]></cell>";
    $xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
    $xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";
// @formatter:on

echo $xml;
