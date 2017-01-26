<?php

function sql($type='', $start, $dir, $sort, $rp, $page ) {
    global $LANG, $pdoDb;

    $valid_search_fields = array('c.id', 'c.name');

    $query = isset($_POST['query']) ? $_POST['query'] : null;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
    if (!empty($qtype) && !empty($query)) {
        if ( in_array($qtype, $valid_search_fields) ) {
            $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
        }
    }
    $pdoDb->addSimpleWhere("c.domain_id", domain_id::get());

    if($type =="count") {
        $pdoDb->addToFunctions("COUNT(*) AS count");
        $rows = $pdoDb->request("SELECT", "customers", "c");
        return $rows[0]['count'];
    }

    if (intval($page) != $page) $start = 0;
    if (intval($rp)   != $rp  ) $rp = 25;

    $start = (($page - 1) * $rp);

    $pdoDb->setSelectList(array("c.id as CID", "c.name", "c.enabled"));

    $ca = new CaseStmt("c.enabled", "enabled_txt");
    $ca->addWhen( "=", ENABLED, $LANG['enabled']);
    $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
    $pdoDb->addToCaseStmts($ca);

    $fn = new FunctionStmt("SUM", "COALESCE(ii.total, 0)", "total");
    $fr = new FromStmt("invoice_items", "ii");
    $jn = new Join("INNER", "invoices", "iv");
    $oc = new OnClause();
    $oc->addSimpleItem("iv.id", new DbField("ii.invoice_id"), "AND");
    $oc->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
    $jn->setOnClause($oc);
    $wh = new WhereClause();
    $wh->addSimpleItem("iv.customer_id", new DbField("CID"), "AND");
    $wh->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
    $se = new Select($fn, $fr, $wh, "customer_total");
    $se->addJoin($jn);
    $pdoDb->addToSelectStmts($se);

    $fn = new FunctionStmt("MAX", new DbField("iv.index_id"));
    $fr = new FromStmt("invoices", "iv");
    $wh = new WhereClause();
    $wh->addSimpleItem("iv.customer_id", new DbField("CID"), "AND");
    $wh->addSimpleItem("iv.domain_id", new DbField("c.domain_id"));
    $se = new Select($fn, $fr, $wh, "last_invoice");
    $pdoDb->addToSelectStmts($se);

    $fn = new FunctionStmt("SUM", "COALESCE(ap.ac_amount, 0)", "amount");
    $fr = new FromStmt("payment", "ap");
    $jn = new Join("INNER", "invoices", "iv");
    $oc = new OnClause();
    $oc->addSimpleItem("iv.id", new DbField("ap.ac_inv_id"), "AND");
    $oc->addSimpleItem("iv.domain_id", new DbField("ap.domain_id"));
    $jn->setOnClause($oc);
    $wh = new WhereClause();
    $wh->addSimpleItem("iv.customer_id", new DbField("CID"), "AND");
    $wh->addSimpleItem("iv.domain_id", new DbField("ap.domain_id"));
    $se = new Select($fn, $fr, $wh, "paid");
    $se->addJoin($jn);
    $pdoDb->addToSelectStmts($se);

    $fn = new FunctionStmt(null, "customer_total");
    $fn->addPart("-", "paid");
    $se = new Select($fn, null, null, "owing");
    $pdoDb->addToSelectStmts($se);

    $validFields = array('CID', 'name', 'customer_total', 'paid', 'owing', 'enabled');
    if (in_array($sort, $validFields)) {
        $dir = (preg_match('/^(asc|desc)$/iD', $dir) ? 'A' : 'D');
        $sortlist = array(array("enabled", "D"), array($sort, $dir));
    } else {
        $sortlist = array(array("enabled", "D"), array("name", "A"));
    }
    $pdoDb->setOrderBy($sortlist);

    $pdoDb->setGroupBy("CID");

    $pdoDb->setLimit($rp, $start);

    $result = $pdoDb->request("SELECT", "customers", "c");
    return $result;
}

global $LANG;

header("Content-type: text/xml");

// @formatter:off
$start = (isset($_POST['start'])    ) ? $_POST['start']     : "0";
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "";
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "";
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25";
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1";
// @formatter:on

$customers = sql('', $start, $dir, $sort, $rp, $page);
$count = sql('count', $start, $dir, $sort, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

$viewcust = "$LANG[view] $LANG[customer]";
$editcust = "$LANG[edit] $LANG[customer]";
$inv4cust = "$LANG[new_invoice] $LANG[for] $LANG[customer]";
foreach ($customers as $row) {
    $last_invoice = utf8_encode($row['last_invoice']);
    $vname = $viewcust . $row['name'];
    $ename = $editcust . $row['name'];
    $iname = $inv4cust . $row['name'];
    $image = ($row['enabled'] == 0 ? 'images/common/cross.png' : 'images/common/tick.png');
    $xml .= "<row id='$row[CID]'>";
    $xml .=
       "<cell><![CDATA[
          <a class='index_table' title='$vname' href='index.php?module=customers&view=details&id=$row[CID]&action=view'>
            <img src='images/common/view.png' class='action' />
          </a>
          <a class='index_table' title='$ename' href='index.php?module=customers&view=details&id=$row[CID]&action=edit'>
            <img src='images/common/edit.png' class='action' />
          </a>
          <a class='index_table' title='$iname' href='index.php?module=invoices&view=usedefault&customer_id=$row[CID]&action=view'>
            <img src='images/common/view.png' class='action' />
          </a>
        ]]></cell>";
    $xml .= "<cell><![CDATA[$row[CID]]]></cell>";
    $xml .= "<cell><![CDATA[$row[name]]]></cell>";
    $xml .= 
       "<cell><![CDATA[
          <a class='index_table' title='quick view' href='index.php?module=invoices&view=quick_view&id=$last_invoice'>
            $last_invoice
          </a>
        ]]></cell>";
    $xml .= "<cell><![CDATA[" . siLocal::number($row['customer_total']) . "]]></cell>";
    $xml .= "<cell><![CDATA[" . siLocal::number($row['paid']) . "]]></cell>";
    $xml .= "<cell><![CDATA[" . siLocal::number($row['owing']) . "]]></cell>";
    $xml .= "<cell><![CDATA[<img src='$image' alt='$row[enabled_txt]' title='$row[enabled_txt]' />]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;
