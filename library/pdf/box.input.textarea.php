<?php
// $Header: /cvsroot/html2ps/box.input.textarea.php,v 1.5 2006/12/24 14:42:43 Konstantin Exp $

class TextAreaInputBox extends InlineBlockBox {
  var $_field_name;
  var $_value;

  function TextAreaInputBox($value, $name) {
    $this->InlineBlockBox();

    $this->set_value($value);
    $this->_field_name  = $name;
  }

  function &create(&$root, &$pipeline) {
    $value = $root->get_content();
    $name  = $root->get_attribute('name');

    $box = new TextAreaInputBox($value, $name);
    $box->readCSS($pipeline->getCurrentCSSState());
    $box->create_content($root, $pipeline);

    return $box;
  }

  function get_height() {
    $normal_height = parent::get_height();
    return $normal_height - $this->_get_vert_extra();
  }

  function get_min_width(&$context) { 
    return $this->get_max_width($context);
  } 

  function get_max_width(&$context) {
    return $this->get_width();
  }

  function get_value() {
    return $this->_value;
  }

  function get_width() {
    $normal_width = parent::get_width();
    return $normal_width - $this->_get_hor_extra();
  }

  function set_value($value) {
    $this->_value = $value;
  }

  function show(&$driver) {
    /**
     * If we're rendering the interactive form, the field content should not be rendered
     */
    global $g_config;
    if ($g_config['renderforms']) {
      $status = GenericFormattedBox::show($driver);

      $driver->field_multiline_text($this->get_left_padding(), 
                                    $this->get_top_padding(),
                                    $this->get_width()  + $this->get_padding_left() + $this->get_padding_right(),
                                    $this->get_height() + $this->get_padding_top()  + $this->get_padding_bottom(),
                                    $this->_value,
                                    $this->_field_name);
    } else {
      $status = GenericContainerBox::show($driver);
    }

    return $status;
  }
}

?>