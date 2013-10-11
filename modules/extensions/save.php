<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

if ($_POST['action'] == "register") {

  $sql = "INSERT INTO ".TB_PREFIX."extensions (`id`,`name`,`description`,`domain_id`,`enabled`) VALUES ( NULL, :name ,  :description , :domain_id , '0');";
  $sth = dbQuery($sql, ':name',$_POST['name'],':description',$_POST['description'],':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

} elseif ($_POST['action'] == "unregister") {

  $sql = "DELETE FROM ".TB_PREFIX."extensions WHERE id = :id AND domain_id = :domain_id; DELETE FROM ".TB_PREFIX."system_defaults WHERE extension_id = :id AND domain_id = :domain_id;";
  $sth = dbQuery($sql, ':id', $_POST['id'],':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

} else {

  die("Dude, this action is unknown to me!");
}

?>
