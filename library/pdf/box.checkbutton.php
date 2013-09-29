<?php
// $Header: /cvsroot/html2ps/box.checkbutton.php,v 1.21 2007/05/07 12:15:53 Konstantin Exp $

/**
 * @package HTML2PS
 * @subpackage Document
 *
 * This file contains the class describing layot and behavior of <input type="checkbox">
 * elements
 */

/**
 * @package HTML2PS
 * @subpackage Document
 * 
 * The CheckBox class desribes the layour of HTML checkboxes (they have HTML2PS-specific
 * '-checkbox' value of 'display' property)
 * 
 * Checkboxes have fixed size, which can be configured using CHECKBOX_SIZE constant 
 * in config.inc.php file. If "checked" attribute is present (whatever its value is),
 * a small cross is drawn inside the checkbox.
 *
 * @see CHECKBOX_SIZE
 *
 * @todo add "disabled" state
 */
class CheckBox extends GenericFormattedBox {
  /**
   * @var Boolean Flag indicating whether the check mark should be drawn
   * @access private
   */
  var $_checked;

  /**
   * @var String name of the corresponding form field
   * @access private
   */
  var $_name;

  /**
   * Notes: leading and trailing spaces are removed; if value is not specified,
   * checkbox is not rendered as ineractive control
   *
   * @var String value to be posted from ineractive form for this checkbox
   * @access private
   */
  var $_value;

  /**
   * Create a new checkbutton element using DOM tree element to initialize
   * it.
   *
   * @param DOMElement $root the DOM 'input' element 
   *
   * @return CheckBox new checkbox element
   *
   * @see CheckBox::CheckBox()
   */
  function &create(&$root, &$pipeline) {
    $value = $root->get_attribute('value');

    if (trim($value) == "") {
      error_log("Checkbox with empty 'value' attribute");
      $value = sprintf("___Value%s",md5(time().rand()));
    };

    $box =& new CheckBox($root->has_attribute('checked'), 
                         $root->get_attribute('name'),
                         $value);
    $box->readCSS($pipeline->getCurrentCSSState());
    $box->setup_dimensions();
    return $box;
  }

  /**
   * Create a new checkbox element with the given state
   * 
   * @param $checked flag inidicating if this box should be checked
   * 
   * @see CheckBox::create()
   */
  function CheckBox($checked, $name, $value) {
    $this->GenericFormattedBox();

    $this->_checked = $checked;
    $this->_name    = trim($name);
    $this->_value   = trim($value);
  }

  /**
   * Returns the width of the checkbox; not that max/min width does not 
   * make sense for the checkbuttons, as their width is always constant.
   *
   * @param FlowContext Context object describing current flow parameters (unused)
   * 
   * @return int width of the checkbox
   *
   * @see CheckBox::get_max_width
   */
  function get_min_width(&$context) { 
    return $this->width; 
  }
  
  /**
   * Returns the width of the checkbox; not that max/min width does not 
   * make sense for the checkbuttons, as their width is always constant.
   *
   * @param FlowContext Context object describing current flow parameters (unused)
   * 
   * @return int width of the checkbox
   *
   * @see CheckBox::get_min_width
   */
  function get_max_width(&$context) { 
    return $this->width; 
  }

  /**
   * Layout current checkbox element. Note that most CSS properties do not apply to the 
   * checkboxes; i.e. margin/padding values are ignored, checkboxes always aligned to 
   * to bottom of current line, etc.
   * 
   * @param GenericContainerBox $parent
   * @param FlowContext $context Context object describing current flow parameters 
   * 
   * @return Boolean flag indicating the error/success state; 'null' value in case of critical error 
   */
  function reflow(&$parent, &$context) {  
    GenericFormattedBox::reflow($parent, $context);
    
    /**
     * Check box size is constant (defined in config.inc.php) and is never affected
     * neither by CSS nor HTML. Call setup_dimensions once more to restore possible 
     * changes size
     * 
     * @see CHECKBOX_SIZE
     */
    $this->setup_dimensions();

    // set default baseline
    $this->baseline = $this->default_baseline;
    
//     // Vertical-align
//     $this->_apply_vertical_align($parent);

    /**
     * append to parent line box
     */ 
    $parent->append_line($this);

    /**
     * Determine coordinates of upper-left margin corner
     */
    $this->guess_corner($parent);

    /**
     * Offset parent current X coordinate
     */
    $parent->_current_x += $this->get_full_width();

    /**
     * Extend parents height to fit the checkbox
     */
    $parent->extend_height($this->get_bottom_margin());
  }

  /** 
   * Render the checkbox using the specified output driver
   *
   * @param OutputDriver $driver The output device driver object
   */
  function show(&$driver) {   
    /**
     * Get the coordinates of the check mark
     */
    $x = ($this->get_left() + $this->get_right()) / 2;
    $y = ($this->get_top() + $this->get_bottom()) / 2;

    /**
     * Calculate checkmark size; it looks nice when it takes 
     * 1/3 of the box size
     */
    $size = $this->get_width() / 3;

    /**
     * Draw the box
     */
    $driver->setrgbcolor(0,0,0);
    $driver->setlinewidth(0.25);
    $driver->moveto($x - $size, $y + $size);
    $driver->lineto($x + $size, $y + $size);
    $driver->lineto($x + $size, $y - $size);
    $driver->lineto($x - $size, $y - $size);
    $driver->closepath();
    $driver->stroke();
    
    /**
     * Render the interactive button (if requested and possible)
     * Also, field should be rendered only if name is not empty
     */
    global $g_config;
    if ($g_config['renderforms'] && $this->_name != "" && $this->_value != "") {
      $driver->field_checkbox($x - $size, 
                              $y + $size, 
                              2*$size, 
                              2*$size, 
                              $this->_name, 
                              $this->_value,
                              $this->_checked);
    } else {     
      /**
       * Draw check mark if needed
       */
      if ($this->_checked) { 
        $check_size = $this->get_width() / 6;
        
        $driver->moveto($x - $check_size, $y + $check_size);
        $driver->lineto($x + $check_size, $y - $check_size);
        $driver->stroke();
        
        $driver->moveto($x + $check_size, $y + $check_size);
        $driver->lineto($x - $check_size, $y - $check_size);
        $driver->stroke();
      }
    };

    return true;
  }

  function setup_dimensions() {
    $this->default_baseline = units2pt(CHECKBOX_SIZE);
    $this->height           = units2pt(CHECKBOX_SIZE);
    $this->width            = units2pt(CHECKBOX_SIZE);
  }
}
?>