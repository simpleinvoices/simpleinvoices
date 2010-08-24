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

	$display_block .= "
	<tr><td><br /><br />".sprintf($LANG['backup_done'],$oBack->filename)."</td></tr>
	<tr><td><br />

			<a class=\"cluetip\" href=\"#\"	rel=\"index.php?module=documentation&amp;view=view&amp;page=help_backup_database_fwrite\" title=\"".$LANG['fwrite_error']."\"><img src=\"./images/common/help-small.png\" alt=\"\" />".$LANG['fwrite_error']."</a>


</td></tr></table>


";

}

else {

$display_block = "
<br />
<table align=\"center\">
<tr><td><br /><br />".$LANG['backup_howto']."</td></tr>
<tr><td align=\"center\"><br /><a href='index.php?module=options&amp;view=backup_database&amp;op=backup_db'>".$LANG['backup_database_now']."</a><br /><br /><br /></td></tr>
<tr><td>".$LANG['note'].": ".$LANG['backup_note_to_file']."</td></tr>
<tr>
<td>
<a class=\"cluetip\" href=\"#\"	rel=\"index.php?module=documentation&amp;view=view&amp;page=help_backup_database\" title=\"".$LANG['database_backup']."\"><img src=\"./images/common/important.png\" alt=\"\" /> <font color=\"red\"> ".$LANG['more_info']."</font></a>
</td>
</tr></table>

";
}

$smarty->assign('display_block', $display_block);
?>
