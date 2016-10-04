<?php
header("Content-type: text/xml");
global $LANG;

// @formatter:off
$dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
$sort = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "ap.id" ;
$rp   = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25" ;
$page = (isset($_POST['page'])     ) ? $_POST['page']      : "1" ;
// @formatter:on

function sql($type = '', $dir, $sort, $rp, $page) {
    global $LANG, $pdoDb;

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $pdoDb->addSimpleWhere("ap.ac_inv_id", $id, "AND");
    } elseif (!empty($_GET['c_id'])) {
        $id = $_GET['c_id'];
        $pdoDb->addSimpleWhere("c.id", $id, "AND");
    }
    
    $query = isset($_POST['query']) ? $_POST['query'] : null;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
    if (!empty($qtype) && !empty($query)) {
        $valid_search_fields = array('ap.id','b.name', 'c.name');
        if ( in_array($qtype, $valid_search_fields) ) {
            $pdoDb->addSimpleWhere($qtype, "%$query%", "AND");
        }
    }
    $pdoDb->addSimpleWhere("ap.domain_id", domain_id::get());

    $jn = new Join("INNER", "invoices", "iv");
    $oc = new OnClause();
    $oc->addSimpleItem("ap.ac_inv_id", new DbField("iv.id"), "AND");
    $oc->addSimpleItem("ap.domain_id", new DbField("iv.domain_id"));
    $jn->setOnClause($oc);
    $pdoDb->addToJoins($jn);

    $jn = new Join("INNER", "customers", "c");
    $oc = new OnClause();
    $oc->addSimpleItem("c.id", new DbField("iv.customer_id"), "AND");
    $oc->addSimpleItem("c.domain_id", new DbField("iv.domain_id"));
    $jn->setOnClause($oc);
    $pdoDb->addToJoins($jn);

    $jn = new Join("INNER", "biller", "b");
    $oc = new OnClause();
    $oc->addSimpleItem("b.id", new DbField("iv.biller_id"), "AND");
    $oc->addSimpleItem("b.domain_id", new DbField("iv.domain_id"));
    $jn->setOnClause($oc);
    $pdoDb->addToJoins($jn);
    
    if($type =="count") {
        $pdoDb->addToFunctions("COUNT(*) AS count");
        $rows = $pdoDb->request("SELECT", "payment", "ap");
        return $rows[0]['count'];
    }

    $jn = new Join("INNER", "preferences", "pr");
    $oc = new OnClause();
    $oc->addSimpleItem("pr.pref_id", new DbField("iv.preference_id"), "AND");
    $oc->addSimpleItem("pr.domain_id", new DbField("iv.domain_id"));
    $jn->setOnClause($oc);
    $pdoDb->addToJoins($jn);

    $jn = new Join("INNER", "payment_types", "pt");
    $oc = new OnClause();
    $oc->addSimpleItem("pt.pt_id", new DbField("ap.ac_payment_type"), "AND");
    $oc->addSimpleItem("pt.domain_id", new DbField("ap.domain_id"));
    $jn->setOnClause($oc);
    $pdoDb->addToJoins($jn);
    
    $start = (($page-1) * $rp);
    $pdoDb->setLimit($rp, $start);

    if (in_array($sort, array('ap.id', 'ap.ac_inv_id', 'description'))) {
        if (!preg_match('/^(asc|desc)$/iD', $dir)) $dir = 'D';
        $oc = new OrderBy($sort, $dir);
    } else {
        $oc = new OrderBy("description");
    }

    $fn = new FunctionStmt("DATE_FORMAT", "ac_date,'%Y-%m-%d'");
    $se = new Select($fn, null, null, "date");
    $pdoDb->addToSelectStmts($se);

    $pdoDb->setOrderBy($oc);

    $list = array("ap.*", "c.name as cname", "b.name as bname", "pt.pt_description AS description",
                  "ap.ac_notes AS notes");
    $pdoDb->setSelectList($list);
    
    $fn = new FunctionStmt("CONCAT", "pr.pref_inv_wording,' ',iv.index_id");
    $se = new Select($fn, null, null, "index_name");
    $pdoDb->addToSelectStmts($se);

    $result = $pdoDb->request("SELECT", "payment", "ap");
    return $result;
}

$payments = sql(     '', $dir, $sort, $rp, $page);
$count    = sql('count', $dir, $sort, $rp, $page);

// @formatter:off
$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($payments as $row) {
    $notes = si_truncate($row['notes'],'13','...');
    $xml .= "<row id='$row[id]'>";
    $xml .= 
        "<cell><![CDATA[
           <a class='index_table' title='$LANG[view] $LANG[payment_id] $row[id]'
              href='index.php?module=payments&view=details&id=$row[id]&action=view'>
             <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
           <a class='index_table' title='$LANG[print_preview_tooltip] $row[id]'
              href='index.php?module=payments&view=print&id=$row[id]'>
             <img src='images/common/printer.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
           <a class='index_table' title='$LANG[email] $LANG[payment_id] $row[id]'
              href='index.php?module=payments&view=email&stage=1&id=$row[id]'>
             <img src='images/common/mail-message-new.png' class='action'>
           </a>
         ]]></cell>";
    $xml .= "<cell><![CDATA[$row[id]]]></cell>";
    $xml .= "<cell><![CDATA[$row[index_name]]]></cell>";
    $xml .= "<cell><![CDATA[$row[cname]]]></cell>";
    $xml .= "<cell><![CDATA[$row[bname]]]></cell>";
    $xml .= "<cell><![CDATA[".siLocal::number($row['ac_amount'])."]]></cell>";
    $xml .= "<cell><![CDATA[$notes]]></cell>";
    $xml .= "<cell><![CDATA[$row[description]]]></cell>";
    $xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";
// @formatter:on

echo $xml;
