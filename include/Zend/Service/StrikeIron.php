<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage StrikeIron
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Loader */
require_once 'Zend/Loader.php';

/** Zend_Service_StrikeIron_Exception */
require_once 'Zend/Service/StrikeIron/Exception.php';

/**
 * This class allows StrikeIron authentication credentials to be specified 
 * in one place and provides a factory for returning instances of different
 * StrikeIron service classes.
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage StrikeIron
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */ 
class Zend_Service_StrikeIron
{
    /**
     * Options to pass to Zend_Service_StrikeIron_Base constructor
     * @param array
     */
    protected $_options;
    
    /**
     * Class constructor
     *
     * @param array  $options  Options to pass to Zend_Service_StrikeIron_Base constructor
     */
    public function __construct($options = array())
    {
        $this->_options = $options;
    }
        
    /**
     * Factory method to return a preconfigured Zend_Service_StrikeIron_*
     * instance.
     *
     * @param  null|string  $options  Service options
     * @return object                 Zend_Service_StrikeIron_* instance
     */
    public function getService($options = array())
    {
        $class = isset($options['class']) ? $options['class'] : 'Base';
        unset($options['class']);
        
        if (strpos($class, '_') === false) {
            $class = "Zend_Service_StrikeIron_{$class}";
        }

        try {
            Zend_Loader::loadClass($class);
        } catch (Exception $e) {
            $msg = "Service '$class' could not be loaded: " . $e->getMessage();
            throw new Zend_Service_StrikeIron_Exception($msg, $e->getCode());
        }

        // instantiate and return the service
        $service = new $class(array_merge($this->_options, $options));
        return $service;
    }
    
}
