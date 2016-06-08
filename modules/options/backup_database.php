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


	$txt=sprintf($LANG['backup_done'],$oBack->filename);

	$display_block =<<<EOF
<div class="si_center">
<pre>
<table>
	{$oBack->output}
</table>
</pre>
</div>
$txt
	<div class="si_help_div">
			<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database_fwrite" title="{$LANG['fwrite_error']}"><img src="{$help_image_path}help-small.png" alt="" />{$LANG['fwrite_error']}</a>
	</div>

EOF;

}

else {

$display_block = <<<EOF
<div class="si_center">
{$LANG['backup_howto']}

		<div class='si_toolbar si_toolbar_top'>
			
			<a href='index.php?module=options&amp;view=backup_database&amp;op=backup_db'><img src="./images/common/database_save.png" alt=""/>{$LANG['backup_database_now']}</a>
		</div>

{$LANG['note']}: {$LANG['backup_note_to_file']}
</div>

	<div class="si_help_div">
		<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database" title="{$LANG['database_backup']}"><img src="./images/common/important.png" alt="" />{$LANG['more_info']}</a>
	</div>
EOF;
}

$smarty->assign('display_block', $display_block);
?>
