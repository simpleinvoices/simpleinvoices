<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$domain_id = domain_id::get();

if ($_POST['action'] == "register") {

  $sql = "INSERT INTO ".TB_PREFIX."extensions (`id`,`name`,`description`,`domain_id`,`enabled`) VALUES ( NULL, :name ,  :description , :domain_id , '0');";
  $sth = $db->query($sql, ':name',$_POST['name'],':description',$_POST['description'],':domain_id', $domain_id);

} elseif ($_POST['action'] == "unregister") {

  $sql = "DELETE FROM ".TB_PREFIX."extensions WHERE id = :id AND domain_id = :domain_id; DELETE FROM ".TB_PREFIX."system_defaults WHERE extension_id = :id AND domain_id = :domain_id;";
  $sth = $db->query($sql, ':id', $_POST['id'],':domain_id', $domain_id);

} else {

  die("Dude, this action is unknown to me!");
}

?>
