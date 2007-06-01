<?php
// $Header: /cvsroot/html2ps/box.input.textarea.php,v 1.1 2006/03/19 09:25:35 Konstantin Exp $

class TextAreaInputBox extends InlineBlockBox {
  var $_field_name;
  var $_value;

  function &create(&$root, &$pipeline) {
    $value = $root->get_content();
    $name  = $root->get_attribute('name');
    $box = new TextAreaInputBox($value, $name);
    $box->create_content($root, $pipeline);
    return $box;
  }

  function get_min_width(&$context) { 
    return $this->get_max_width($context);
  } 

  function get_max_width(&$context) {
    return $this->get_width();
  }

  function TextAreaInputBox($value, $name) {
    $this->InlineBlockBox();

    $this->_value = $value;
    $this->_field_name  = $name;
  }

  function show(&$driver) {
    /**
     * If we're rendering the interactive form, the field content should not be rendered
     */
    global $g_config;
    if ($g_config['renderforms']) {
      $status = GenericFormattedBox::show($driver);

//       $driver->setfontcore('Helvetica', $this->font_size);
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