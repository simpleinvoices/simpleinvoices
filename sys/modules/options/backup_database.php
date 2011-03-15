<?php

//stop the direct browsing to this file - let index.php handle which files get displayed

//checkLogin();

$smarty -> assign('pageActive', 'backup');
$smarty -> assign('active_tab', '#setting');

$display_block = "
<br />
<table align=\"center\">
<tr><td><br /><br />".$LANG['backup_howto']."</td></tr>
<tr><td align=\"center\"><br /><a href='index.php?module=export&view=db&format=file&filetype=sql'>".$LANG['backup_database_now']."</a><br /><br /><br /></td></tr>
<tr>
<td>
</td>
</tr></table>

";

$smarty->assign('display_block', $display_block);
?>
