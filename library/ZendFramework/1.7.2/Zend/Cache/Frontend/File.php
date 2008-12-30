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
 * @package    Zend_Cache
 * @subpackage Zend_Cache_Frontend
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * @see Zend_Cache_Core
 */
require_once 'Zend/Cache/Core.php';


/**
 * @package    Zend_Cache
 * @subpackage Zend_Cache_Frontend
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Cache_Frontend_File extends Zend_Cache_Core
{
    /**
     * Available options
     *
     * ====> (string) master_file :
     * - the complete path and name of the master file
     * - this option has to be set !
     *
     * @var array available options
     */
    protected $_specificOptions = array(
        'master_file' => ''
    );

    /**
     * Master file mtime
     *
     * @var int
     */
    private $_masterFile_mtime = null;

    /**
     * Constructor
     *
     * @param  array $options Associative array of options
     * @throws Zend_Cache_Exception
     * @return void
     */
    public function __construct(array $options = array())
    {
        while (list($name, $value) = each($options)) {
            $this->setOption($name, $value);
        }
        if (!isset($this->_specificOptions['master_file'])) {
            Zend_Cache::throwException('master_file option must be set');
        }
        $this->setMasterFile($this->_specificOptions['master_file']);
    }
    
    /**
     * Change the master_file option
     * 
     * @param string $masterFile the complete path and name of the master file
     */
    public function setMasterFile($masterFile)
    {
        clearstatcache();
        $this->_specificOptions['master_file'] = $masterFile;
        if (!($this->_masterFile_mtime = @filemtime($masterFile))) {
            Zend_Cache::throwException('Unable to read master_file : '.$masterFile);
        }
    }
    
    /**
     * Public frontend to set an option
     *
     * Just a wrapper to get a specific behaviour for master_file
     *
     * @param  string $name  Name of the option
     * @param  mixed  $value Value of the option
     * @throws Zend_Cache_Exception
     * @return void
     */
    public function setOption($name, $value)
    {
        if ($name == 'master_file') {
            $this->setMasterFile($value);
        } else {
            parent::setOption($name, $value);
        }
    }

    /**
     * Test if a cache is available for the given id and (if yes) return it (false else)
     *
     * @param  string  $id                     Cache id
     * @param  boolean $doNotTestCacheValidity If set to true, the cache validity won't be tested
     * @param  boolean $doNotUnserialize       Do not serialize (even if automatic_serialization is true) => for internal use
     * @return mixed|false Cached datas
     */
    public function load($id, $doNotTestCacheValidity = false, $doNotUnserialize = false)
    {
        if (!$doNotTestCacheValidity) {
            if ($this->test($id)) {
                return parent::load($id, true, $doNotUnserialize);
            }
            return false;
        }
        return parent::load($id, true, $doNotUnserialize);
    }

    /**
     * Test if a cache is available for the given id
     *
     * @param  string $id Cache id
     * @return boolean True is a cache is available, false else
     */
    public function test($id)
    {
        $lastModified = parent::test($id);
        if ($lastModified) {
            if ($lastModified > $this->_masterFile_mtime) {
                return $lastModified;
            }
        }
        return false;
    }

}

