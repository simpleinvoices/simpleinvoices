<?php
namespace SimpleInvoices\Controller;

/**
 * @author Juan Pedro Gonzalez Gutierrez
 */
class PaymentsController
{
    protected $menu;
    
    protected $smarty;

    /**
     * TODO: Don't use globals!
     */
    public function __construct()
    {
        global $menu;
        global $smarty;

        $this->smarty = $smarty;
        $this->menu   = $menu;
    }
    
    public function detailsAction()
    {
        jsBegin();
        jsFormValidationBegin("frmpost");
        jsValidateRequired("name","Biller name");
        jsFormValidationEnd();
        jsEnd();
        /*end validation code*/
        
        $payment     = getPayment($_GET['id']);
        
        /*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
        $invoice     = getInvoice($payment['ac_inv_id']);
        $invoiceType = getInvoiceType($invoice['type_id']);
        $paymentType = getPaymentType($payment['ac_payment_type']);
        
        $this->smarty->assign("payment",$payment);
        $this->smarty->assign("invoice",$invoice);
        $this->smarty->assign("invoiceType",$invoiceType);
        $this->smarty->assign("paymentType",$paymentType);
        $this->smarty->assign('pageActive', 'payment');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function ewayAction()
    {
        $saved       = false;
        $invoiceobj  = new \invoice();
        $invoice_all = $invoiceobj->get_all();
        
        if ( ($_POST['op'] =='add') AND (!empty($_POST['invoice_id'])) )
        {
            $invoice = $invoiceobj->select($_POST['invoice_id']);
        
            $eway_check          = new \eway();
            $eway_check->invoice = $invoice;
            $eway_pre_check      = $eway_check->pre_check();
        
            if($eway_pre_check == 'true')
            {
                //do eway payment
                $eway          = new \eway();
                $eway->invoice = $invoice;
                $saved         = $eway->payment();
            } else {
                $saved = 'check_failed';
            }
        }
        
        $this->smarty->assign('invoice_all',$invoice_all);
        $this->smarty->assign('saved',$saved);
        $this->smarty->assign('pageActive', 'payment');
        $this->smarty->assign('subPageActive', 'payment_eway');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function manageAction()
    {
        //TODO - replace get..Payments with simple count - as data is got by xml.php now
        $query = null;$inv_id = null;$c_id = null;
        #if coming from another page where you want to filter by just one invoice
        if (!empty($_GET['id'])) {
            $inv_id        = $_GET['id'];
            $query         = getInvoicePayments($_GET['id']);
            $invoice       = getInvoice($_GET['id']);
            $preference    = getPreference($invoice['preference_id']);
            $subPageActive = "payment_filter_invoice";
        }
        #if coming from another page where you want to filter by just one customer
        elseif (!empty($_GET['c_id'])) {
            $c_id          = $_GET['c_id'];
            $query         = getCustomerPayments($_GET['c_id']);
            $customer      = getCustomer($_GET['c_id']);
            $subPageActive = "payment_filter_customer";
        }
        #if you want to show all invoices - no filters
        else {
            $query         = getPayments();
            $subPageActive = "payment_manage";
        }
        
        $payments = progressPayments($query);
        
        $this->smarty->assign("payments", $payments);
        $this->smarty->assign("preference", $preference);
        $this->smarty->assign("customer", $customer);
        $this->smarty->assign("c_id", $c_id);
        $this->smarty->assign("inv_id", $inv_id);
        $this->smarty->assign('subPageActive', $subPageActive);
        $this->smarty->assign('pageActive', 'payment');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function printAction()
    {
        $this->menu = false;
        $payment    = getPayment($_GET['id']);
        
        /*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
        $invoice           = getInvoice($payment['ac_inv_id']);
        $biller            = getBiller($payment['biller_id']);
        $logo              = getLogo($biller);
        $logo              = str_replace(" ", "%20", $logo);
        $customer          = getCustomer($payment['customer_id']);
        $invoiceType       = getInvoiceType($invoice['type_id']);
        $customFieldLabels = getCustomFieldLabels();
        $paymentType       = getPaymentType($payment['ac_payment_type']);
        $preference        = getPreference($invoice['preference_id']);
        
        $this->smarty->assign("payment",$payment);
        $this->smarty->assign("invoice",$invoice);
        $this->smarty->assign("biller",$biller);
        $this->smarty->assign("logo",$logo);
        $this->smarty->assign("customer",$customer);
        $this->smarty->assign("invoiceType",$invoiceType);
        $this->smarty->assign("paymentType",$paymentType);
        $this->smarty->assign("preference",$preference);
        $this->smarty->assign("customFieldLabels",$customFieldLabels);
        $this->smarty->assign('pageActive', 'payment');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function processAjaxAction()
    {
        $domain_id = \domain_id::get();
        $invoice   = new \invoice();
        $sth       = $invoice->select_all();
        $q         = strtolower($_GET["q"]);
        
        if (!$q) return;
        
        while ($invoice = getInvoices($sth)) {
            $invoiceType = getInvoiceType($invoice['type_id']);
        
            if (strpos(strtolower($invoice['index_id']), $q) !== false) {
                $invoice['id'] = htmlsafe($invoice['id']);
                $invoice['total'] = htmlsafe(number_format($invoice['total'],2));
                $invoice['paid'] = htmlsafe(number_format($invoice['paid'],2));
                $invoice['owing'] = htmlsafe(number_format($invoice['owing'],2));
                echo "$invoice[id]|<table><tr><td class='details_screen'>$invoice[preference]:</td><td>$invoice[index_id]</td><td  class='details_screen'>Total: </td><td>$invoice[total] </td></tr><tr><td class='details_screen'>Biller: </td><td>$invoice[biller] </td><td class='details_screen'>Paid: </td><td>$invoice[paid] </td></tr><tr><td class='details_screen'>Customer: </td><td>$invoice[customer] </td><td class='details_screen'>Owing: </td><td><u>$invoice[owing]</u></td></tr></table>\n";
            }
        }
    }
    
    public function processAction()
    {
        $maxInvoice = maxInvoice();
        
        jsBegin();
        jsFormValidationBegin("frmpost");
        #jsValidateifNum("ac_inv_id",$LANG['invoice_id']);
        #jsPaymentValidation("ac_inv_id",$LANG['invoice_id'],1,$maxInvoice['maxId']);
        jsValidateifNum("ac_amount",$LANG['amount']);
        jsValidateifNum("ac_date",$LANG['date']);
        jsFormValidationEnd();
        jsEnd();
        /* end validataion code */
        
        $today             = date("Y-m-d");
        $master_invoice_id = $_GET['id'];
        $invoice           = null;
        
        if(isset($_GET['id'])) {
            $invoiceobj = new \invoice();
            $invoice = $invoiceobj->select($master_invoice_id);
        } else {
            $sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id";
            $sth = dbQuery($sql, ':domain_id', \domain_id::get());
            $invoice = $sth->fetch();
            #$sth = new invoice();
            #$invoice = $sth->select_all();
        }
        
        $customer = getCustomer($invoice['customer_id']);
        $biller   = getBiller($invoice['biller_id']);
        $defaults = getSystemDefaults();
        $pt       = getPaymentType($defaults['payment_type']);
        
        $invoices             = new \invoice();
        $invoices->sort       = 'id';
        $invoices->having     = 'money_owed';
        $invoices->having_and = 'real';
        $invoice_all          = $invoices->select_all('count');
        $paymentTypes         = getActivePaymentTypes();
        $subPageActive        =  "payment_process" ;
        
        $this->smarty->assign('invoice_all',$invoice_all);
        $this->smarty->assign("paymentTypes",$paymentTypes);
        $this->smarty->assign("defaults",$defaults);
        $this->smarty->assign("biller",$biller);
        $this->smarty->assign("customer",$customer);
        $this->smarty->assign("invoice",$invoice);
        $this->smarty->assign("today",$today);
        $this->smarty->assign('pageActive', 'payment');
        $this->smarty->assign('subPageActive', $subPageActive);
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function saveAction()
    {
        global $db_server;
        global $auth_session;
        global $LANG;
        
        if ( isset($_POST['process_payment']) ) {
        
            $payment                    = new \payment();
            $payment->ac_inv_id 		= $_POST['invoice_id'];
            $payment->ac_amount 		= $_POST['ac_amount'];
            $payment->ac_notes			= $_POST['ac_notes'];
            $payment->ac_date			= SqlDateWithTime($_POST['ac_date']);
            $payment->ac_payment_type	= $_POST['ac_payment_type'];
            $result                     = $payment->insert();
        
            $saved = !empty($result) ? "true" : "false";
            if($saved =='true')
            {
                $display_block =  $LANG['save_payment_success'];
            } else {
                $display_block =  $LANG['save_payment_failure']."<br />".$sql;
            }
        
            $refresh_total = "<meta http-equiv='refresh' content='27;url=index.php?module=payments&view=manage' />";
        }
        
        $this->smarty->assign('display_block', $display_block);
        $this->smarty->assign('pageActive', 'payment');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function xmlAction()
    {
        global $LANG;
        global $config;
        global $auth_session;
        
        header("Content-type: text/xml");
        
        //$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
        $dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
        $sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "ap.id" ;
        $rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
        $page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;
        
        function sql($type='', $dir, $sort, $rp, $page )
        {
            $valid_search_fields = array('ap.id','b.name', 'c.name');
        
            //SC: Safety checking values that will be directly subbed in
            if (intval($start) != $start) {
                $start = 0;
            }
            if (intval($limit) != $limit) {
                $limit = 25;
            }
            if (!preg_match('/^(asc|desc)$/iD', $dir)) {
                $dir = 'DESC';
            }
        
            /*SQL Limit - start*/
            $start = (($page-1) * $rp);
            $limit = "LIMIT $start, $rp";
        
            if($type =="count")
            {
                unset($limit);
            }
            /*SQL Limit - end*/
        
            $where = "";
            $query = isset($_POST['query']) ? $_POST['query'] : null;
            $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
            if ( ! (empty($qtype) || empty($query)) ) {
                if ( in_array($qtype, $valid_search_fields) ) {
                    $where = " AND $qtype LIKE :query ";
                } else {
                    $qtype = null;
                    $query = null;
                }
            }
        
            /*Check that the sort field is OK*/
            $validFields = array('ap.id', 'ac_inv_id', 'description', 'unit_price','enabled');
        
            if (in_array($sort, $validFields)) {
                $sort = $sort;
            } else {
                $sort = "ap.id";
            }
        
            $sql = "SELECT
				ap.*
				, c.name as cname
				, (SELECT CONCAT(pr.pref_inv_wording,' ',iv.index_id)) as index_name
				, b.name as bname
				, pt.pt_description AS description
				, ac_notes AS notes
				, DATE_FORMAT(ac_date,'%Y-%m-%d') AS date
			FROM
				".TB_PREFIX."payment ap
				INNER JOIN ".TB_PREFIX."invoices iv      ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
				INNER JOIN ".TB_PREFIX."customers c      ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
				INNER JOIN ".TB_PREFIX."biller b         ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
				INNER JOIN ".TB_PREFIX."preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = ap.domain_id)
				INNER JOIN ".TB_PREFIX."payment_types pt ON (pt.pt_id = ap.ac_payment_type AND pt.domain_id = ap.domain_id)
			WHERE
				ap.domain_id = :domain_id ";
        
            #if coming from another page where you want to filter by just one invoice
            if (!empty($_GET['id'])) {
        
                $id = $_GET['id'];
        
                $sql .= "
                AND ap.ac_inv_id = :invoice_id
                $where
                ORDER BY
                $sort $dir
                $limit";
        
                if (empty($query)) {
                    $result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':invoice_id', $id);
                } else {
                    $result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':invoice_id', $id, ':query', "%$query%");
                }
            }
            #if coming from another page where you want to filter by just one customer
            elseif (!empty($_GET['c_id'])) {
        
                //$query = getCustomerPayments($_GET['c_id']);
                $id = $_GET['c_id'];
                $sql .= "
                AND c.id = :id
                ORDER BY
                $sort $dir
                $limit";
        
                $result = dbQuery($sql, ':id', $id, ':domain_id', $auth_session->domain_id);
        
            }
            #if you want to show all invoices - no filters
            else {
                //$query = getPayments();
        
                $sql .= "
                $where
                ORDER BY
                $sort $dir
                $limit";
                	
                if (empty($query)) {
                    $result =  dbQuery($sql, ':domain_id', $auth_session->domain_id);
                } else {
                    $result =  dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
                }
            }
        
            return $result;
        }
        
