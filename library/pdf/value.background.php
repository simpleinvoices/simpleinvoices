<?php

require_once(HTML2PS_DIR.'value.generic.php');

/**
 * @package HTML2PS
 * @subpackage Document
 * Represents the 'background' CSS property value (including 'background-...' subproperties).
 *
 * @link http://www.w3.org/TR/CSS21/colors.html#propdef-background CSS 2.1 'background' property description
 */
class Background extends CSSValue {
  /**
   * @var Color Contains the 'background-color' CSS subproperty value
   * @access private
   */
  var $_color;

  /**
   * @var BackgroundImage Contains the 'background-image' CSS subproperty value
   * @access private
   */
  var $_image;

  /**
   * @var int Contains the 'background-repeat' CSS subproperty value (enumeration)
   * @access private
   */
  var $_repeat;

  /**
   * @var BackgroundPosition Containg the 'background-position' CSS subproperty value
   * @access private
   */
  var $_position;

  var $_attachment;

  /**
   * Constructs a new object containing a background information 
   *
   * @param Color $color 'background-color' value
   * @param BackgroundImage $image 'background-image' value
   * @param int $repeat 'background-repeat' value
   * @param BackgroundPosition $position 'background-position' value
   */
  function Background($color, $image, $repeat, $position, $attachment) {
    $this->_color      = $color;
    $this->_image      = $image;
    $this->_repeat     = $repeat;
    $this->_position   = $position;
    $this->_attachment = $attachment;
  }

  /**
   * "Deep copy" routine
   *
   * @return Background A copy of current object
   */
  function &copy() {
    $value =& new Background(is_null($this->_color) ? null : $this->_color->copy(), 
                             is_null($this->_image) ? null : $this->_image->copy(),
                             $this->_repeat,
                             is_null($this->_position) ? null : $this->_position->copy(),
                             $this->_attachment);

    return $value;
  }

  /**
   * Tests if the 'background' CSS property value is the default property value; e.g.
   * all subproperty values are set to defaults.
   * 
   * @return bool Flag indicating if current object have default value
   *
   * @see CSSBackgroundColor::default_value
   * @see BackgroundImage::is_default
   * @see CSSBackgroundRepeat::default_value
   * @see BackgroundPosition::is_default
   */
  function is_default() {
    return 
      $this->_color->equals(CSSBackgroundColor::default_value()) &&
      $this->_image->is_default() &&
      $this->_repeat == CSSBackgroundRepeat::default_value() &&
      $this->_position->is_default() &&
      $this->_attachment->is_default();
  }

  /**
   * Renders the background for the given box object using an output driver
   *
   * @param OutputDriver $driver Output driver to be used
   * @param GenericFormattedBox $box Box the background is rendered for
   *
   * @uses GenericFormattedBox
   * @uses OutputDriver
   */
  function show(&$driver, &$box) {
    /**
     * Fill box with background color
     *
     * @see Color::apply
     * @see OutputDriver::moveto
     * @see OutputDriver::lineto
     * @see OutputDriver::closepath
     * @see OutputDriver::fill
     */
    if (!$this->_color->transparent) {
      $this->_color->apply($driver);
      $driver->moveto($box->get_left_background(), $box->get_top_background());
      $driver->lineto($box->get_right_background(), $box->get_top_background());
      $driver->lineto($box->get_right_background(), $box->get_bottom_background());
      $driver->lineto($box->get_left_background(), $box->get_bottom_background());
      $driver->closepath();
      $driver->fill();
    };

    /**
     * Render background image
     *
     * @see BackgroundImage::show
     */
    $this->_image->show($driver, $box, $this->_repeat, $this->_position, $this->_attachment);   
  }

  /** 
   * Converts the absolute lengths used in subproperties (if any) to the device points
   * 
   * @param float $font_size Font size to use during conversion of 'ex' and 'em' units
   */
  function units2pt($font_size) {
    $this->_position->units2pt($font_size);
  }

  function doInherit(&$state) {
    if ($this->_color === CSS_PROPERTY_INHERIT) {
      $value =& $state->getInheritedProperty(CSS_BACKGROUND_COLOR);
      $this->_color = $value->copy();
    };
    
    if ($this->_image === CSS_PROPERTY_INHERIT) {
      $value =& $state->getInheritedProperty(CSS_BACKGROUND_IMAGE);
      $this->_image = $value->copy();
    };

    if ($this->_position === CSS_PROPERTY_INHERIT) {
      $value =& $state->getInheritedProperty(CSS_BACKGROUND_POSITION);
      $this->_position = $value->copy();
    };

    if ($this->_repeat === CSS_PROPERTY_INHERIT) {
      $this->_repeat = $state->getInheritedProperty(CSS_BACKGROUND_REPEAT);
    };

    if ($this->_attachment === CSS_PROPERTY_INHERIT) {
      $this->_attachment =& $state->getInheritedProperty(CSS_BACKGROUND_ATTACHMENT);
    };
  }
}

?>