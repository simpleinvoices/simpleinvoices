<?php
include('./include/include_main.php');


if ($_GET[op] == "backup_db") {
include_once("./include/backup.lib.php");
$today = date("YmdGisa");
$oBack    = new backup_db;
$oBack->filename = "./database_backups/simple_invoices_backup_$today.sql"; // output file name
$oBack->start_backup();
$display_block ="

<b>Database Backup</b>

<hr></hr>

<table align=center>";

$display_block .= $oBack->output; 

$display_block .= "<tr><td><br><br>Your database has now been backed up to the file database_backups/simple_invoices_backup_$today.sql, you can now continue using Simple Invoices as normal</td></tr>
<tr><td><br><a href=\"./documentation/info_pages/backup_database_fwrite.html\" rel=\"ibox&height=400\"><font color=\"red\">Got fwrite errors?</a></font></td></tr></table>"; 

}

else {

$display_block ="

<b>Database Backup</b>
 <hr></hr>
<table align=center>
<tr><td><br><br>To make a backup of your Simple Invoices database click the below link</td></tr>
<tr><td align=center><br><a href='index.php?module=options&view=backup_database&op=backup_db'>BACKUP DATABASE NOW</a><br><br><br></td></tr>
<tr><td>Note: this will backup your database to a file into your database_backups directory</td></tr>
<tr><td><a href=\"./documentation/info_pages/backup_database.html\" rel=\"ibox&height=400\"><font color=\"red\"><img src=\"./images/common/important.png\"></img>Extra information</font></td></tr></table>

";

}






?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- CSS -->
    <script type="text/javascript" src="./include/jquery.js"></script>
<script type="text/javascript" src="./src/include/js/ibox.js"></script>
<link rel="stylesheet" href="./src/include/css/ibox.css" type="text/css"  media="screen"/>

</head>
<body>

<?php 
	echo $display_block; 
?>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
