<?php
header("Content-type: text/xml");

// @formatter:off
$start = (isset($_POST['start']))     ? $_POST['start']     : "0";
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC";
$sort  = (isset($_POST['sortname']))  ? $_POST['sortname']  : "associated_table";
$rp    = (isset($_POST['rp']))        ? $_POST['rp']        : "25";
$page  = (isset($_POST['page']))      ? $_POST['page']      : "1";
// @formatter:on

function sql($type = '', $dir, $sort, $rp, $page) {
    global $config;
    global $LANG;
    global $auth_session;
    
    $valid_search_fields = array('associated_table', 'flg_id', 'enabled');
    
    // SC: Safety checking values that will be directly subbed in
    if (!isset($start) || intval($start) != $start) {
        $start = 0;
    }
    
    if (!isset($limit) || intval($limit) != $limit) {
        $limit = 25;
    }
    
    /* SQL Limit - start */
    $start = (($page - 1) * $rp);
    $limit = "LIMIT $start, $rp";
    
    if ($type == "count") {
        unset($limit);
    }
    /* SQL Limit - end */
    
    if (!preg_match('/^(asc|desc)$/iD', $dir)) {
        $dir = 'ASC';
    }
    
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
    $validFields = array('associated_table', 'flg_id');
    
    if (!in_array($sort, $validFields)) {
        $sort = "associated_table";
    }
    
    // @formatter:off
    $sql = "SELECT associated_table,
                   flg_id,
                   field_label,
                   enabled,
                   (SELECT (CASE  WHEN enabled = 0 THEN \"".$LANG['disabled']."\" ELSE \"".$LANG['enabled']."\" END )) AS wording_for_enabled,
                   field_help
            FROM " . TB_PREFIX . "custom_flags
            WHERE domain_id = :domain_id
                  $where
            ORDER BY 
                  $sort $dir 
                  ".(isset($limit) ? $limit:'');
    // @formatter:on
    if (empty($query)) {
        $result = dbQuery($sql, ':domain_id', $auth_session->domain_id);
    } else {
        $result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
    }
    
    return $result;
}

$sth = sql('', $dir, $sort, $rp, $page);
$sth_count_rows = sql('count', $dir, $sort, $rp, $page);

$cflgs = $sth->fetchAll(PDO::FETCH_ASSOC);
$count = $sth_count_rows->rowCount();

$xml = "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

// @formatter:off
foreach ($cflgs as $row) {
    $id = htmlsafe($row['associated_table'] . ':' . $row['flg_id']);
    $xml .= "<row id=\"$id\" >";
    $xml .= "  <cell><![CDATA[
    		   <a class=\"index_table\" title=\"".$LANG['view']." ".$LANG['custom_flags_upper']."\" 
                  href=\"index.php?module=custom_flags&view=details&id=$id&action=view\">
                  <img src=\"images/common/view.png\" height=\"16\" border=\"-5px\" padding=\"-4px\" valign=\"bottom\" />
               </a>
    		   <a class=\"index_table\" title=\"".$LANG['edit']." ".$LANG['custom_flags_upper']."\"
                  href=\"index.php?module=custom_flags&view=details&id=$id&action=edit\">
                  <img src=\"images/common/edit.png\" height=\"16\" border=\"-5px\" padding=\"-4px\" valign=\"bottom\" />
               </a>
    	  ]]></cell>";
    $xml .= "  <cell><![CDATA[" . htmlsafe($row['associated_table']) . "]]></cell>";
    $xml .= "  <cell><![CDATA[" . htmlsafe($row['flg_id'])           . "]]></cell>";
    $xml .= "  <cell><![CDATA[" . htmlsafe($row['field_label'])      . "]]></cell>";
    if ($row['enabled']==1) {
        $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$LANG['enabled']."' title='".$LANG['enabled']."' />]]></cell>";
    }
    else {
        $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$LANG['disabled']."' title='".$LANG['disabled']."' />]]></cell>";
    }
    $xml .= "  <cell><![CDATA[" . htmlsafe($row['field_help'])       . "]]></cell>";
    $xml .= "</row>";
}
// @formatter:on
$xml .= "</rows>";

echo $xml;
