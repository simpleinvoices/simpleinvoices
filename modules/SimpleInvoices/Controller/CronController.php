<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class CronController
{
    protected $smarty;

    /**
     * TODO: Don't use globals!
     */
    public function __construct()
    {
        global $smarty;

        $this->smarty = $smarty;
    }

    public function addAction()
    {
        if ($_POST['op'] =='add' AND !empty($_POST['invoice_id']))
        {
            $cron                  = new \cron();
            $cron->domain_id       = \domain_id::get();
            $cron->invoice_id      = $_POST['invoice_id'];
            $cron->start_date      = $_POST['start_date'];
            $cron->end_date        = $_POST['end_date'];
            $cron->recurrence      = $_POST['recurrence'];
            $cron->recurrence_type = $_POST['recurrence_type'];
            $cron->email_biller    = $_POST['email_biller'];
            $cron->email_customer  = $_POST['email_customer'];
            $result                = $cron->insert();
            $saved                 = !empty($result) ? "true" : "false";
        }
        
        $invoices       = new \invoice();
        $invoices->sort = 'id';
        $invoice_all    = $invoices->select_all('count');
        
        $this->smarty->assign('invoice_all', $invoice_all);
        $this->smarty->assign('saved', $saved);
        $this->smarty->assign('pageActive', 'cron');
        $this->smarty->assign('subPageActive', 'cron_add');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function deleteAction()
    {
        global $dbh;
        
        $sql            = "DELETE FROM ".TB_PREFIX."cron WHERE id = :id AND domain_id = :domain_id";
        $sth            = dbQuery($sql, ':id', $_GET['id'], ':domain_id', \domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
        $saved          = !empty($sth) ? "true" : "false";
        $invoices       = new \invoice();
        $invoices->sort = 'id';
        $invoice_all    = $invoices->select_all('count');
        
        $this->smarty->assign('invoice_all', $invoice_all);
        $this->smarty->assign('saved', $saved);
        $this->smarty->assign('pageActive', 'cron');
        $this->smarty->assign('subPageActive', 'cron_manage');
        $this->smarty->assign('active_tab', '#money');
    }
    
    
    public function editAction()
    {
        $saved = false;
        
        if ($_POST['op'] =='edit' AND !empty($_POST['invoice_id']))
        {
            $edit                  = new \cron();
            $edit->domain_id       = \domain_id::get();
            $edit->id              = $_GET['id'];
            $edit->invoice_id      = $_POST['invoice_id'];
            $edit->start_date      = $_POST['start_date'];
            $edit->end_date        = $_POST['end_date'];
            $edit->recurrence      = $_POST['recurrence'];
            $edit->recurrence_type = $_POST['recurrence_type'];
            $edit->email_biller    = $_POST['email_biller'];
            $edit->email_customer  = $_POST['email_customer'];
            $result                = $edit->update();
            $saved                 = !empty($result) ? "true" : "false";
        }
        
        $invoices       = new \invoice();
        $invoices->sort = 'id';
        $invoice_all    = $invoices->select_all('count');
        
        $get_cron       = new \cron();
        $get_cron->id   = $_GET['id'];
        $cron           = $get_cron->select();
        
        $this->smarty->assign('invoice_all', $invoice_all);
        $this->smarty->assign('saved', $saved);
        $this->smarty->assign('cron', $cron);
        $this->smarty->assign('pageActive', 'cron');
        $this->smarty->assign('subPageActive', 'cron_edit');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function manageAction()
    {
        $sql             = "SELECT count(*) AS count FROM ".TB_PREFIX."cron WHERE domain_id = :domain_id";
        $sth             = dbQuery($sql, ':domain_id', \domain_id::get());
        $number_of_crons = $sth->fetch(\PDO::FETCH_ASSOC);
        $url             =  'index.php?module=cron&view=xml';
        
        //$smarty->assign("invoices", $invoices);
        $this->smarty->assign("number_of_crons", $number_of_crons);
        $this->smarty->assign('pageActive', 'cron');
        $this->smarty->assign('active_tab', '#money');
        $this->smarty->assign('url', $url);
    }
    
    /**
     * TODO: DOMAIN_ID = 1 is that correct?
     */
    public function runAction()
    {
        $cron            = new \cron();
        $cron->domain_id = 1;
        $message         = $cron->run();
        
        $this->smarty->assign('message', $message);
    }
    
    public function viewAction()
    {
        $saved = false;
        
        if ($_POST['op'] =='edit' AND !empty($_POST['invoice_id']))
        {
            $cron                  = new \cron();
            $cron->domain_id       = \domain_id::get();
            $cron->invoice_id      = $_POST['invoice_id'];
            $cron->start_date      = $_POST['start_date'];
            $cron->end_date        = $_POST['end_date'];
            $cron->recurrence      = $_POST['recurrence'];
            $cron->recurrence_type = $_POST['recurrence_type'];
            $cron->email_biller    = $_POST['email_biller'];
            $cron->email_customer  = $_POST['email_customer'];
            $result                = $cron->insert();
            $saved                 = !empty($result) ? "true" : "false";
        }
        
        //$invoiceobj = new invoice();
        //$invoice_all = $invoiceobj->get_all();
        
        $get_cron      = new \cron();
        $get_cron->id = $_GET['id'];
        $cron         =  $get_cron->select();
        
        //$smarty -> assign('invoice_all',$invoice_all);
        $this->smarty->assign('saved', $saved);
        $this->smarty->assign('cron', $cron);
        $this->smarty->assign('pageActive', 'cron');
        $this->smarty->assign('subPageActive', 'cron_view');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function xmlAction()
    {
        global $LANG;
        
        header("Content-type: text/xml");
        
        //$start        = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
        $dir            = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
        $sort           = (isset($_POST['sortname'])) ? $_POST['sortname'] : "id" ;
        $rp             = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
        $page           = (isset($_POST['page'])) ? $_POST['page'] : "1" ;
        //$sql          = "SELECT * FROM ".TB_PREFIX."cron LIMIT $start, $limit";
        $cron           = new \cron();
        $cron->sort     = $sort;
        $crons          = $cron->select_all('', $dir, $rp, $page);
        $sth_count_rows = $cron->select_all('count', $dir, $rp, $page);
        
        unset($cron);
        
        $count = $sth_count_rows;
        
        $xml  = "";
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($crons as $row) {
            $row['email_biller_nice'] = $row['email_biller']==1?$LANG['yes']:$LANG['no'];
            $row['email_customer_nice'] = $row['email_customer']==1?$LANG['yes']:$LANG['no'];
            $xml .= "<row id='".$row['id']."'>";
            $xml .= "<cell><![CDATA[
            <a class='index_table' title='$LANG[view] "  .$row['name']."' href='index.php?module=cron&view=view&id=$row[id]'>  <img src='images/common/view.png'   height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            <a class='index_table' title='$LANG[edit] "  .$row['name']."' href='index.php?module=cron&view=edit&id=$row[id]'>  <img src='images/common/edit.png'   height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            <a class='index_table' title='$LANG[delete] ".$row['name']."' href='index.php?module=cron&view=delete&id=$row[id]'><img src='images/common/delete.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            ]]></cell>";
            $xml .= "<cell><![CDATA[".$row['index_name']."]]></cell>";
            #$xml .= "<cell><![CDATA[".\siLocal::date($row['start_date'])."]]></cell>";
            #$xml .= "<cell><![CDATA[".\siLocal::date($row['end_date'])."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['start_date']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['end_date']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['recurrence']." ".$row['recurrence_type']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['email_biller_nice']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['email_customer_nice']."]]></cell>";
            $xml .= "</row>";
        }
        
        $xml .= "</rows>";
        
        echo $xml;
    }
}