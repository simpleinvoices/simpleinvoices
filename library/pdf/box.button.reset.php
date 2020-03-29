<?php

class ButtonResetBox extends ButtonBox {
  function ButtonResetBox($text) {
    $this->ButtonBox($text);
  }

  function &create(&$root, &$pipeline) {
    if ($root->has_attribute("value")) {
      $text = $root->get_attribute("value");
    } else {
      $text = DEFAULT_RESET_TEXT;
    };

    $box =& new ButtonResetBox($text);
    $box->readCSS($pipeline->getCurrentCSSState());

    return $box;
  }

  function readCSS(&$state) {
    parent::readCSS($state);
    
    $this->_readCSS($state, 
                    array(CSS_HTML2PS_FORM_ACTION));
  }

  function _render_field(&$driver) {
    $driver->field_pushbuttonreset($this->get_left_padding(), 
                                   $this->get_top_padding(), 
                                   $this->get_width() + $this->get_padding_left() + $this->get_padding_right(), 
                                   $this->get_height() + $this->get_padding_top() + $this->get_padding_bottom());
  }
}

?>