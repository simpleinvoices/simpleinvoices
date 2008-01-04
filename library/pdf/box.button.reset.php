<?php

class ButtonResetBox extends ButtonBox {
  /**
   * @var String URL to post the form to; may be null if this is not a 'submit' button
   * @access private
   */
  var $_action_url;

  function ButtonResetBox($text) {
    $this->ButtonBox($text);

    $handler =& get_css_handler('-html2ps-form-action');
    $this->_action_url = $handler->get();
  }

  function &create(&$root, &$pipeline) {
    if ($root->has_attribute("value")) {
      $text = $root->get_attribute("value");
    } else {
      $text = DEFAULT_RESET_TEXT;
    };

    $box =& new ButtonResetBox($text);
    return $box;
  }

  function _render_field(&$driver) {
    $driver->field_pushbuttonreset($this->get_left_padding(), 
                                   $this->get_top_padding(), 
                                   $this->get_width() + $this->get_padding_left() + $this->get_padding_right(), 
                                   $this->get_height() + $this->get_padding_top() + $this->get_padding_bottom());
  }
}

?>