<?php
header("Content-type: text/xml");

global $auth_session, $LANG;

function sql($type, $dir, $sort, $rp, $page, $LANG, $domain_id) {
    $valid_search_fields = array('pref_id', 'pref_description');

    // @formatter:off
    if (intval($rp) != $rp) $rp = 25;

    $start = (($page - 1) * $rp);

    if ($type == "count") {
        $limit = "";
    } else {
        $limit = "LIMIT $start, $rp";
    }

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
    $validFields = array('pref_id', 'pref_description', 'enabled');

    if (in_array($sort, $validFields)) {
        $sort = $sort;
    } else {
        $sort = "pref_description";
    }

    $sql = "SELECT pref_id,
                   pref_description,
                   locale,
                   language,
                   (SELECT (CASE WHEN pref_enabled = 0 THEN '" .
                            $LANG['disabled'] . "' ELSE '" .
                            $LANG['enabled' ] . "' END )) AS enabled
            FROM " . TB_PREFIX . "preferences
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

// @formatter:off
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC";
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "pref_description";
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25";
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1";
// @formatter:on

$domain_id = $auth_session->domain_id;
$sth = sql('', $dir, $sort, $rp, $page, $LANG, $domain_id);
$sth_count_rows = sql('count', $dir, $sort, $rp, $page, $LANG, $domain_id);

$preferences = $sth->fetchAll(PDO::FETCH_ASSOC);
$count = $sth_count_rows->rowCount();

$xml  = '';
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($preferences as $row) {
    $pref_desc = $row['pref_description'];
    $pref_id   = $row['pref_id'];
    $language  = $row['language'];
    $locale    = $row['locale'];
    $enabled   = $row['enabled'];
    if ($enabled == $LANG['enabled']) {
        $pic = "images/common/tick.png";
    } else {
        $pic = "images/common/cross.png";
    }
    $title = $LANG['view'] . " " . $LANG['preference'] . " " . $pref_desc;
    $xml .= "<row id='$pref_id'>";
    $xml .= "<cell><![CDATA[
            <a class='index_table' title='$title'
               href='index.php?module=preferences&view=details&id=$pref_id&action=view'>
              <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
            </a>
            <a class='index_table' title='$title'
               href='index.php?module=preferences&view=details&id=$pref_id&action=edit'>
              <img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />
            </a>
            ]]></cell>";
    $xml .= "<cell><![CDATA[$pref_desc]]></cell>";
    $xml .= "<cell><![CDATA[$language]]></cell>";
    $xml .= "<cell><![CDATA[$locale]]></cell>";
    $xml .= "<cell><![CDATA[<img src='$pic' alt='$enabled' title='$enabled' />]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;

