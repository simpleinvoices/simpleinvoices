<?php
//Stop direct browsing to this file
checkLogin();


$extension_id = $_GET['id'];
$extension_name = $_GET['name'];
$action = $_GET['action'];
$extension_desc = $_GET['description'];

if ($extension_id == null) {	// extension not yet registered

} else {
  //retrieve name and description from DB
  $sql = "SELECT name, description FROM ".TB_PREFIX."extensions WHERE id = :id;";
  $sth = dbQuery($sql,':id',$extension_id);
  $info = $sth->fetchAll(PDO::FETCH_ASSOC);

  $extension_name = $info[0]['name'];
  $extension_desc = $info[0]['description'];
  
  $sql = "SELECT * FROM ".TB_PREFIX."system_defaults WHERE extension_id = :id;";
  $sth = dbQuery($sql,':id',$extension_id);
  $info = $sth->fetchAll(PDO::FETCH_ASSOC);
  $count = count($info);
}

$smarty-> assign('id',$extension_id);
$smarty-> assign('action',$action);
$smarty-> assign('name',$extension_name);
$smarty-> assign('count',$count);
$smarty-> assign('description',$extension_desc);
$smarty-> assign('pageActive','extensions');
$smarty-> assign('active_tab','#settings');
?>
