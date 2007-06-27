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
 * @package    Zend_Measure
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @version    $Id: Abstract.php 3867 2007-03-11 13:02:10Z thomas $
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


require_once 'Zend/Locale.php';


/**
 * @category   Zend
 * @package    Zend_Measure
 * @subpackage Zend_Measure_Abstract
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Measure_Abstract
{

    /**
     * internal plain value in standard unit
     */
    protected $_value;


    /**
     * internal original type for this unit
     */
    protected $_type;


    /**
     * Internal locale identifier
     */
    protected $_Locale = null;


    /**
     * Unit types for this measurement
     */
    protected $_UNITS = array();


    /**
     * Zend_Measure_Abstract is an abstract class for the different measurement types
     *
     * @param  $value  mixed  - Value as string, integer, real or float
     * @param  $type   type   - OPTIONAL a Zend_Measure_Area Type
     * @param  $locale locale - OPTIONAL a Zend_Locale Type
     * @throws Zend_Measure_Exception
     */
    public function __construct($value, $type = null, $locale = null)
    {
        if (Zend_Locale::isLocale($type)) {
            $locale = $type;
            $type = null;
        }

        if ($locale === null) {
            $locale = new Zend_Locale();
        }

        if ($locale instanceof Zend_Locale) {
            $locale = $locale->toString();
        }

        if (!$this->_Locale = Zend_Locale::isLocale($locale, true)) {
            throw new Zend_Measure_Exception("Language ($locale) is unknown");
        }

        $this->_Locale = $locale;

        if ($type === null) {
            $type = $this->_UNITS['STANDARD'];
        }

        if (!array_key_exists($type, $this->_UNITS)) {
            throw new Zend_Measure_Exception("Type ($type) is unknown");
        }
        $this->setValue($value, $type, $this->_Locale);
    }


    /**
     * Returns the internal value
     */
    public function getValue()
    {
        return $this->_value;
    }


    /**
     * Set a new value
     *
     * @param  integer|string      $value   Value as string, integer, real or float
     * @param  string              $type    OPTIONAL A Zend_Measure_Acceleration Type
     * @param  string|Zend_Locale  $locale  OPTIONAL Locale for parsing numbers
     * @throws Zend_Measure_Exception
     */
    public function setValue($value, $type = null, $locale = null)
    {
        if (Zend_Locale::isLocale($type)) {
            $locale = $type;
            $type = null;
        }

        if ($locale === null) {
            $locale = $this->_Locale;
        }

        if ($locale instanceof Zend_Locale) {
            $locale = $locale->toString();
        }

        if (!Zend_Locale::isLocale($locale)) {
            throw new Zend_Measure_Exception("Language ($locale) is unknown");
        }

        if ($type === null) {
            $type = $this->_UNITS['STANDARD'];
        }

        if (empty($this->_UNITS[$type])) {
            throw new Zend_Measure_Exception("Type ($type) is unknown");
        }

        try {
            $value = Zend_Locale_Format::getNumber($value, array('locale' => $locale));
        } catch(Exception $e) {
            throw new Zend_Measure_Exception($e->getMessage());
        }

        $this->_value = $value;
        $this->setType($type);
    }


    /**
     * Returns the original type
     * 
     * @return type
     */
    public function getType()
    {
        return $this->_type;
    }


    /**
     * Set a new type, and convert the value
     *
     * @param  string  $type  New type to set
     * @throws Zend_Measure_Exception
     */
    public function setType($type)
    {
        if (empty($this->_UNITS[$type])) {
            throw new Zend_Measure_Exception("Type ($type) is unknown");
        }

        if (empty($this->_type)) {
            $this->_type = $type;
        } else {

            // Convert to standard value
            $value = $this->getValue();
            if (is_array($this->_UNITS[$this->getType()][0])) {
                foreach ($this->_UNITS[$this->getType()][0] as $key => $found) {
                    switch ($key) {
                        case "/":
                            if ($found != 0) {
                                $value = @call_user_func(Zend_Locale_Math::$div, $value, $found, 25);
                            }
                            break;
                        case "+":
                            $value = call_user_func(Zend_Locale_Math::$add, $value, $found, 25);
                            break;
                        case "-":
                            $value = call_user_func(Zend_Locale_Math::$sub, $value, $found, 25);
                            break;
                        default:
                            $value = call_user_func(Zend_Locale_Math::$mul, $value, $found, 25);
                            break;
                    }
                }
            } else {
                $value = $value * ($this->_UNITS[$this->getType()][0]);
            }

            // Convert to expected value
            if (is_array($this->_UNITS[$type][0])) {
                foreach ($this->_UNITS[$type][0] as $key => $found) {
                    switch ($key) {
                        case "/":
                            $value = call_user_func(Zend_Locale_Math::$mul, $value, $found, 25);
                            break;
                        case "+":
                            $value = call_user_func(Zend_Locale_Math::$sub, $value, $found, 25);
                            break;
                        case "-":
                            $value = call_user_func(Zend_Locale_Math::$add, $value, $found, 25);
                            break;
                        default:
                            if ($found != 0) {
                                $value = @call_user_func(Zend_Locale_Math::$div, $value, $found, 25);
                            }
                            break;
                    }
                }
            } else {
                $value = $value / ($this->_UNITS[$type][0]);
            }

            $this->_value = $value;
            $this->_type = $type;
        }
    }


    /**
     * Compare if the value and type is equal
     *
     * @param  Zend_Measure_Detailtype  $object  object to compare
     * @return boolean
     */
    public function equals($object)
    {
        if ($object->toString() == $this->toString()) {
            return true;
        }

        return false;
    }




    /**
     * Returns a string representation
     *
     * @return string
     */
    public function toString()
    {
        return $this->getValue() . ' ' . $this->_UNITS[$this->getType()][1];
    }


    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }


    /**
     * Returns the conversion list
     * 
     * @return array
     */
    public function getConversionList()
    {
        return $this->_UNITS;
    }


    /**
     * Alias function for setType returning the converted unit
     *
     * @param $type  type
     * @return
     */
    public function convertTo($type)
    {
        $this->setType($type);
        return $this->toString();
    }


    /**
     * Adds an unit to another one
     *
     * @param $object  object of same unit type
     * @return  Zend_Measure object
     */
    public function add($object)
    {
        $object->setType($this->getType());
        $value  = $this->getValue() + $object->getValue();

        $this->setValue($value, $this->getType(), $this->_Locale);
        return $this;
    }


    /**
     * Substracts an unit from another one
     *
     * @param $object  object of same unit type
     * @return  Zend_Measure object
     */
    public function sub($object)
    {
        $object->setType($this->getType());
        $value  = $this->getValue() - $object->getValue();
        
        $this->setValue($value, $this->getType(), $this->_Locale);
        return $this;
    }


    /**
     * Compares two units
     *
     * @param $object  object of same unit type
     * @return boolean
     */
    public function compare($object)
    {
        $object->setType($this->getType());
        $value  = $this->getValue() - $object->getValue();

        if ($value < 0) {
            return -1;
        } else if ($value > 0) {
            return 1;
        }
        return 0;
    }
}
