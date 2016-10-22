<?php
header("Content-type: text/xml");

function sql($type = '', $dir, $sort, $rp, $page, $domain_id) {
    global $pdoDb, $LANG;

    $query = isset($_POST['query']) ? $_POST['query'] : null;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
    if (!(empty($qtype) || empty($query))) {
        if (in_array($qtype, array('pt_id', 'pt_description'))) {
            $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
        }
    }
    $pdoDb->addSimpleWhere("domain_id", $domain_id);

    if ($type == "count") {
        $pdoDb->addToFunctions("COUNT(*) AS count");
        $rows = $pdoDb->request("SELECT", "payment_types");
        return $rows[0]['count'];
    }

    // Check that the sort field is OK
    if (!preg_match('/^(asc|desc|A|D)$/iD', $dir)) $dir = 'A';
    if (!in_array($sort, array('pt_id', 'pt_description', 'enabled'))) {
        $sort = "pt_description";
        $dir  = "A";
    }
    $pdoDb->setOrderBy(array($sort, $dir));

    if (intval($rp) != $rp   ) $rp = 25;
    $start = (($page - 1) * $rp);
    $pdoDb->setLimit($rp, $start);

    $oc = new CaseStmt("pt_enabled", "enabled");
    $oc->addWhen("=", ENABLED, $LANG['enabled']);
    $oc->addWhen("!=", ENABLED, $LANG['disabled'], true);
    $pdoDb->addToCaseStmts($oc);

    $pdoDb->setSelectAll(true);

    return $pdoDb->request("SELECT", "payment_types");
}

global $LANG;

$domain_id = domain_id::get();

// @formatter:off
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "A";
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "pt_description";
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25";
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1";

$payment_types = sql(''     , $dir, $sort, $rp, $page, $domain_id);
$count         = sql('count', $dir, $sort, $rp, $page, $domain_id);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($payment_types as $row) {
    $pt_desc = $row['pt_description'];
    $pt_id = $row['pt_id'];
    $enabled = $row['enabled'];
    $title   = "$LANG[view] $LANG[payment_type] $pt_desc";
    $pic = ($enabled == $LANG['enabled'] ? "images/common/tick.png" : "images/common/cross.png");

    $xml .= "<row id='$pt_id'>";
    $xml .=
        "<cell><![CDATA[
        <a class='index_table' title='$title'
           href='index.php?module=payment_types&view=details&id=$pt_id&action=view'>
          <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
            </a>";
    $xml .= 
           "<a class='index_table' title='$title'
           href='index.php?module=payment_types&view=details&id=$pt_id&action=edit'>
          <img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />
        </a>
    ]]></cell>";
    $xml .= "<cell><![CDATA[$pt_desc]]></cell>";
    $xml .= "<cell><![CDATA[<img src='$pic' alt='$enabled' title='$enabled' />]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";
// @formatter:on

echo $xml;
