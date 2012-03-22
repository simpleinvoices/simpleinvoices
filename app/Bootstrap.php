<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
    * Bootstrap autoloader
    * 
    * Smarty 3.1.8 conflicts in Non-MVC, so let's take care of it
    */
    protected function _initAppAutoLoad()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        
        $autoloader->setFallbackAutoloader(true)->pushAutoloader(NULL, 'Smarty_' );
        // Avoid conflicts with Smarty autoloader
        //$autoloader->pushAutoloader(NULL, 'Smarty_' );
        return $autoloader;
    }
    
    /**
    * Bootstrap configuration
    * 
    * For Non-MVC
    */
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
        return $config;
    }
    
    
    /**
    * Bootstrap sessions
    * 
    * For Non-MVC
    */
    protected function _initSession()
    {
        $options = $this->getOptions();
        
        // Get session options
        $session_options = $options['resources']['session'];
        
        if (!empty($session_options)) {
            Zend_Session::setOptions($session_options);
        }

        $session = $this->getPluginResource('session'); 
        $session->init(); 
        Zend_Session::start();
        
        $auth_session = new Zend_Session_Namespace('Zend_Auth');
        
        // Default to 30 minutes
        $lifetime = 1800;
        if(isset($session_options['remember_me_seconds'])) {
            if ($session_options['remember_me_seconds'] > 0) {
                $lifetime = $session_options['remember_me_seconds'];
            }    
        }
        $auth_session->setExpirationSeconds($lifetime);
        
        // store in registry for backward compatibility
        Zend_Registry::set('auth_session', $auth_session);
    }
    
    /**
    * Bootstrap logging
    */
    protected function _initSyslog()
    {
        // Zend_Log
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . "/tmp/log/si.log");
 
        $logger = new Zend_Log($writer);
        Zend_Registry::set('logger', $logger); 
 }
    
}
?>
