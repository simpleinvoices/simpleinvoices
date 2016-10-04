<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class ProductAttributeController
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
        if ($_POST['name'] != "" ) {
            $this->saveAction();
        }
        
        $sql2       = "SELECT id, name FROM ".TB_PREFIX."products_attribute_type";
        $sth2       = dbQuery($sql2);
        $types      = $sth2->fetchAll(\PDO::FETCH_ASSOC);
        $pageActive = "product_attribute_add";
        
        $this->smarty->assign("types", $types);
        $this->smarty->assign('pageActive', $pageActive);
        $this->smarty->assign('active_tab', '#product');
        $this->smarty->assign('save', $save);
    }
    
    public function detailsAction()
    {
        global $LANG;
        
        if ($_POST['name'] != "" ) {
            $this->saveAction();
        }
        
        #get the invoice id
        $id                        = $_GET['id'];
        $sql_prod                  = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE id = :id;";
        $sth_prod                  = dbQuery($sql_prod, ':id', $id);
        $product_attribute         = $sth_prod->fetch();
        $type                      = \product_attributes::get($id);
        $product_attribute['type'] = $type['type'];
        
        $sql2                      = "SELECT id, name FROM ".TB_PREFIX."products_attribute_type";
        $sth2                      =  dbQuery($sql2);
        $types                     = $sth2->fetchAll(\PDO::FETCH_ASSOC);
        
        $product_attribute['wording_for_enabled'] = $product_attribute['enabled']==1?$LANG['enabled']:$LANG['disabled'];
        $product_attribute['wording_for_visible'] = $product_attribute['visible']==1?$LANG['enabled']:$LANG['disabled'];
        $pageActive = "product_attribute_manage";
        
        $this->smarty->assign("types", $types);
        $this->smarty->assign('pageActive', $pageActive);
        $this->smarty->assign('active_tab', '#product');
        $this->smarty->assign('product_attribute', $product_attribute);
    }
    
    public function manageAction()
    {
        $pageActive = "product_attribute_manage";
        
        $this->smarty->assign('pageActive', $pageActive);
        $this->smarty->assign('active_tab', '#product');
    }
    
    public function saveAction()
    {
        $op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
        
        if (  $op === 'insert_product_attribute' ) {
            $sql = "INSERT into
		".TB_PREFIX."products_attributes
	VALUES
		(
			NULL,
			:name,
			:type_id,
			:enabled,
			:visible
		 )";
        
            if (dbQuery($sql,
                ':name', $_POST['name'],
                ':type_id', $_POST['type_id'],
                ':enabled', $_POST['enabled'],
                ':visible', $_POST['visible']
                )) {
                    $display_block = "Successfully saved";
            } else {
                $display_block = "Error occurred with saving";
            }
        
            //header( 'refresh: 2; url=manage_preferences.php' );
            $refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=product_attribute&amp;view=manage' />";
        } else if (  $op === 'edit_product_attribute' ) {
            if (isset($_POST['save_product_attribute'])) {
                $sql = "UPDATE
				".TB_PREFIX."products_attributes
			SET
				name = :name,
				type_id = :type_id,
				enabled = :enabled,
				visible = :visible
			WHERE
				id = :id";
        
                if (dbQuery($sql,
                    ':name', $_POST['name'],
                    ':type_id', $_POST['type_id'],
                    ':enabled', $_POST['enabled'],
                    ':visible', $_POST['visible'],
                    ':id', $_GET['id']))
                {
                    $display_block = "Successfully saved";
                } else {
                    $display_block = "Error occurred with saving";
                }
        
                $refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=product_attribute&amp;view=manage' />";
        
            } else if ($_POST[action] == "Cancel") {
        
                //header( 'refresh: 0; url=manage_preferences.php' );
                $refresh_total = "<meta http-equiv='refresh' content='0;url=index.php?module=product_attribute&amp;view=manage' />";
            }
        }
        
        
        $refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
        
        $pageActive = "product_attribute_manage";
        
        $this->smarty->assign('pageActive', $pageActive);
        $this->smarty->assign('active_tab', '#product');
        $this->smarty->assign('display_block',$display_block);
        $this->smarty->assign('refresh_total',$refresh_total);
    }
    
    public function xmlAction()
    {
        global $LANG;
        global $dbh;
        
        header("Content-type: text/xml");
        
        $start               = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
        $dir                 = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
        $sort                = (isset($_POST['sortname']))  ? $_POST['sortname']  : "id" ;
        $limit               = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
        $page                = (isset($_POST['page']))      ? $_POST['page']      : "1" ;
        $valid_search_fields = array('id', 'name');
        
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
        $validFields = array('id', 'name','enabled','visible');
        
        if (in_array($sort, $validFields)) {
            $sort = $sort;
        } else {
            $sort = "id";
        }
        
        //$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
        $sql = "SELECT
				id,
				name,
                enabled,
                visible
			FROM
				".TB_PREFIX."products_attributes
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
        
        $customers = $sth->fetchAll(\PDO::FETCH_ASSOC);
        				
        				
        
        $sqlTotal = "SELECT count(id) AS count FROM ".TB_PREFIX."products_attributes";
        $tth = dbQuery($sqlTotal);
        $resultCount = $tth->fetch();
        $count = $resultCount[0];
        //echo sql2xml($customers, $count);
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($customers as $row) {
		    $xml .= "<row id='".$row['id']."'>";
            $xml .= "<cell><![CDATA[<a class='index_table' title='$LANG[view] ".$row['description']."' href='index.php?module=product_attribute&view=details&action=view&id=".$row['id']."&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a> <a class='index_table' title='$LANG[edit] ".$row['description']."' href='index.php?module=product_attribute&view=details&action=edit&id=".$row['id']."&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>]]></cell>";
            $xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
            $xml .= "<cell><![CDATA[".utf8_encode($row['name'])."]]></cell>";
            if ($row['enabled']=='1') {
                $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
            } else {
                $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
            }
            if ($row['visible']=='1') {
                $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['visible']."' title='".$row['visible']."' />]]></cell>";
            } else {
                $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['visible']."' title='".$row['visible']."' />]]></cell>";
            }
        
            $xml .= "</row>";
        }
        
        $xml .= "</rows>";
        echo $xml;
    }
}