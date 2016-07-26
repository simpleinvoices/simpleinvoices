<?php
header("Content-type: text/xml");

global $LANG, $pdoDb;

// @formatter:off
$start = (isset($_POST['start'])    ) ? $_POST['start']     : "0" ;
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "username" ;
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25" ;
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1" ;
// @formatter:on

function selectRecords(PdoDb $pdoDb, $type, $dir, $sort, $rp, $page) {
    global $LANG, $start, $auth_session;

    $domain_id = $auth_session->domain_id;

    $pdoDb->clearAll();

    $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : "";
    $qtype = isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : "";
    if (!empty($qtype) && !empty($query)) {
        if (in_array($qtype, array('username' , 'email', 'ur.name'))) {
            $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", $query, false, "AND"));
        }
    }

    $pdoDb->addSimpleWhere("domain_id", $domain_id);

    $count = ($type == 'count');
    if ($count) {
        $pdoDb->addToFunctions("count(*) AS count");
        $rows = $pdoDb->request("SELECT", "user");
        return $rows[0]['count'];
    }

    $dir = (!preg_match('/^desc$/iD', $dir) ? "D" : "A");
    if (!in_array($sort, array('username', 'email', 'role'))) $sort = "username";
    $pdoDb->setOrderBy(array($sort, $dir));

    if (intval($start) != $start) $start = 0;
    if (intval($rp) != $rp) $rp = 25;
    $start = (($page - 1) * $rp);
    $pdoDb->setLimit($rp, $start);

    $list = array("id", "username", "email", "user_id", "enabled", "ur.name AS role_name");
    $pdoDb->setSelectList($list);

    $caseStmt = new CaseStmt("u.enabled", "=", ENABLED, $LANG['enabled'], $LANG['disabled'], "enabled");
    $pdoDb->addToCaseStmts($caseStmt);

    $join = new Join("LEFT", "user_role", "ur");
    $join->addSimpleItem("ur.id", new DbField("role_id"));
    $pdoDb->addToJoins($join);

    $rows = $pdoDb->request("SELECT", "user", "u");

    $result = array();
    foreach($rows as $row) {
        if ($row['role_name'] == 'customer') {
            $cid = $row['user_id'];
            $pdoDb->addSimpleWhere("domain_id", $domain_id, "AND");
            $pdoDb->addSimpleWhere("id", $cid);
            $cust = $pdoDb->request("SELECT", "customers");
            $uid = $cid . " - " . (empty($cust[0]['name']) ? "Unknown Customer" : $cust[0]['name']);
        } else if ($row['role_name'] == 'biller') {
            $bid = $row['user_id'];
            $pdoDb->addSimpleWhere("domain_id", $domain_id, "AND");
            $pdoDb->addSimpleWhere("id", $bid);
            $bilr = $pdoDb->request("SELECT", "biller");
            $uid = $bid . " - " . (empty($bilr[0]['name']) ? "Unknown Biller" : $bilr[0]['name']);
        } else {
            $uid = "0 - Standard User";
        }
        $row['uid'] = $uid;
        $result[] = $row;
    }
    return $result;
}
$rows  = selectRecords($pdoDb, '', $dir, $sort, $rp, $page);
$count = selectRecords($pdoDb, 'count', $dir, $sort, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";
foreach ($rows as $row) {
    $xml .= "<row id='" . $row['id'] . "'>";
    $xml .= "<cell><![CDATA[
               <a class='index_table' title='" . $LANG['view'] . " " . (isset($row['name']) ? $row['name'] : "") . "'
                  href='index.php?module=user&view=details&id=" . $row['id'] . "&action=view'>
                 <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
               </a>
               <a class='index_table' title='" . $LANG['edit'] . " " . (isset($row['name']) ? $row['name'] : "") . "'
                  href='index.php?module=user&view=details&id=" . $row['id'] . "&action=edit'>
                 <img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />
               </a>
            ]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['username'] . "]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['email']    . "]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['role_name']. "]]></cell>";
    if ($row['enabled'] == $LANG['enabled']) {
        $xml .= "<cell><![CDATA[<img src='images/common/tick.png'  alt='" . $row['enabled'] .
                                  "' title='" . $row['enabled'] . "' />]]></cell>";
    } else {
        $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='" . $row['enabled'] .
                                  "' title='" . $row['enabled'] . "' />]]></cell>";
    }
    $xml .= "<cell><![CDATA[" . $row['uid'] ."]]></cell>";
    $xml .= "</row>";
}

$xml .= "</rows>";
echo $xml;
