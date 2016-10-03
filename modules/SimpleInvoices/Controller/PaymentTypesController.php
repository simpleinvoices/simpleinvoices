<?php
namespace SimpleInvoices\Controller;

/**
 * @author Juan Pedro Gonzalez Gutierrez
 */
class PaymentTypesController
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
    
    protected function xmlSql($type='', $dir, $sort, $rp, $page )
    {
    	global $config;
    	global $auth_session;
    	global $LANG;
    
    	$valid_search_fields = array('pt_id', 'pt_description');
    
    	//SC: Safety checking values that will be directly subbed in
    	if (intval($start) != $start) {
    		$start = 0;
    	}
    	if (intval($limit) != $rp) {
    		$rp = 25;
    	}
    
    	/*SQL Limit - start*/
    	$start = (($page-1) * $rp);
    	$limit = "LIMIT $start, $rp";
    
    	if($type =="count")
    	{
    		unset($limit);
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
    	$validFields = array('pt_id', 'pt_description','enabled');
    
    	if (in_array($sort, $validFields)) {
    		$sort = $sort;
    	} else {
    		$sort = "pt_description";
    	}
    
    		$sql = "SELECT 
    					pt_id,
    					pt_description, 
    					(SELECT (CASE  WHEN pt_enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
    			FROM 
    					".TB_PREFIX."payment_types
    			WHERE domain_id = :domain_id
    				$where
    			ORDER BY 
    					$sort $dir 
    			$limit";
    
    	if (empty($query)) {
    		$result = dbQuery($sql,':domain_id', $auth_session->domain_id);
    	} else {
    		$result = dbQuery($sql,':domain_id', $auth_session->domain_id, ':query', "%$query%");
    	}
    
    	return $result;
    }

    public function addAction()
    {
        $this->smarty->assign('pageActive', 'payment_type');
        $this->smarty->assign('subPageActive', 'payment_types_add');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function detailsAction()
    {
        global $LANG;
        
        jsBegin();
        jsFormValidationBegin("frmpost");
        jsValidateRequired("pt_description", $LANG['payment_type_description']);
        jsFormValidationEnd();
        jsEnd();
        
        #get the invoice id
        $payment_type_id = $_GET['id'];
        $paymentType     = getPaymentType($payment_type_id);
        $subPageActive   = $_GET['action'] =="view"  ? "payment_types_view" : "payment_types_edit" ;
        
        $this->smarty->assign('paymentType', $paymentType);
        $this->smarty->assign('pageActive', 'payment_type');
        $this->smarty->assign('subPageActive', $subPageActive);
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function manageAction()
    {
        $paymentTypes = getPaymentTypes();
        
        $this->smarty->assign('paymentTypes', $paymentTypes);
        $this->smarty->assign('pageActive', 'payment_type');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function saveAction()
    {
        global $auth_session;
        
        $op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
        
        #insert payment type
        
        switch ($op)
        {
            case 'insert_payment_type':
                /*Raymond - what about the '', bit doesnt seem to do an insert in me environment when i exclude it
                 $sql = "INSERT INTO ".TB_PREFIX."tax VALUES ('$_POST[tax_description]','$_POST[tax_percentage]')";
                 */
                
                if ($db_server == 'pgsql') {
                    $sql = "INSERT into ".TB_PREFIX."payment_types
                				(domain_id, pt_description, pt_enabled)
                			VALUES
                				(:domain_id', :description, :enabled)";
                } else {
                    $sql = "INSERT into
                				".TB_PREFIX."payment_types
                			VALUES
                				(NULL, :domain_id, :description, :enabled)";
                }
                
                if (dbQuery($sql, ':domain_id', $auth_session->domain_id, ':description', $_POST['pt_description'], ':enabled', $_POST['pt_enabled'])) {
                    $saved = true;
                    //$display_block = $LANG['save_payment_type_success'];
                } else {
                    $saved = false;
                    //$display_block =  $LANG['save_payment_type_failure'];
                }
                
                //header( 'refresh: 2; url=manage_payment_types.php' );
                
                break;
                
            case 'edit_payment_type':
                /*$conn = mysql_connect("$db_host","$db_user","$db_password");
                 mysql_select_db("$db_name",$conn); */
                
                if (isset($_POST['save_payment_type'])) {
                    $sql = "UPDATE
                				".TB_PREFIX."payment_types
			                SET
                				pt_description = :description,
                				pt_enabled = :enabled
                			WHERE
                				pt_id = :id";
                
                    if (dbQuery($sql, ':description', $_POST['pt_description'], ':enabled', $_POST['pt_enabled'], ':id', $_GET['id'])) {
                        $saved = true;
                        //$display_block = $LANG['save_payment_type_success'];
                    } else {
                        $saved = false;
                        //$display_block =  $LANG['save_payment_type_failure'];
                    }
                
                    //header( 'refresh: 2; url=manage_payment_types.php' );
                    //$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=payment_types&amp;view=manage' />";
                }    
                
                break;
        }
        
        //TODO: Make redirection with php..
        
        $refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
        
        $this->smarty->assign('display_block', $display_block);
        $this->smarty->assign('refresh_total', $refresh_total);
        $this->smarty->assign('saved', $saved);
        $this->smarty->assign('pageActive', 'payment_type');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function xmlAction()
    {
        global $LANG;
        
        header("Content-type: text/xml");
        
        $start          = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
        $dir            = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
        $sort           = (isset($_POST['sortname']))  ? $_POST['sortname']  : "pt_description" ;
        $rp             = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
        $page           = (isset($_POST['page']))      ? $_POST['page']      : "1" ;
        $sth            = $this->xmlSql('', $dir, $sort, $rp, $page);
        $sth_count_rows = $this->xmlSql('count',$dir, $sort, $rp, $page);
        $payment_types  = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $count          = $sth_count_rows->rowCount();
        
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($payment_types as $row) {
            $xml .= "<row id='".$row['pref_id']."'>";
            $xml .= "<cell><![CDATA[
            <a class='index_table' title='$LANG[view] $LANG[payment_type] ".$row['pt_description']."' href='index.php?module=payment_types&view=details&id=$row[pt_id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            <a class='index_table' title='$LANG[edit] $LANG[payment_type] ".$row['pt_description']."' href='index.php?module=payment_types&view=details&id=$row[pt_id]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            ]]></cell>";
            $xml .= "<cell><![CDATA[".$row['pt_description']."]]></cell>";
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