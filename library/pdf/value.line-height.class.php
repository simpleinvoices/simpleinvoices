<?php

require_once(HTML2PS_DIR.'value.generic.php');

class LineHeight_Absolute extends CSSValue {
  var $length;

  function apply($value) { 
    return $this->length; 
  }

  function is_default() { 
    return false; 
  }

  function LineHeight_Absolute($value) { 
    $this->length = $value; 
  }

  function units2pt($base) { 
    $this->length = units2pt($this->length, $base); 
  }

  function &copy() {
    $value =& new LineHeight_Absolute($this->length);
    return $value;
  }
}

class LineHeight_Relative extends CSSValue {
  var $fraction;

  function apply($value) { 
    return $this->fraction * $value; 
  }

  function is_default() { 
    return $this->fraction == 1.1; 
  }

  function LineHeight_Relative($value) { 
    $this->fraction = $value; 
  }

  function units2pt($base) { }

  function &copy() {
    $value =& new LineHeight_Relative($this->fraction);
    return $value;
  }
}

?>