<?php
header("Content-type: text/xml");

function sql($type = '', $dir, $sort, $rp, $page, $start, $LANG, $domain_id) {
    $valid_search_fields = array('pt_id', 'pt_description');

    // @formatter:off
    if (intval($start) != $start) $start = 0;
    if (intval($rp)    != $rp   ) $rp = 25;

    $start = (($page - 1) * $rp);

    if ($type == "count") $limit = "";
    else                  $limit = "LIMIT $start, $rp";

    if (!preg_match('/^(asc|desc)$/iD', $dir)) $dir = 'ASC';

    $where = "";
    $query = isset($_POST['query']) ? $_POST['query'] : null;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
    if (!(empty($qtype) || empty($query))) {
        if (in_array($qtype, $valid_search_fields)) {
            $where = " AND $qtype LIKE :query ";
        } else {
            $qtype = null;
            $query = null;
        }
    }

    // Check that the sort field is OK
   $validFields = array('pt_id', 'pt_description', 'enabled');
    if (in_array($sort, $validFields)) {
        $sort = $sort;
    } else {
        $sort = "pt_description";
    }

    $sql = "SELECT pt_id, pt_description,
                   (SELECT (CASE  WHEN pt_enabled = 0 THEN '" . $LANG['disabled'] . "' ELSE '" . $LANG['enabled'] . "' END )) AS enabled
            FROM " . TB_PREFIX . "payment_types
            WHERE domain_id = :domain_id
                  $where
            ORDER BY $sort $dir
            $limit";

    if (empty($query)) {
        $result = dbQuery($sql, ':domain_id', $domain_id);
    } else {
        $result = dbQuery($sql, ':domain_id', $domain_id, ':query', "%$query%");
    }
    // @formatter:on

    return $result;
}

global $LANG, $auth_session;

// @formatter:off
$start = (isset($_POST['start'])    ) ? $_POST['start']     : "0";
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC";
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "pt_description";
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25";
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1";

$sth = sql('', $dir, $sort, $rp, $page, $start, $LANG, $auth_session->domain_id);
$sth_count_rows = sql('count', $dir, $sort, $rp, $page, $start, $LANG, $auth_session->domain_id);

$payment_types = $sth->fetchAll(PDO::FETCH_ASSOC);
$count = $sth_count_rows->rowCount();

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($payment_types as $row) {
    $pt_desc = $row['pt_description'];
    $pt_id = $row['pt_id'];
    $enabled = $row['enabled'];
    $title = $LANG['view'] . " " . $LANG['payment_type'] . " " . $pt_desc;
    $pic = ($enabled == $LANG['enabled'] ? "images/common/tick.png" : "images/common/cross.png");

    $xml .= "<row id='$pt_id'>";
    $xml .= "<cell><![CDATA[
        <a class='index_table' title='$title'
           href='index.php?module=payment_types&view=details&id=$pt_id&action=view'>
          <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
        </a>
        <a class='index_table' title='$title'
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
