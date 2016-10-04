<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class CustomersController
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
    
    protected function xmlSql($type='', $start, $dir, $sort, $rp, $page )
    {
    	global $config;
    	global $LANG;
    	global $auth_session;
    
    	$valid_search_fields = array('c.id', 'c.name');
    		
    	//SC: Safety checking values that will be directly subbed in
    	if (intval($page) != $page) {
    		$start = 0;
    	}
    	
    	if (intval($rp) != $rp) {
    		$rp = 25;
    	}
    	
    	/*SQL Limit - start*/
    	$start = (($page-1) * $rp);
    	$limit = "LIMIT $start, $rp";
    
    	if($type =="count")
    	{
    		unset($limit);
    		$limit;
    	}
    	/*SQL Limit - end*/	
    	
    	
    	if (!preg_match('/^(asc|desc)$/iD', $dir)) {
    		$dir = 'DESC';
    	}
    	
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
    	$validFields = array('CID', 'name', 'customer_total', 'paid', 'owing', 'enabled');
    	
    	if (in_array($sort, $validFields)) {
    		$sort = $sort;
    	} else {
    		$sort = "CID";
    	}
    	
    		//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
    		$sql = "SELECT 
    					c.id as CID 
    					, c.name as name 
    					, (SELECT (CASE  WHEN c.enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
    					, SUM(COALESCE(IF(pr.status = 1, ii.total, 0),  0)) AS customer_total
    					, COALESCE(ap.amount,0) AS paid
    					, (SUM(COALESCE(IF(pr.status = 1, ii.total, 0),  0)) - COALESCE(ap.amount,0)) AS owing
    				FROM 
    					".TB_PREFIX."customers c  
    					LEFT JOIN ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id AND iv.domain_id = c.domain_id)
    					LEFT JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
    					LEFT JOIN ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
    					LEFT JOIN (SELECT iv3.customer_id, p.domain_id, SUM(COALESCE(p.ac_amount, 0)) AS amount 
    							FROM ".TB_PREFIX."payment p INNER JOIN si_invoices iv3 
    						ON (iv3.id = p.ac_inv_id AND iv3.domain_id = p.domain_id)
    							GROUP BY iv3.customer_id, p.domain_id
    						) ap ON (ap.customer_id = c.id AND ap.domain_id = c.domain_id)
    				WHERE c.domain_id = :domain_id
    					  $where
    				GROUP BY CID
    				ORDER BY 
    					$sort $dir 
    				$limit";
    	
    		if (empty($query)) {
    			$result = dbQuery($sql, ':domain_id', $auth_session->domain_id);
    		} else {
    			$result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
    		}
    
    		return $result;
    		
    }
    
    public function addAction()
    {
        $customFieldLabel = getCustomFieldLabels();
        
        //if valid then do save
        if ($_POST['name'] != "" ) {
            $this->saveAction();
        }
        
        $this->smarty->assign('customFieldLabel', $customFieldLabel);
        $this->smarty->assign('pageActive', 'customer');
        $this->smarty->assign('subPageActive', 'customer_add');
        $this->smarty->assign('active_tab', '#people');
    }
    
    public function detailsAction()
    {
        global $LANG;
        
        #get the invoice id
        $customer_id                     = $_GET['id'];
        $customer                        = getCustomer($customer_id);
        $customer['wording_for_enabled'] = $customer['enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled'];
        
        //TODO: Perhaps possible a bit nicer?
        $stuff = null;
        $stuff['total'] = calc_customer_total($customer['id'], \domain_id::get(), true);
        
        #amount paid calc - start
        $stuff['paid'] = calc_customer_paid($customer['id'], \domain_id::get(), true);
        #amount paid calc - end
        
        #amount owing calc - start
        $stuff['owing'] = $stuff['total'] - $stuff['paid'];
        #get custom field labels
        
        $customFieldLabel = getCustomFieldLabels();
        $invoices = getCustomerInvoices($customer_id);
        
        //$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
        $dir    =  "DESC" ;
        $sort   =  "id" ;
        $rp     = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
        $having = 'money_owed' ;
        $page   = (isset($_POST['page'])) ? $_POST['page'] : "1" ;
        
        //$sql = "SELECT * FROM ".TB_PREFIX."invoices LIMIT $start, $limit";
        $invoice_owing = new \invoice();
        $invoice_owing->sort       = $sort;
        $invoice_owing->having_and = "real";
        $invoice_owing->query      = $_REQUEST['query'];
        $invoice_owing->qtype      = $_REQUEST['qtype'];
        
        $large_dataset = getDefaultLargeDataset();
        if($large_dataset == $LANG['enabled'])
        {
            $sth = $invoice_owing->select_all('large_count', $dir, $rp, $page, $having);
        } else {
            $sth = $invoice_owing->select_all('', $dir, $rp, $page, $having);
        
        }
        
        $invoices_owing = $sth->fetchAll(\PDO::FETCH_ASSOC);
        
        //$customFieldLabel = getCustomFieldLabels("biller");
        
        $subPageActive = $_GET['action'] =="view"  ? "customer_view" : "customer_edit" ;
        
        $this->smarty->assign("stuff",$stuff);
        $this->smarty->assign('customer',$customer);
        $this->smarty->assign('invoices',$invoices);
        $this->smarty->assign('invoices_owing',$invoices_owing);
        $this->smarty->assign('customFieldLabel',$customFieldLabel);
        $this->smarty->assign('pageActive', 'customer');
        $this->smarty->assign('subPageActive', $subPageActive);
        $this->smarty->assign('pageActive', 'customer');
        $this->smarty->assign('active_tab', '#people');
    }
    
    public function manageAction()
    {
        $sql                 = "SELECT count(*) AS count FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id";
        $sth                 = dbQuery($sql, ':domain_id', \domain_id::get());
        $number_of_customers = $sth->fetch(\PDO::FETCH_ASSOC);
        
        $this->smarty->assign('number_of_customers', $number_of_customers);
        $this->smarty->assign('pageActive', 'customer');
        $this->smarty->assign('active_tab', '#people');
    }
    
    public function saveAction()
    {
        $op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
        
        #insert customer
        $saved = false;
        
        if ($op === "insert_customer") {        
            if (insertCustomer()) {
                $saved = true;
            }
        } elseif ( $op === 'edit_customer' ) {
            if (isset($_POST['save_customer'])) {
                if (updateCustomer()) {
                    $saved = true;
                }
            }
        }
        
        $this->smarty->assign('saved', $saved);
        $this->smarty->assign('pageActive', 'customer');
        $this->smarty->assign('active_tab', '#people');
    }
    
    /**
     * TODO: by loading the default templates we don't allow customization!
     */
    public function searchAction()
    {
        $this->smarty->display("../templates/default/menu.tpl");
        $this->smarty->display("../templates/default/main.tpl");
        
        echo <<<EOD
	<div>
	<form action="index.php?module=customers&view=search" method="post">
	<input type="text" name="name" />
	<input type="submit" value="Search">
	</form>
EOD;
        
        $customers = searchCustomers($_POST['name']);
        
        echo "<table> <br />";
        
        foreach($customers as $customer) {
            echo <<<EOD
        
		<tr>
			<td>$customer[name]&nbsp;&nbsp;</td>
			<td><a href="index.php?module=invoices&view=itemised&customer_id=$customer[id]">Itemised</a> |</td>
			<td><a href="index.php?module=invoices&view=consulting&customer_id=$customer[id]">&nbsp;Consulting</a> |</td>
			<td><a href="index.php?module=invoices&view=total&customer_id=$customer[id]">&nbsp;Total</a></td>
		</tr>
EOD;
        }
        
        echo "</table></div>";
        
        //getMenuStructure();
        exit(); //Fix double menu display ;-) - Gates
    }
    
    public function xmlAction()
    {
        global $LANG;
        
        header("Content-type: text/xml");
        
        $start          = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
        $dir            = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
        $sort           = (isset($_POST['sortname']))  ? $_POST['sortname']  : "name" ;
        $rp             = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
        $page           = (isset($_POST['page']))      ? $_POST['page']      : "1" ;
        $xml            = "";
        $sth            = $this->xmlSql('', $start, $dir, $sort, $rp, $page);
        $sth_count_rows = $this->xmlSql('count', $start, $dir, $sort, $rp, $page);
        $customers      = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $count          = $sth_count_rows->rowCount();
        
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($customers as $row) {
            $xml .= "<row id='".$row['CID']."'>";
            $xml .= "<cell><![CDATA[
            <a class='index_table' title='$LANG[view] $LANG[customer] ".$row['name']."' href='index.php?module=customers&view=details&id=$row[CID]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            <a class='index_table' title='$LANG[edit] $LANG[customer] ".$row['name']."' href='index.php?module=customers&view=details&id=$row[CID]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            ]]></cell>";
            $xml .= "<cell><![CDATA[".$row['CID']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
            $xml .= "<cell><![CDATA[".\siLocal::number($row['customer_total'])."]]></cell>";
            $xml .= "<cell><![CDATA[".\siLocal::number($row['paid'])."]]></cell>";
            $xml .= "<cell><![CDATA[".\siLocal::number($row['owing'])."]]></cell>";
            if ($row['enabled']==$LANG['enabled']) {
                $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
            }
            else {
                $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
            }
            $xml .= "</row>";
        }
        $xml .= "</rows>";
        
        echo $xml;
    }
}