<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class InventoryController
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
        if ($_POST['op'] =='add' AND !empty($_POST['product_id']))
        {
            $inventory             = new \inventory();
            $inventory->domain_id  = \domain_id::get();
            $inventory->product_id = $_POST['product_id'];
            $inventory->quantity   = $_POST['quantity'];
            $inventory->cost       = $_POST['cost'];
            $inventory->date       = $_POST['date'];
            $inventory->note       = $_POST['note'];
            $result                = $inventory->insert();
            $saved                 = !empty($result) ? "true" : "false";
        }
        
        $productobj  = new \product();
        $product_all = $productobj->get_all();
        
        $smarty->assign('product_all',$product_all);
        $smarty->assign('saved',$saved);
        $smarty->assign('pageActive', 'inventory');
        $smarty->assign('subPageActive', 'inventory_add');
        $smarty->assign('active_tab', '#product');
    }
    
    public function editAction()
    {
        $saved = "false";
        
        if ($_POST['op'] =='edit' AND !empty($_POST['product_id']))
        {
            $inventory             = new \inventory();
            $inventory->id         = $_GET['id'];
            $inventory->domain_id  = \domain_id::get();
            $inventory->product_id = $_POST['product_id'];
            $inventory->quantity   = $_POST['quantity'];
            $inventory->cost       = $_POST['cost'];
            $inventory->date       = $_POST['date'];
            $inventory->note       = $_POST['note'];
            $result                = $inventory->update();
            $saved                 = !empty($result) ? "true" : "false";
        }
        
        $invoices          = new \invoice();
        $invoices->sort    = 'id';
        $invoice_all       = $invoices->select_all('count');
        $get_inventory     = new \inventory();
        $get_inventory->id = $_GET['id'];
        $inventory         = $get_inventory->select();
        $productobj        = new \product();
        $product_all       = $productobj->get_all();
        
        $this->smarty->assign('product_all', $product_all);
        $this->smarty->assign('saved', $saved);
        $this->smarty->assign('inventory', $inventory);
        $this->smarty->assign('pageActive', 'inventory');
        $this->smarty->assign('subPageActive', 'inventory_edit');
        $this->smarty->assign('active_tab', '#product');
    }
    
    public function manageAction()
    {
        global $dbh;
        
        $sql            = "SELECT count(*) AS count FROM ".TB_PREFIX."inventory where domain_id = :domain_id";
        $sth            = dbQuery($sql, ':domain_id', \domain_id::get()) or die(htmlsafe(end($dbh->errorInfo())));
        $number_of_rows = $sth->fetch(\PDO::FETCH_ASSOC);
        $url            =  'index.php?module=inventory&view=xml';
        
        $this->smarty->assign("number_of_rows", $number_of_rows);
        $this->smarty->assign('pageActive', 'inventory');
        $this->smarty->assign('active_tab', '#product');
        $this->smarty->assign('url', $url);
    }
    
    public function viewAction()
    {
        $get_inventory     = new \inventory();
        $get_inventory->id = $_GET['id'];
        $inventory         = $get_inventory->select();
        
        $this->smarty->assign('inventory', $inventory);
        $this->smarty->assign('pageActive', 'inventory');
        $this->smarty->assign('subPageActive', 'inventory_view');
        $this->smarty->assign('active_tab', '#product');
    }
    
    public function xmlAction()
    {
        global $LANG;
        
        header("Content-type: text/xml");
        
        //$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
        $dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
        $sort = (isset($_POST['sortname'])) ? $_POST['sortname'] : "id" ;
        $rp   = (isset($_POST['rp'])) ? $_POST['rp'] : "25" ;
        $page = (isset($_POST['page'])) ? $_POST['page'] : "1" ;
        
        $inventory       = new \inventory();
        $inventory->sort = $sort;
        $inventory_all   = $inventory->select_all('', $dir, $rp, $page);
        $sth_count_rows  = $inventory->select_all('count',$dir, $rp, $page);
        $count           = $sth_count_rows;
        
        $xml  ="";
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($inventory_all as $row) {
            $xml .= "<row id='".$row['id']."'>";
            $xml .= "<cell><![CDATA[
            <a class='index_table' title='$LANG[view] ".$row['name']."' href='index.php?module=inventory&view=view&id=$row[id]'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            <a class='index_table' title='$LANG[edit] ".$row['name']."' href='index.php?module=inventory&view=edit&id=$row[id]'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            ]]></cell>";
            $xml .= "<cell><![CDATA[".$row['date']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['description']."]]></cell>";
            $xml .= "<cell><![CDATA[".\siLocal::number($row['quantity'])."]]></cell>";
            $xml .= "<cell><![CDATA[".\siLocal::number($row['cost'])."]]></cell>";
            $xml .= "<cell><![CDATA[".\siLocal::number($row['total_cost'])."]]></cell>";
            $xml .= "</row>";
        }
        $xml .= "</rows>";
        echo $xml;
    }
}