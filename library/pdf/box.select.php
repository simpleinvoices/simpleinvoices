<?php
// $Header: /cvsroot/html2ps/box.select.php,v 1.24 2007/01/03 19:39:29 Konstantin Exp $

class SelectBox extends InlineControlBox {
  var $_name;
  var $_value;
  var $_options;

  function SelectBox($name, $value, $options) {
    // Call parent constructor
    $this->InlineBox();

    $this->_name    = $name;
    $this->_value   = $value;
    $this->_options = $options;
  }

  function &create(&$root, &$pipeline) {
    $name = $root->get_attribute('name');

    $value = '';
    $options = array();

    // Get option list
    $child = $root->first_child();
    $content = '';
    $size = 0;
    while ($child) {
      if ($child->node_type() == XML_ELEMENT_NODE) {
        $size = max($size, strlen($child->get_content()));
        if (empty($content) || $child->has_attribute('selected')) { 
          $content = preg_replace('/\s/',' ',$child->get_content());
          $value   = trim($child->get_content());
        };

        if ($child->has_attribute('value')) {
          $options[] = array($child->get_attribute('value'),
                             $child->get_content());
        } else {
          $options[] = array($child->get_content(),
                             $child->get_content());
        };
      };
      $child = $child->next_sibling();
    };
    $content = str_pad($content, $size*SIZE_SPACE_KOEFF + SELECT_SPACE_PADDING, ' ');

    $box =& new SelectBox($name, $value, $options);
    $box->readCSS($pipeline->getCurrentCSSState());
    $box->setup_content($content, $pipeline);

    return $box;
  }

  function show(&$driver) {   
    global $g_config;
    if ($g_config['renderforms']) {
      return $this->show_field($driver);
    } else {
      return $this->show_rendered($driver);
    };
  }

  function show_field(&$driver) {
    if (is_null(GenericFormattedBox::show($driver))) {
      return null;
    };

    $driver->field_select($this->get_left_padding(), 
                          $this->get_top_padding(),
                          $this->get_width()  + $this->get_padding_left() + $this->get_padding_right(),
                          $this->get_height(),
                          $this->_name,
                          $this->_value,
                          $this->_options);
    return true;
  }

  function show_rendered(&$driver) {
    // Now set the baseline of a button box to align it vertically when flowing isude the 
    // text line
    $this->default_baseline = $this->content[0]->baseline + $this->get_extra_top();
    $this->baseline         = $this->content[0]->baseline + $this->get_extra_top();

    if (is_null(GenericContainerBox::show($driver))) {
      return null;
    };

    $this->show_button($driver);
    return true;
  }

  function show_button(&$driver) {
    $padding = $this->getCSSProperty(CSS_PADDING);
    $button_height = $this->get_height() + $padding->top->value + $padding->bottom->value;

    // Show arrow button box
    $driver->setrgbcolor(0.93, 0.93, 0.93);
    $driver->moveto($this->get_right_padding(), $this->get_top_padding());
    $driver->lineto($this->get_right_padding() - $button_height, $this->get_top_padding());
    $driver->lineto($this->get_right_padding() - $button_height, $this->get_bottom_padding());
    $driver->lineto($this->get_right_padding(), $this->get_bottom_padding());
    $driver->closepath();
    $driver->fill();

    // Show box boundary
    $driver->setrgbcolor(0,0,0);
    $driver->moveto($this->get_right_padding(), $this->get_top_padding());
    $driver->lineto($this->get_right_padding() - $button_height, $this->get_top_padding());
    $driver->lineto($this->get_right_padding() - $button_height, $this->get_bottom_padding());
    $driver->lineto($this->get_right_padding(), $this->get_bottom_padding());
    $driver->closepath();
    $driver->stroke();
  
    // Show arrow
    $driver->setrgbcolor(0,0,0);
    $driver->moveto($this->get_right_padding() - SELECT_BUTTON_TRIANGLE_PADDING,
                      $this->get_top_padding() - SELECT_BUTTON_TRIANGLE_PADDING);
    $driver->lineto($this->get_right_padding() - $button_height + SELECT_BUTTON_TRIANGLE_PADDING, 
                      $this->get_top_padding() - SELECT_BUTTON_TRIANGLE_PADDING);
    $driver->lineto($this->get_right_padding() - $button_height/2, $this->get_bottom_padding() + SELECT_BUTTON_TRIANGLE_PADDING);
    $driver->closepath();
    $driver->fill();

    return true;
  }
}
?>