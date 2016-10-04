<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class TaxRatesController
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
    
    	$valid_search_fields = array('tax_id', 'tax_description', 'tax_percentage');
    
    	//SC: Safety checking values that will be directly subbed in
    	if (intval($start) != $start) {
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
    		$limit ="";
    	}
    	/*SQL Limit - end*/	
    	if (!preg_match('/^(asc|desc)$/iD', $dir)) {
    		$dir = 'ASC';
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
    	$validFields = array('tax_id', 'tax_description','tax_percentage','enabled');
    
    	if (in_array($sort, $validFields)) {
    		$sort = $sort;
    	} else {
    		$sort = "tax_description";
    	}
    
    	$sql = "SELECT 
    				tax_id, 
    				tax_description,
    				tax_percentage,
    				type,
    				(SELECT (CASE  WHEN tax_enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
    			FROM 
    				".TB_PREFIX."tax
    			WHERE domain_id = :domain_id
    				$where
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
        //if valid then do save
        if ($_POST['tax_description'] != "" ) {
            include("./modules/tax_rates/save.php");
        }
        
        $types = getTaxTypes();
        
        $this->smarty->assign("types",$types);
        $this->smarty->assign('save',$save);
        
        $this->smarty->assign('pageActive', 'tax_rate');
        $this->smarty->assign('subPageActive', 'tax_rate_add');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function detailsAction()
    {
        sBegin();
        jsFormValidationBegin("frmpost");
        jsValidateRequired("tax_description",$LANG['tax_description']);
        jsValidateifNum("tax_percentage",$LANG['tax_percentage']);
        jsFormValidationEnd();
        jsEnd();
        
        #get the invoice id
        $tax_rate_id = $_GET['id'];
        
        $tax = getTaxRate($tax_rate_id);
        $types = getTaxTypes();
        
        $this->smarty->assign("tax",$tax);
        $this->smarty->assign("types",$types);
        
        $this->smarty-> assign('pageActive', 'tax_rate');
        $subPageActive = $_GET['action'] =="view"  ? "tax_rates_view" : "tax_rates_edit" ;
        $this->smarty->assign('subPageActive', $subPageActive);
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function manageAction()
    {
        $this->smarty->assign("taxes", getTaxes());
        $this->smarty->assign('pageActive', 'tax_rate');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function saveAction()
    {
        $display_block = "";
        $refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=tax_rates&amp;view=manage' />";
        
        # Deal with op and add some basic sanity checking
        
        $op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
        $op = isset($_POST['cancel']) ? "cancel" : $op;
        
        switch ($op) {
            case "insert_tax_rate":
                #insert tax rate
                $display_block = insertTaxRate();
                break;
        
            case "edit_tax_rate":
                #edit tax rate
                if (isset($_POST['save_tax_rate']))
                    $display_block = updateTaxRate();
                    else
                        $refresh_total = '&nbsp';
                        break;
        
            case "cancel":
                break;
        
            default:
                $refresh_total = '&nbsp';
        }
        
        $this->smarty->assign('display_block',$display_block);
        $this->smarty->assign('refresh_total',$refresh_total);
        
        $this->smarty->assign('pageActive', 'tax_rate');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function xmlAction()
    {
        global $LANG;
        
        header("Content-type: text/xml");
        
        $start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
        $dir = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
        $sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "tax_description" ;
        $rp = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
        $page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;
        
        $xml ="";
        
        $sth = $this->xmlSql('', $dir, $start, $sort, $rp, $page);
        $sth_count_rows = $this->xmlSql('count',$dir, $start, $sort, $rp, $page);
        
        $tax = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $count = $sth_count_rows->rowCount();
        
        
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($tax as $row) {
            $xml .= "<row id='".$row['tax_id']."'>";
            $xml .= "<cell><![CDATA[
            <a class='index_table' title='$LANG[view] $LANG[tax_rate] ".$row['tax_description']."' href='index.php?module=tax_rates&view=details&id=$row[tax_id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            <a class='index_table' title='$LANG[edit] $LANG[tax_rate] ".$row['tax_description']."' href='index.php?module=tax_rates&view=details&id=$row[tax_id]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            ]]></cell>";
            $xml .= "<cell><![CDATA[".$row['tax_id']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['tax_description']."]]></cell>";
            $xml .= "<cell><![CDATA[" . \siLocal::number($row['tax_percentage']) . " ".$row['type']."]]></cell>";
            if ($row['enabled']==$LANG['enabled']) {
                $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".utf8_encode($row['enabled'])."' title='".utf8_encode($row['enabled'])."' />]]></cell>";
            }
            else {
                $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".utf8_encode($row['enabled'])."' title='".utf8_encode($row['enabled'])."' />]]></cell>";
            }
            $xml .= "</row>";
        }
        $xml .= "</rows>";
        
        echo $xml;
    }
}