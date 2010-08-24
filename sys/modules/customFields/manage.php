<?php
/*
* Script: manage.php
* 	new manage custom fields page
*
* Authors:
*	 Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

if(isset($_POST['save'])) {
	saveCustomField($_POST[plugin],$_POST[categorie],$_POST[name],$_POST[description]);
}

ini_set("display_errors","On");
//Note: If input is language specific it has to be in the form: {$LANG['value']} or {$LANG["value"]}

?>
