<?php
// $Header: /cvsroot/html2ps/css.text-indent.inc.php,v 1.10 2006/03/26 14:01:13 Konstantin Exp $

// Stack value is an array containing two values:
// string value of text-indent proprty and 
// boolean flag indication of this value is relative (true) or absolute (false)

class TextIndentValuePDF {
  var $raw_value;

  function calculate(&$box) {
    if ($this->raw_value[1]) {
      // Is a percentage
      return $box->get_width() * $this->raw_value[0] / 100;
    } else {
      return $this->raw_value[0];
    };
  }

  function copy() {
    return new TextIndentValuePDF($this->raw_value);
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

class CSSTextIndent extends CSSProperty {
  function CSSTextIndent() { $this->CSSProperty(true, true); }

  function default_value() { return new TextIndentValuePDF(array(0,false)); }

  function parse($value) {
    if (is_percentage($value)) { 
      return new TextIndentValuePDF(array((int)$value, true));
    } else {
      return new TextIndentValuePDF(array($value, false));
    };
  }
}

register_css_property('text-indent', new CSSTextIndent());

?>
