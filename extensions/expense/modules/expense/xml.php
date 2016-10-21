<?php
function sql($type='', $dir, $sort, $rp, $page ) {
    global $LANG, $pdoDb;

    $table_alias = "";
    $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
    $qtype = isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : null;
    if ( ! (empty($qtype) || empty($query)) ) {
        $valid_search_fields = array('e.id', 'ea.name', 'b.name', 'c.name', 'i.id', 'e.status_wording');
        if (in_array($qtype, $valid_search_fields)) {
            $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
            $table_alias = strstr($qtype, ".", true);
        }
    }
    $pdoDb->addSimpleWhere("e.domain_id", domain_id::get());

    $get_count = ($type =="count");
    if (!$get_count || $table_alias == "ea") {
        $join = new Join("LEFT", "expense_account", "ea");
        $join->addSimpleItem("ea.id"       , new DbField("e.expense_account_id"), "AND");
        $join->addSimpleItem("ea.domain_id", new DbField("e.domain_id"));
        $pdoDb->addToJoins($join);
    }

    if (!$get_count || $table_alias == "b") {
        $join = new Join("LEFT", "biller", "b");
        $join->addSimpleItem("b.id"       , new DbField("e.biller_id"), "AND");
        $join->addSimpleItem("b.domain_id", new DbField("e.domain_id"));
        $pdoDb->addToJoins($join);
    }

    if (!$get_count || $table_alias == "c") {
        $join = new Join("LEFT", "customers", "c");
        $join->addSimpleItem("c.id"       , new DbField("e.customer_id"), "AND");
        $join->addSimpleItem("c.domain_id", new DbField("e.domain_id"));
        $pdoDb->addToJoins($join);
    }

    if (!$get_count || $table_alias == "p") {
        $join = new Join("LEFT", "products", "p");
        $join->addSimpleItem("p.id"       , new DbField("e.product_id"), "AND");
        $join->addSimpleItem("p.domain_id", new DbField("e.domain_id"));
        $pdoDb->addToJoins($join);
    }

    if (!$get_count || $table_alias == "i") {
        $join = new Join("LEFT", "invoices", "i");
        $join->addSimpleItem("i.id"       , new DbField("e.invoice_id"), "AND");
        $join->addSimpleItem("i.domain_id", new DbField("e.domain_id"));
        $pdoDb->addToJoins($join);
    }

    if($get_count) {
        $pdoDb->addToFunctions("count(*) AS count");
        $rows = $pdoDb->request("SELECT", "expense", "e");
        return $rows[0]['count'];
    }

    if (intval($page) != $page) $page = 1;
    if (intval($rp) != $rp) $rp = 25;

    $start = (($page-1) * $rp);
    $pdoDb->setLimit($rp, $start);

    $validFields = array('id', 'status', 'amount', 'expense_account_id','biller_id', 'customer_id', 'invoice_id','date','amount','note');
    if (!in_array($sort, $validFields)) $sort = "id";

    $dir = (preg_match('/^(asc|desc)$/iD', $dir) ? 'A' : 'D');
    $pdoDb->setOrderBy(array($sort, $dir));

    $case = new CaseStmt("status", "status_wording");
    $case->addWhen( "=", ENABLED, $LANG['paid']);
    $case->addWhen("!=", ENABLED, $LANG['not_paid'], true);
    $pdoDb->addToCaseStmts($case);

    // This is a fudge until sub-select can be added to the features.
    $exp_item_tax = TB_PREFIX . "expense_item_tax";
    $pdoDb->addToFunctions("(SELECT SUM(tax_amount) FROM $exp_item_tax WHERE expense_id = id) AS tax");
    $pdoDb->addToFunctions("(SELECT tax + e.amount) AS total");

    $selectList = array("e.id AS EID", "e.status AS status", "e.*", "i.id AS iv_id", "b.name AS b_name", "ea.name AS ea_name",
                        "c.name AS c_name", "p.description AS p_desc");
    $pdoDb->setSelectList($selectList);
    $result = $pdoDb->request("SELECT", "expense", "e");
    return $result;
}

global $LANG;

header("Content-type: text/xml");

$dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : 'DESC' ;
$sort = (isset($_POST['sortname']))  ? $_POST['sortname']  : 'id'   ;
$rp   = (isset($_POST['rp']))        ? $_POST['rp']        : '25'   ;
$page = (isset($_POST['page']))      ? $_POST['page']      : '1'    ;

$rows  = sql('', $dir, $sort, $rp, $page);
$count = sql('count',$dir, $sort, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";
foreach ($rows as $row) {
    $status_wording = ($row['status'] == 1 ? $LANG['paid'] : $LANG['not_paid']);

    $xml .= "<row id='".$row['id']."'>";
    $xml .=
        "<cell><![CDATA[
           <a class='index_table' title='$LANG[view] ".$row['p_desc']."' href='index.php?module=expense&view=details&id=".$row['id']."&action=view'>
             <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
           <a class='index_table' title='$LANG[edit] ".$row['p_desc']."' href='index.php?module=expense&view=details&id=".$row['id']."&action=edit'>
             <img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
        ]]></cell>";        
    $xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";        
    $xml .= "<cell><![CDATA[".siLocal::number_trim($row['amount'])."]]></cell>";
    $xml .= "<cell><![CDATA[".siLocal::number_trim($row['tax'])."]]></cell>";
    $xml .= "<cell><![CDATA[".siLocal::number_trim($row['amount'] + $row['tax'])."]]></cell>";
    $xml .= "<cell><![CDATA[".$row['ea_name']."]]></cell>";
    $xml .= "<cell><![CDATA[".$row['b_name']."]]></cell>";
    $xml .= "<cell><![CDATA[".$row['c_name']."]]></cell>";
    $xml .= "<cell><![CDATA[".$row['iv_id']."]]></cell>";
    $xml .= "<cell><![CDATA[".$status_wording."]]></cell>";
    $xml .= "</row>";        
}

$xml .= "</rows>";
echo $xml;
