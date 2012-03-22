<?php
class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        // Action body
        $this->_helper->layout->disableLayout();
    }
    
    public function preDispatch()
    {
        /**
        * Disable Layout and Views as we are using Smarty in Non-MVC
        */
        //$this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }
}

?>
