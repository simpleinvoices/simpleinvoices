<?php

//stop the direct browsing to this file - let index.php handle which files get displayed

//checkLogin();

$smarty -> assign('pageActive', 'backup');
$smarty -> assign('active_tab', '#setting');

if ($_GET['op'] == "backup_db") {


	$today = date("YmdGisa");
	$oBack    = new backup_db;
	$oBack->filename = "./tmp/database_backups/simple_invoices_backup_$today.sql"; // output file name
	$oBack->start_backup();

	$display_block ="

	<br />

	<table align='center'>";

	$display_block .= $oBack->output; 

	$display_block .= <<<EOD
	<tr><td><br /><br />Your database has now been backed up to the file tmp/database_backups/simple_invoices_backup_$today.sql, you can now continue using Simple Invoices as normal</td></tr>
	<tr><td><br />

			<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database_fwrite" title="$LANG[fwrite_error]"><img src="./images/common/help-small.png" alt="" />$LANG[fwrite_error]</a>


</td></tr></table>


EOD;

}

else {

$display_block = <<<EOD
<br />
<table align="center">
<tr><td><br /><br />To make a backup of your Simple Invoices database click the below link</td></tr>
<tr><td align="center"><br /><a href='index.php?module=options&amp;view=backup_database&amp;op=backup_db'>BACKUP DATABASE NOW</a><br /><br /><br /></td></tr>
<tr><td>Note: this will backup your database to a file into your database_backups directory</td></tr>
<tr>
<td>
<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database" title="Database Backup"><img src="./images/common/important.png" alt="" /> <font color="red"> Extra information</font></a>
</td>
</tr></table>

EOD;
}

$smarty->assign('display_block', $display_block);
?>
