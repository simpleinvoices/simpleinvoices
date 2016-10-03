<?php
namespace SimpleInvoices\Controller;

/**
 * TODO: Is this module used at all???
 * 
 * @author Juan Pedro Gonzalez Gutierrez
 */
class DocumentationController
{
    protected $menu;
    
    protected $smarty;

    /**
     * TODO: Don't use globals!
     */
    public function __construct()
    {
        global $smarty;
        global $menu;

        $this->smarty = $smarty;
        $this->menu   = $menu;
    }
 
    public function viewAction()
    {
        global $LANG;
        
        $this->menu = false;
        $get_page   = $_GET['page'];
        $page       = isset($LANG[$get_page]) ? $LANG[$get_page] :  $LANG['no_help_page'] ;
        
        $this->smarty->assign("page", $page);
    }
}