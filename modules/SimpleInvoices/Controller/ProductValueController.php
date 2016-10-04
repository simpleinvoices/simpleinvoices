<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class ProductValueController
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
        if ($_POST['value'] !== '' ) {
            $this->saveAction();
        }
        
        $sql                = "SELECT * FROM ".TB_PREFIX."products_attributes";
        $sth                =  dbQuery($sql);
        $product_attributes = $sth->fetchAll();
        $pageActive         = "product_value_add";
        
        $this->smarty->assign('pageActive', $pageActive);
        $this->smarty->assign('active_tab', '#product');
        $this->smarty->assign("product_attributes", $product_attributes);
        $this->smarty->assign('save',$save);
    }
    
    public function detailsAction()
    {
        if ($_POST['value'] != "" ) {
            $this->saveAction();
        }
        
        #get the invoice id
        $id = $_GET['id'];
        
        $sql           = "SELECT * FROM ".TB_PREFIX."products_values WHERE id = :id";
        $sth           =  dbQuery($sql, ':id', $id);
        $product_value = $sth->fetch();
        
        $sql_attr_sel = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE id = ".$product_value['id'];
        $sth_attr_sel =  dbQuery($sql_attr_sel);
        $product_attribute = $sth_attr_sel->fetch();
        
        $sql_attr = "select * from ".TB_PREFIX."products_attributes";
        $sth_attr =  dbquery($sql_attr);
        $product_attributes = $sth_attr->fetchall();
        
        $pageActive = "product_value_manage";
        
        $this->smarty->assign("product_value", $product_value);
        $this->smarty->assign("product_attribute", $product_attribute['name']);
        $this->smarty->assign('pageActive', $pageActive);
        $this->smarty->assign('active_tab', '#product');
        $this->smarty->assign('preference', $preference);
        $this->smarty->assign("product_attributes", $product_attributes);
    }
    
    public function manageAction()
    {
        $pageActive = "product_value_manage";
        
        $this->smarty->assign('pageActive', $pageActive);
        $this->smarty->assign('active_tab', '#product');
        
    }
    
    public function saveAction()
    {
        $op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
        
        #insert invoice_preference
        if (  $op === 'insert_product_value' ) {
        
            $sql = "INSERT into
		".TB_PREFIX."products_values
	VALUES
		(
			NULL,
			:attribute_id,
            :value,
            :enabled
		 )";
        
            if (dbQuery($sql,
                ':attribute_id', $_POST['attribute_id'],
                ':value', $_POST['value'],
                ':enabled', $_POST['enabled']
                )) {
                    $display_block = "Successfully saved";
                } else {
                    $display_block = "Error occurred with saving";
                }
        
                //header( 'refresh: 2; url=manage_preferences.php' );
                $refresh_total = "<meta http-equiv='refresh' content='20;url=index.php?module=product_value&amp;view=manage' />";
        }
        
        #edit preference
        
        if (  $op === 'edit_product_value' ) {
        
            if (isset($_POST['save_product_value'])) {
                $sql = "UPDATE
				".TB_PREFIX."products_values
			SET
				attribute_id = :attribute_id,
				value = :value,
				enabled = :enabled
			WHERE
				id = :id";
        
                if (dbQuery($sql,
                    ':attribute_id', $_POST['attribute_id'],
                    ':value', $_POST['value'],
                    ':enabled', $_POST['enabled'],
                    ':id', $_GET['id']))
                {
                    $display_block = "Successfully saved";
                } else {
                    $display_block = "Error occurred with saving";
                }
        
                //header( 'refresh: 2; url=manage_preferences.php' );
                $refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=product_value&amp;view=manage' />";
            }
        }
        
        $refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
        
        $pageActive = "product_value_manage";
        
        $this->smarty->assign('pageActive', $pageActive);
        $this->smarty->assign('active_tab', '#product');
        $this->smarty->assign('display_block', $display_block);
        $this->smarty->assign('refresh_total', $refresh_total);
    }
    
    public function xmlAction()
    {
        global $LANG;
        
        header("Content-type: text/xml");
        
        $start               = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
        $dir                 = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
        $sort                = (isset($_POST['sortname']))  ? $_POST['sortname']  : "id" ;
        $limit               = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
        $page                = (isset($_POST['page']))      ? $_POST['page']      : "1" ;
        
        $valid_search_fields = array('name', 'value');
        
        //SC: Safety checking values that will be directly subbed in
        if (intval($page) != $page) {
            $start = 0;
        }
        $start = (($page-1) * $limit);
        
        if (intval($limit) != $limit) {
            $limit = 25;
        }
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
        $validFields = array('id', 'name', 'value','enabled');
        
        if (in_array($sort, $validFields)) {
            $sort = $sort;
        } else {
            $sort = "id";
        }
        
        $sql = "SELECT
				v.id as id,
				a.name as name,
                v.value as value,
                v.enabled as enabled
			FROM
				".TB_PREFIX."products_attributes a LEFT JOIN
				".TB_PREFIX."products_values v ON (a.id = v.attribute_id)
        	WHERE 1
        		$where
        	ORDER BY
        	    $sort $dir
        	LIMIT
        	    $start, $limit";
        
        if (empty($query)) {
            $sth = dbQuery($sql);
        } else {
            $sth = dbQuery($sql, ':query', "%$query%");
        }
        
        $customers = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        $sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."products_values";
        $tth = dbQuery($sqlTotal);
        $resultCount = $tth->fetch();
        $count = $resultCount[0];
        //echo sql2xml($customers, $count);
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($customers as $row) {
            $xml .= "<row id='".$row['id']."'>";
            $xml .= "<cell><![CDATA[<a class='index_table' title='$LANG[view] ".$row['description']."' href='index.php?module=product_value&view=details&action=view&id=".$row['id']."&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a> <a class='index_table' title='$LANG[edit] ".$row['description']."' href='index.php?module=product_value&view=details&action=edit&id=".$row['id']."&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>]]></cell>";
            $xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
            $xml .= "<cell><![CDATA[".utf8_encode($row['name'])."]]></cell>";
        	$xml .= "<cell><![CDATA[".utf8_encode($row['value'])."]]></cell>";
            if ($row['enabled']=='1') {
                $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
            } else {
                $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
            }
        
            $xml .= "</row>";
        }
        
        $xml .= "</rows>";
        echo $xml;
    }
}