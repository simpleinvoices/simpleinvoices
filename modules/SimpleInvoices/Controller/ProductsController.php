<?php
namespace SimpleInvoices\Controller;

/**
 * @author Juan Pedro Gonzalez Gutierrez
 */
class ProductsController
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
        $customFieldLabel = getCustomFieldLabels();
        $taxes            = getActiveTaxes();
        //if valid then do save
        if ($_POST['description'] != "" ) {
            $this->saveAction();
        }
        
        $sql = "select * from ".TB_PREFIX."products_attributes where enabled ='1'";
        $sth =  dbQuery($sql);
        $attributes = $sth->fetchAll();
        
        $this->smarty->assign("defaults", getSystemDefaults());
        $this->smarty->assign('customFieldLabel', $customFieldLabel);
        $this->smarty->assign('taxes', $taxes);
        $this->smarty->assign("attributes", $attributes);
        $this->smarty->assign('pageActive', 'product_add');
        $this->smarty->assign('active_tab', '#product');
    }
    
    public function detailsAction()
    {
        $product_id                  = $_GET['id'];
        $product                     = getProduct($product_id);
        $customFieldLabel            = getCustomFieldLabels();
        $taxes                       = getActiveTaxes();
        $tax_selected                = getTaxRate($product['default_tax_id']);
        $product['attribute_decode'] = json_decode($product['attribute'],true);
        $sql                         = "select * from ".TB_PREFIX."products_attributes";
        $sth                         = dbQuery($sql);
        $attributes                  = $sth->fetchAll();
        $subPageActive               = $_GET['action'] =="view"  ? "product_view" : "product_edit" ;
        
        $this->smarty->assign("defaults", getSystemDefaults());
        $this->smarty->assign('product', $product);
        $this->smarty->assign('taxes', $taxes);
        $this->smarty->assign('tax_selected', $tax_selected);
        $this->smarty->assign('customFieldLabel', $customFieldLabel);
        $this->smarty->assign("attributes", $attributes);
        $this->smarty->assign('pageActive', 'product_manage');
        $this->smarty->assign('subPageActive', $subPageActive);
        $this->smarty->assign('active_tab', '#product');
    }
    
    public function manageAction()
    {
        global $dbh;
        
        $sql            = "SELECT count(*) AS count FROM ".TB_PREFIX."products WHERE domain_id = :domain_id";
        $sth            = dbQuery($sql, ':domain_id', \domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
        $number_of_rows = $sth->fetch(\PDO::FETCH_ASSOC);
        $defaults       = getSystemDefaults();
        
        $this->smarty->assign("defaults", $defaults);
        $this->smarty->assign("number_of_rows", $number_of_rows);
        $this->smarty->assign('pageActive', 'product_manage');
        $this->smarty->assign('active_tab', '#product');
    }
    
    public function saveAction()
    {
        $saved = false;
        
        $op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
        
        switch ($op) 
        {
            case 'insert_product':
                if($id = insertProduct()) {
                    $saved = true;
                    //saveCustomFieldValues($_POST['categorie'], lastInsertId());
                }
                break;
            case 'edit_product':
                if (isset($_POST['save_product']) && updateProduct()) {
                    $saved = true;
                    //updateCustomFieldValues($_POST['categorie'],$_GET['id']);
                }
                break;
        }
        
        $refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
        
        
        $smarty->assign('saved', $saved);
        $smarty->assign('pageActive', 'product_manage');
        $smarty->assign('active_tab', '#product');
    }
    
    public function xmlAction()
    {
        global $LANG;
        
        header("Content-type: text/xml");
        
        //$start        = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
        $dir            = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
        $sort           = (isset($_POST['sortname']))  ? $_POST['sortname']  : "description" ;
        $rp             = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
        $page           = (isset($_POST['page']))      ? $_POST['page']      : "1" ;
        $defaults       = getSystemDefaults();
        $products       = new \product();
        $sth            = $products->select_all('', $dir, $sort, $rp, $page);
        $sth_count_rows = $products->select_all('count',$dir, $sort, $rp, $page);
        $products_all   = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $count          = $sth_count_rows->rowCount();
        
        //echo sql2xml($customers, $count);
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($products_all as $row) {
            $xml .= "<row id='".$row['iso']."'>";
            $xml .= "<cell><![CDATA[
            <a class='index_table' title='$LANG[view] ".$row['description']."' href='index.php?module=products&view=details&id=".$row['id']."&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            <a class='index_table' title='$LANG[edit] ".$row['description']."' href='index.php?module=products&view=details&id=".$row['id']."&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
		]]></cell>";
        
            $xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['description']."]]></cell>";
            $xml .= "<cell><![CDATA[".\siLocal::number($row['unit_price'])."]]></cell>";
            if($defaults['inventory'] == '1')
            {
                $xml .= "<cell><![CDATA[".\siLocal::number_trim($row['quantity'])."]]></cell>";
            }
        
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