        $sth = sql('', $dir, $sort, $rp, $page);
        $sth_count_rows = sql('count',$dir, $sort, $rp, $page);
        
        $payments = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $count = $sth_count_rows->rowCount();
        /*
         $sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."payment";
         $tth = dbQuery($sqlTotal) or die(end($dbh->errorInfo()));
         $resultCount = $tth->fetch();
         $count = $resultCount[0];
         //echo sql2xml($customers, $count);
         */
        
        $xml  = "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($payments as $row) {
        
            $notes = si_truncate($row['ac_notes'],'13','...');
            $xml .= "<row id='".$row['id']."'>";
            $xml .= "<cell><![CDATA[
            <a class='index_table' title='$LANG[view] ".$row['name']."' href='index.php?module=payments&view=details&id=$row[id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            <a class='index_table' title='$LANG[print_preview_tooltip] ".$row['id']."' href='index.php?module=payments&view=print&id=$row[id]'><img src='images/common/printer.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            ]]></cell>";
            $xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['index_name']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['cname']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['bname']."]]></cell>";
            $xml .= "<cell><![CDATA[".\siLocal::number($row['ac_amount'])."]]></cell>";
            $xml .= "<cell><![CDATA[".$notes."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['description']."]]></cell>";
            $xml .= "<cell><![CDATA[".siLocal::date($row['date'])."]]></cell>";
        
            $xml .= "</row>";
        }
        $xml .= "</rows>";
        echo $xml;
    }
}
