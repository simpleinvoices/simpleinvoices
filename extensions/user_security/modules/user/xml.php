<?php
header("Content-type: text/xml");
global $LANG;

// @formatter:off
$start = (isset($_POST['start'])    ) ? $_POST['start']     : "0" ;
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "username" ;
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25" ;
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1" ;
// @formatter:on
function sql($type = '', $dir, $sort, $rp, $page) {
    global $LANG, $auth_session, $start;

    $valid_search_fields = array('username' , 'email', 'ur.name');

    // SC: Safety checking values that will be directly subbed in
    if (intval($start) != $start) {
        $start = 0;
    }
    if (intval($rp) != $rp) {
        $rp = 25;
    }

    // SQL Limit - start
    $start = (($page - 1) * $rp);
    $limit = "LIMIT $start, $rp";

    if ($type == "count") {
        $limit = "";
    }
    // SQL Limit - end

    if (!preg_match('/^(asc|desc)$/iD', $dir)) {
        $dir = 'ASC';
    }

    $where = "";
    $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
    $qtype = isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : null;
    if (!(empty($qtype) || empty($query))) {
        if (in_array($qtype, $valid_search_fields)) {
            $where = " AND $qtype LIKE :query ";
        } else {
            $qtype = null;
            $query = null;
        }
    }

    // Check that the sort field is OK
    $validFields = array('id', 'email', 'role', 'username');

    if (in_array($sort, $validFields)) {
        $sort = $sort;
    } else {
        $sort = "username";
    }

    // $sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
    $sql = "SELECT
                u.id,
                u.username,
                u.email,
                ur.name as role,
                (SELECT (CASE WHEN u.enabled = " .
                     ENABLED . " THEN '" . $LANG['enabled'] . "' ELSE '" . $LANG['disabled'] . "' END )) AS enabled,
                user_id
            FROM "      . TB_PREFIX . "user u
            LEFT JOIN " . TB_PREFIX . "user_role ur ON (u.role_id = ur.id)
            WHERE u.domain_id = :domain_id
                $where
            ORDER BY
                $sort $dir
            $limit";

    if (empty($query)) {
        $result = dbQuery($sql, ':domain_id', $auth_session->domain_id);
    } else {
        $result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
    }

    return $result;
}

$sth = sql('', $dir, $sort, $rp, $page);
$sth_count_rows = sql('count', $dir, $sort, $rp, $page);

$user = $sth->fetchAll(PDO::FETCH_ASSOC);

$count = $sth_count_rows->rowCount();

// echo sql2xml($customers, $count);
$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";
foreach ($user as $row) {
    $xml .= "<row id='" . (isset($row['iso']) ? $row['iso'] : "") . "'>";
    $xml .= "<cell><![CDATA[
       <a class='index_table' title='$LANG[view] " . (isset($row['name']) ? $row['name'] : "") . "'
          href='index.php?module=user&view=details&id=$row[id]&action=view'>
         <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
       </a>
       <a class='index_table' title='$LANG[edit] " . (isset($row['name']) ? $row['name'] : "") . "'
          href='index.php?module=user&view=details&id=$row[id]&action=edit'>
         <img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />
       </a>
    ]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['username'] . "]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['email']    . "]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['role']     . "]]></cell>";
    if ($row['enabled'] == $LANG['enabled']) {
        $xml .= "<cell><![CDATA[<img src='images/common/tick.png'  alt='" . $row['enabled'] .
                                  "' title='" . $row['enabled'] . "' />]]></cell>";
    } else {
        $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='" . $row['enabled'] .
                                  "' title='" . $row['enabled'] . "' />]]></cell>";
    }
    $xml .= "<cell><![CDATA[".$row['user_id']."]]></cell>";
    $xml .= "</row>";
}

$xml .= "</rows>";
echo $xml;
