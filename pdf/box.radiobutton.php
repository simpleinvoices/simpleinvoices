<?php
// $Header: /cvsroot/html2ps/box.radiobutton.php,v 1.16 2006/03/22 19:02:36 Konstantin Exp $

class RadioBox extends GenericFormattedBox {
  var $_checked;

  /**
   * @var String name of radio button group
   * @access private
   */
  var $_group_name;

  /**
   * @var String value to be posted as this radio button value
   * @access private
   */
  var $_value;

  function &create(&$root, &$pipeline) {
    $checked = $root->has_attribute('checked');

    $value   = $root->get_attribute('value');
    if (trim($value) == "") {
      error_log("Radiobutton with empty 'value' attribute");
      $value = sprintf("___Value%s",md5(time().rand()));
    };

    $box =& new RadioBox($checked, $value);
    return $box;
  }

  function RadioBox($checked, $value) {
    // Call parent constructor
    $this->GenericFormattedBox();

    // Check the box state
    $this->_checked = $checked;

    /**
     * Store the form value for this radio button
     */
    $this->_value = trim($value);

    $handler =& get_css_handler('-html2ps-form-radiogroup');
    $this->_group_name = $handler->get();

    // Setup box size:
    $this->default_baseline = units2pt(CHECKBOX_SIZE);
    $this->height           = units2pt(CHECKBOX_SIZE);
    $this->width            = units2pt(CHECKBOX_SIZE);
  }

  // Inherited from GenericFormattedBox
  function get_min_width(&$context) { return $this->get_full_width($context); }
  function get_max_width(&$context) { return $this->get_full_width($context); }

  function reflow(&$parent, &$context) {  
    GenericFormattedBox::reflow($parent, $context);
    
    // set default baseline
    $this->baseline = $this->default_baseline;
    
//     // Vertical-align
//     $this->_apply_vertical_align($parent);

    // append to parent line box
    $parent->append_line($this);

    // Determine coordinates of upper-left _margin_ corner
    $this->guess_corner($parent);

    // Offset parent current X coordinate
    $parent->_current_x += $this->get_full_width();

    // Extends parents height
    $parent->extend_height($this->get_bottom_margin());
  }

  function show(&$driver) {   
    // Cet check center
    $x = ($this->get_left() + $this->get_right()) / 2;
    $y = ($this->get_top() + $this->get_bottom()) / 2;

    // Calculate checkbox size
    $size = $this->get_width() / 3;

    // Draw checkbox
    $driver->setlinewidth(0.25);
    $driver->circle($x, $y, $size);
    $driver->stroke();

    /**
     * Render the interactive button (if requested and possible)
     * Also, if no value were specified, then this radio button should not be interactive
     */
    global $g_config;
    if ($g_config['renderforms'] && $this->_value != "") {
      $driver->field_radio($x - $size, 
                           $y + $size, 
                           2*$size, 
                           2*$size,
                           $this->_group_name,
                           $this->_value,
                           $this->_checked);
    } else {
      // Draw checkmark if needed
      if ($this->_checked) { 
        $check_size = $this->get_width() / 6;

        $driver->circle($x, $y, $check_size);
        $driver->fill();
      }
    };

    return true;
  }
}
?>