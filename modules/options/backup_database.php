<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed

checkLogin();


if ($_GET['op'] == "backup_db") {
include_once("./include/backup.lib.php");
$today = date("YmdGisa");
$oBack    = new backup_db;
$oBack->filename = "./database_backups/simple_invoices_backup_$today.sql"; // output file name
$oBack->start_backup();
$display_block ="

<h3>Database Backup</h3>
<hr />

<table align=center>";

$display_block .= $oBack->output; 

$display_block .= <<<EOD
<tr><td><br /><br />Your database has now been backed up to the file database_backups/simple_invoices_backup_$today.sql, you can now continue using Simple Invoices as normal</td></tr>
<tr><td><br /><a href="docs.php?t=help&p=backup_database_fwrite" rel="gb_page_center[450, 450]"><font color="red">Did you get fwrite errors?</a></font></td></tr></table>
EOD;

}

else {

$display_block = <<<EOD

<h3>Database Backup</h3>
<hr />
<table align=center>
<tr><td><br /><br />To make a backup of your Simple Invoices database click the below link</td></tr>
<tr><td align=center><br><a href='index.php?module=options&view=backup_database&op=backup_db'>BACKUP DATABASE NOW</a><br><br><br></td></tr>
<tr><td>Note: this will backup your database to a file into your database_backups directory</td></tr>
<tr><td><a href="docs.php?t=help&p=backup_database" rel="gb_page_center[450, 450]"><font color="red"><img src="./images/common/important.png"></img>Extra information</font></td></tr></table>

EOD;
}


	echo $display_block; 
?>
