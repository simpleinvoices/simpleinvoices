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
 * @category     Zend
 * @package      Zend_Gdata
 * @copyright    Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license      http://framework.zend.com/license/new-bsd         New BSD License
 */

/**
 * @see Zend_Gdata_Entry
 */
require_once 'Zend/Gdata/Entry.php';

/**
 * @see Zend_Gdata_Spreadsheets_Extension_Custom
 */
require_once 'Zend/Gdata/Spreadsheets/Extension/Custom.php';

/**
 * Concrete class for working with List entries.
 *
 * @category     Zend
 * @package        Zend_Gdata
 * @copyright    Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license        http://framework.zend.com/license/new-bsd         New BSD License
 */
class Zend_Gdata_Spreadsheets_ListEntry extends Zend_Gdata_Entry
{

    protected $_entryClassName = 'Zend_Gdata_Spreadsheets_ListEntry';

    protected $_custom = array();

    /**
     * Constructs a new Zend_Gdata_Spreadsheets_ListEntry object.
     * @param DOMElement $element An existing XML element on which to base this new object.
     */
    public function __construct($element = null)
    {
        foreach (Zend_Gdata_Spreadsheets::$namespaces as $nsPrefix => $nsUri) {
            $this->registerNamespace($nsPrefix, $nsUri);
        }
        parent::__construct($element);
    }
    
    public function getDOM($doc = null)
    {
        $element = parent::getDOM($doc);
        if (!empty($this->_custom)) {
            foreach ($this->_custom as $custom) {
                $element->appendChild($custom->getDOM($element->ownerDocument));
            }
        }
        return $element;
    }
    
    protected function takeChildFromDOM($child)
    {
        switch ($child->namespaceURI) {
        case $this->lookupNamespace('gsx');
            $custom = new Zend_Gdata_Spreadsheets_Extension_Custom($child->localName);
            $custom->transferFromDOM($child);
            $this->_custom[] = $custom;
            break;
        default:
            parent::takeChildFromDOM($child);
            break;
        }
    }
    
    /**
     * Gets the row elements contained by this list entry.
     * @return array The custom row elements in this list entry
     */
    public function getCustom() 
    {
        return $this->_custom;
    }
    
    /**
     * Sets the row elements contained by this list entry.
     * @param array $custom The custom row elements to be contained in this list entry
     */
    public function setCustom($custom) 
    {
        $this->_custom = $custom;
        return $this;
    }

}
