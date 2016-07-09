<?php
global $help_image_path, $smarty, $LANG;

$smarty->assign('pageActive', 'backup');
$smarty->assign('active_tab', '#setting');

if (isset($_GET['op']) && $_GET['op'] == "backup_db") {
    $today = date("YmdGisa");
    $oBack = new BackupDb();
    $filename = "./tmp/database_backups/simple_invoices_backup_$today.sql"; // output file name
    $oBack->start_backup($filename);

    $txt=sprintf($LANG['backup_done'],$filename);

    $display_block = "<div class='si_center'>\n" .
                     "  <pre>\n" .
                     "    <table>{" . $oBack->getOutput() . "}</table>\n" .
                     "  </pre>\n" .
                     "</div>\n" .
                      $txt .
                     "<div class='si_help_div'>\n" .
                     "  <a class='cluetip' href='#'\n" .
                     "     rel='index.php?module=documentation&amp;view=view&amp;page=help_backup_database_fwrite' title='{$LANG['fwrite_error']}'>" .
                     "     <img src='{$help_image_path}help-small.png' alt=''/>\n" .
                     "    {$LANG['fwrite_error']}" .
                     "  </a>\n" .
                     "</div>\n\n";
} else {
    $display_block = "<div class='si_center'>\n" .
                     "  {$LANG['backup_howto']}\n" .
                     "  <div class='si_toolbar si_toolbar_top'>\n" .
                     "    <a href='index.php?module=options&amp;view=backup_database&amp;op=backup_db'>" .
                     "      <img src='./images/common/database_save.png' alt=''/>\n" .
                     "      {$LANG['backup_database_now']}\n" .
                     "    </a>\n" .
                     "  </div>\n" .
                     "  {$LANG['note']}: {$LANG['backup_note_to_file']}\n" .
                     "</div>\n" .
                     "<div class='si_help_div'>\n" .
                     "  <a class='cluetip' href='#'\n" .
                     "     rel='index.php?module=documentation&amp;view=view&amp;page=help_backup_database'\n" .
                     "     title='{$LANG['database_backup']}'>\n" .
                     "    <img src='./images/common/important.png' alt='' />\n" .
                     "    {$LANG['more_info']}\n" .
                     "  </a>\n" .
                     "</div>\n\n";
}

$oBack = null;
$smarty->assign('display_block', $display_block);
