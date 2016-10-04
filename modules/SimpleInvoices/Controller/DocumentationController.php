<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

/**
 * TODO: Is this module used at all???
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