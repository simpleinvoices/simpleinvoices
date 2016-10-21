<?php

function sql($type = '', $dir, $sort, $rp, $page) {
    global $LANG, $pdoDb;

    $query = isset($_POST['query']) ? $_POST['query'] : null;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
    if (!empty($qtype) && !empty($query)) {
        $valid_search_fields = array('associated_table', 'flg_id', 'enabled');
        if (in_array($qtype, $valid_search_fields)) {
            $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
        }
    }
    $pdoDb->addSimpleWhere("domain_id", domain_id::get());

    if($type == "count") {
        $pdoDb->addToFunctions("count(*) AS count");
        $rows = $pdoDb->request("SELECT", "custom_flags");
        return $rows[0]['count'];
    }

    $start = (($page - 1) * $rp);
    $pdoDb->setLimit($rp, $start);

    $validFields = array('associated_table', 'flg_id');
    if (!in_array($sort, $validFields)) $sort = "associated_table";

    $dir = (preg_match('/^(asc|desc)$/iD', $dir) ? 'A' : 'D');
    $pdoDb->setOrderBy(array($sort, $dir));

    $ca = new CaseStmt("enabled", "wording_for_enabled");
    $ca->addWhen("=", ENABLED, $LANG['enabled']);
    $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
    $pdoDb->addToCaseStmts($ca);

    $pdoDb->setSelectList(array("associated_table", "flg_id", "field_label", "enabled", "field_help"));

    return $pdoDb->request("SELECT", "custom_flags");
}

global $LANG;

header("Content-type: text/xml");

// @formatter:off
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC";
$sort  = (isset($_POST['sortname']))  ? $_POST['sortname']  : "associated_table";
$rp    = (isset($_POST['rp']))        ? $_POST['rp']        : "25";
$page  = (isset($_POST['page']))      ? $_POST['page']      : "1";

$cflgs = sql(''     , $dir, $sort, $rp, $page);
$count = sql('count', $dir, $sort, $rp, $page);

$xml  = "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";
foreach ($cflgs as $row) {
    $id = htmlsafe($row['associated_table'] . ':' . $row['flg_id']);
    if ($row['enabled'] == ENABLED) {
        $enabled = $LANG['enabled'];
        $image = 'images/common/tick.png';
    } else {
        $enabled = $LANG['disabled'];
        $image = 'images/common/cross.png';
    }

    $xml .= "<row id='$id'>";
    $xml .= 
        "<cell><![CDATA[
           <a class='index_table' title='$LANG[view] $LANG[custom_flags_upper]'
              href='index.php?module=custom_flags&view=details&id=$id&action=view'>
             <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
           <a class='index_table' title='$LANG[edit] $LANG[custom_flags_upper]'
              href='index.php?module=custom_flags&view=details&id=$id&action=edit' >
             <img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
         ]]></cell>";
    $xml .= "<cell><![CDATA[" . htmlsafe($row['associated_table']) . "]]></cell>";
    $xml .= "<cell><![CDATA[" . htmlsafe($row['flg_id'])           . "]]></cell>";
    $xml .= "<cell><![CDATA[" . htmlsafe($row['field_label'])      . "]]></cell>";
    $xml .= "<cell><![CDATA[<img src='$image' alt='$enabled' title='$enabled' />]]></cell>";
    $xml .= "<cell><![CDATA[" . htmlsafe($row['field_help']) . "]]></cell>";
    $xml .= "</row>";
}
// @formatter:on
$xml .= "</rows>";

echo $xml;
