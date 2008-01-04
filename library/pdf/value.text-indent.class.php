<?php

require_once(HTML2PS_DIR.'value.generic.php');

// Stack value is an array containing two values:
// string value of text-indent proprty and 
// boolean flag indication of this value is relative (true) or absolute (false)

class TextIndentValuePDF extends CSSValue {
  var $raw_value;

  function calculate(&$box) {
    if ($this->raw_value[1]) {
      // Is a percentage
      return $box->get_width() * $this->raw_value[0] / 100;
    } else {
      return $this->raw_value[0];
    };
  }

  function &copy() {
    $value =& new TextIndentValuePDF($this->raw_value);
    return $value;
  }

  function is_default() {
    return $this->raw_value[0] == 0;
  }

  function TextIndentValuePDF($value) {
    $this->raw_value = $value;
  }

  function units2pt($base) {
    $this->raw_value[0] = units2pt($this->raw_value[0], $base);
  }
}

?>