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
        $pdoDb->addToFunctions("count(*) AS count");
        $rows = $pdoDb->request("SELECT", "customers", "c");
        return $rows[0]['count'];
    }

    if (intval($page) != $page) $start = 0;
    if (intval($rp)   != $rp  ) $rp = 25;

    $start = (($page - 1) * $rp);

    $validFields = array('CID', 'name', 'customer_total', 'paid', 'owing', 'enabled');
    if (!in_array($sort, $validFields)) {
        $sortlist = array(array("enabled", "D"), array("name", "A"));
    } else {
        $dir = (preg_match('/^(asc|desc)$/iD', $dir) ? 'A' : 'D');
        $sortlist = array(array("enabled", "D"), array($sort, $dir));
    }
    $pdoDb->setOrderBy($sortlist);
    
    $join = new Join("LEFT", "invoices", "iv");
    $join->addSimpleItem("iv.customer_id", new DbField("c.id"), "AND");
    $join->addSimpleItem("iv.domain_id"  , new DbField("c.domain_id"));
    $pdoDb->addToJoins($join);

    $join = new Join("LEFT", "preferences", "pr");
    $join->addSimpleItem("pr.pref_id"  , new DbField("iv.preference_id"), "AND");
    $join->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
    $pdoDb->addToJoins($join);

    $join = new Join("LEFT", "invoice_items", "ii");
    $join->addSimpleItem("iv.id"       , new DbField("ii.invoice_id"), "AND");
    $join->addSimpleItem("iv.domain_id", new DbField("ii.domain_id"));
    $pdoDb->addToJoins($join);

    $ij = new Join("INNER", "invoices", "iv3");
    $ij->addSimpleItem("iv3.id", new DbField("p.ac_inv_id"), "AND");
    $ij->addSimpleItem("iv3.domain_id", new DbField("p.domain_id"));
    $ij->addGroupBy(new GroupBy(array("iv3.customer_id", "p.domain_id")));
    $select = new Select(array("iv3.customer_id AS customer_id", "p.domain_id AS domain_id",
                         new FunctionStmt("SUM","COALESCE(p.ac_amount,0)", "amount")),
                         new FromStmt("payment", "p"));
    $select->addJoin($ij);
    $join = new Join("LEFT", $select, "ap");

    $oc = new OnClause(new OnItem(false, "ap.customer_id", "=", new DbField("c.id"), false, "AND"));
    $oc->addSimpleItem("ap.domain_id", new DbField("c.domain_id"));
    $join->setOnClause($oc);
    $pdoDb->addToJoins($join);
    // @formatter:off

    $case = new CaseStmt("c.enabled", "enabled_txt");
    $case->addWhen( "=", ENABLED, $LANG['enabled']);
    $case->addWhen("!=", ENABLED, $LANG['disabled'], true);
    $pdoDb->addToCaseStmts($case);

    $cust_tot = new FunctionStmt("SUM", "COALESCE(IF(pr.status = 1, ii.total, 0),  0)", "customer_total");
    $pdoDb->addToFunctions($cust_tot->build());

    $paid = new FunctionStmt("COALESCE", "ap.amount,0", "paid");
    $pdoDb->addToFunctions($paid->build());

    $owing = new FunctionStmt("SUM", "COALESCE(IF(pr.status = 1, ii.total, 0),  0)", "owing");
    $owing->addPart("-", "COALESCE(ap.amount,0)");
    $pdoDb->addToFunctions($owing->build());

    $pdoDb->addToFunctions("(SELECT MAX(iv.id)) AS last_invoice");

    $pdoDb->setGroupBy("CID");

    $pdoDb->setLimit($rp, $start);

    $pdoDb->setSelectList(array("c.id as CID", "c.name", "c.enabled"));

    $result = $pdoDb->request("SELECT", "customers", "c");
    return $result;
}

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
    $xml .= "<row id='" . $row['CID'] . "'>";
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
