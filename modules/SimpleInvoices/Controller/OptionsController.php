<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class OptionsController
{
    protected $menu;

    protected $smarty;

    /**
     * TODO: Don't use globals!
     */
    public function __construct()
    {
        global $smarty;

        $this->smarty = $smarty;
    }
    
    /**
     * TODO: Remove some how the output as the controller should only care about logic
     */
    public function backupDatabaseAction()
    {
        global $LANG;
        
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
			<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_backup_database_fwrite" title="{$LANG['fwrite_error']}"><img src="./images/common/help-small.png" alt="" />{$LANG['fwrite_error']}</a>
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
        
        $this->smarty->assign('pageActive', 'backup');
        $this->smarty->assign('active_tab', '#setting');
        $this->smarty->assign('display_block', $display_block);
    }
    
    public function databaseSqlpatchesAction()
    {
        include('./include/sql_patches.php');
    }
    
    public function helpAction()
    {
        // TODO: File was empty. Shall it be removed?
    }
    
    public function indexAction()
    {
        $this->smarty->assign('pageActive', 'setting');
        $$this->smarty->assign('active_tab', '#setting');
    }
    
    public function manageCronlogAction()
    {
        $get_cronlog = new \cronlog();
        $cronlogs    = $get_cronlog->select();
        
        $this->smarty->assign("cronlogs", $cronlogs);
        $this->smarty->assign('pageActive', 'options');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function manageSqlpatchesAction()
    {
        $this->smarty->assign("patches", getSQLPatches());
        $this->smarty->assign('pageActive', 'sqlpatch');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function sanityCheckAction()
    {
        // TODO: File was empty. Shall we remove?
    }
}