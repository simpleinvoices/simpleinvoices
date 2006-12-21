<?php
include('./include/include_main.php');








if ($_GET[op] == "backup_db") {
include_once("./include/backup.lib.php");
$today = date("YmdGisa");
$oBack    = new backup_db;
$oBack->filename = "./database_backups/simple_invoices_backup_$today.sql"; // output file name
$oBack->start_backup();
$display_block ="<br>
<table align=center><tr><td align=center><i>Database Backup</i><br></td></tr>";
$display_block .= $oBack->output; 
$display_block .= "<tr><td><br><br>Your database has now been backed up to the file database_backups/simple_invoices_backup_$today.sql, you can now continue using Simple Invoices as normal</td></tr>
<tr><td><br><a href=\"./documentation/text/backup_database_fwrite.html?keepThis=true&TB_iframe=true&height=300&width=500\" title=\"Info :: Database backup - fwrite\" class=\"thickbox\"><font color=\"red\">Got fwrite errors?</a></font></td></tr></table>"; 

}

else {

$display_block ="
<table align=center><tr><td align=center><i>Database Backup</i></td></tr>
<tr><td><br><br>To make a backup of your Simple Invoices database click the below link</td></tr>
<tr><td align=center><br><a href='?op=backup_db'>BACKUP DATABASE NOW</a><br><br><br></td></tr>
<tr><td>Note: this will backup your database to a file into your database_backups directory</td></tr>
<tr><td><a href=\"./documentation/text/backup_database.html?keepThis=true&TB_iframe=true&height=300&width=500\" title=\"Info :: Database backup\" class=\"thickbox\"><font color=\"red\">Extra information</font></td></tr></table>

";

}






?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- CSS -->
    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/jquery.thickbox.js"></script>


<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>


<title>Simple Invoices - Backup database
</title>
</head>
<body>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/jquery.thickbox.css">
<br>
<div id="container">
<div id="header"></div>
<?php echo $display_block; ?>
<div id="footer"></div>
</div>
</div>

</body>

