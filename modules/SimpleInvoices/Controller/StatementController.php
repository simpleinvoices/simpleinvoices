<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class StatementController
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
    
    /**
     * TODO: What happens when stage is not present? Right now it shows a blank screen
     *       Also validate the stage input.
     */
    public function emailAction()
    {
        $biller_id      = $_GET['biller_id'];
        $customer_id    = $_GET['customer_id'];
        $filter_by_date = $_GET['filter_by_date'];
        
        if ( $filter_by_date =="yes" )
        {
            $start_date = $_GET['start_date'];
            $end_date   = $_GET['end_date'];
        }
        
        $show_only_unpaid = $_GET['show_only_unpaid'];
        $get_format       = $_GET['format'];
        $get_file_type    = $_GET['filetype'];
        $biller           = getBiller($_GET['biller_id']);
        $customer         = getCustomer($_GET['customer_id']);
        
        #create PDF name
        if ($_GET['stage'] == 2 ) {
            #get the invoice id
            $export = new \export();
            $export->format           = 'pdf';
            $export->file_type        = $get_file_type;
            $export->file_location    = 'file';
            $export->module           = 'statement';
            $export->biller_id        = $biller_id;
            $export->customer_id      = $customer_id;
            $export->start_date       = $start_date;
            $export->end_date         = $end_date;
            $export->show_only_unpaid = $show_only_unpaid;
            $export->filter_by_date   = $filter_by_date;
            $export->execute();
        
            #$attachment = file_get_contents('./tmp/cache/statement_'.$biller_id.'_'.$customer_id.'_'.$start_date.'_'.$end_date.'.pdf');
            $attachment = 'statement_'.$biller_id.'_'.$customer_id.'_'.$start_date.'_'.$end_date.'.pdf';
        
            $email = new \email();
            $email->format        = 'statement';
            $email->notes         = $_POST['email_notes'];
            $email->from          = $_POST['email_from'];
            $email->from_friendly = $biller['name'];
            $email->to            = $_POST['email_to'];
            $email->bcc           = $_POST['email_bcc'];
            $email->subject       = $_POST['email_subject'];
            $email->attachment    = $attachment;
            $message              = $email->send ();
        } else if ($_GET['stage'] == 3 ) {
            //stage 3 = assemble email and send
            $message = "How did you get here :)";
        }
        
        $this->smarty->assign('message', $message);
        $this->smarty->assign('biller', $biller);
        $this->smarty->assign('customer', $customer);
        $this->smarty->assign('invoice', $invoice);
        $this->smarty->assign('preferences', $preference);
        
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function exportAction()
    {
        global $menu;
        
        $biller_id        = $_GET['biller_id'];
        $customer_id      = $_GET['customer_id'];
        $start_date       = $_GET['start_date'];
        $end_date         = $_GET['end_date'];
        $show_only_unpaid = $_GET['show_only_unpaid'];
        $filter_by_date   = $_GET['filter_by_date'];
        $get_format       = $_GET['format'];
        $get_file_type    = $_GET['filetype'];
        
        #get the invoice id
        $export = new \export();
        $export->format           = $get_format;
        $export->file_type        = $get_file_type;
        $export->file_location    = 'download';
        $export->module           = 'statement';
        $export->biller_id        = $biller_id;
        $export->customer_id      = $customer_id;
        $export->start_date       = $start_date;
        $export->end_date         = $end_date;
        $export->show_only_unpaid = $show_only_unpaid;
        $export->filter_by_date   = $filter_by_date;
        $export->execute();
    }
    
    public function indexAction()
    {
        global $menu;
        
        $start_date  = isset($_POST['start_date'])  ? $_POST['start_date']   : date("Y-m-d", strtotime('01-01-'.date('Y').' 00:00:00'));
        $end_date    = isset($_POST['end_date'])    ? $_POST['end_date']     : date("Y-m-d", strtotime('31-12-'.date('Y').' 00:00:00'));
        $biller_id   = isset($_POST['biller_id'])   ? $_POST['biller_id']    : "" ;
        $customer_id = isset($_POST['customer_id']) ? $_POST['customer_id']  : "" ;
               
        if (isset($_POST['submit'])) {
            $invoice = new \invoice();
            $invoice->start_date = $start_date;
            $invoice->end_date = $end_date;
            $invoice->biller = $biller_id;
            $invoice->customer = $customer_id;
            $invoice->having = "open";
        
            if ( isset($_POST['filter_by_date']) ) {
                $invoice->having = "date_between";
                $filter_by_date = "yes";
                $having_and_count = 1;
            }
        
            if ( isset($_POST['show_only_unpaid']) ) {
                if ($having_and_count == 1) {
                    $invoice->having_and = "money_owed";
                } else {
                    $invoice->having = "money_owed";
                }
                $show_only_unpaid = "yes";
            }
        
            $invoice->sort = "date";
            $invoice_all   = $invoice->select_all();
            $invoices      = $invoice_all->fetchAll();
        
            foreach ($invoices as $i => $row) {
                if ($row['status'] > 0) {
                    $statement['total'] = $statement['total'] + $row['invoice_total'];
                    $statement['owing'] = $statement['owing'] + $row['owing'] ;
                    $statement['paid']  = $statement['paid']  + $row['INV_PAID'];
                }
            }
        }
        
        $billers          = getActiveBillers();
        $customers        = getActiveCustomers();
        $biller_details   = getBiller($biller_id);
        $customer_details = getCustomer($customer_id);
        
        $this->smarty->assign('biller_id', $biller_id);
        $this->smarty->assign('biller_details', $biller_details);
        $this->smarty->assign('customer_id', $customer_id);
        $this->smarty->assign('customer_details', $customer_details);
        
        $this->smarty->assign('show_only_unpaid', $show_only_unpaid);
        $this->smarty->assign('filter_by_date', $filter_by_date);
        
        $this->smarty->assign('billers', $billers);
        $this->smarty->assign('customers', $customers);
        
        $this->smarty->assign('invoices', $invoices);
        $this->smarty->assign('statement', $statement);
        $this->smarty->assign('start_date', $start_date);
        $this->smarty->assign('end_date', $end_date);
        
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
        $this->smarty->assign('menu', $menu);
    }
}