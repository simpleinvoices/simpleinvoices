<?php
namespace SimpleInvoices\Controller;

/**
 * @author Juan Pedro Gonzalez Gutierrez
 */
class IndexController
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
    
    public function indexAction()
    {
        global $mysql;
        global $db_server;
        
        $debtor      = getTopDebtor();
        $customer    = getTopCustomer();
        $biller      = getTopBiller();
        
        $billers     = getBillers();
        $customers   = getCustomers();
        $taxes       = getTaxes();
        $products    = getProducts();
        $preferences = getPreferences();
        $defaults    = getSystemDefaults();
        
        if ($billers == null OR $customers == null OR $taxes == null OR $products == null OR $preferences == null)
        {
            $first_run_wizard = true;
            $this->smarty->assign("first_run_wizard", $first_run_wizard);
        }
        
        $this->smarty->assign("mysql", $mysql);
        $this->smarty->assign("db_server", $db_server);
        $this->smarty->assign("biller", $biller);
        $this->smarty->assign("billers", $billers);
        $this->smarty->assign("customer", $customer);
        $this->smarty->assign("customers", $customers);
        $this->smarty->assign("taxes", $taxes);
        $this->smarty->assign("products", $products);
        $this->smarty->assign("preferences", $preferences);
        $this->smarty->assign("debtor", $debtor);
        
        $this->smarty->assign('pageActive', 'dashboard');
        $this->smarty->assign('active_tab', '#home');
    }
}