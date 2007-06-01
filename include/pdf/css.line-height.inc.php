<?php
// $Header: /cvsroot/html2ps/css.line-height.inc.php,v 1.12 2006/03/26 14:01:12 Konstantin Exp $

class LineHeight_Absolute {
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

  function copy() {
    $value = new LineHeight_Absolute($this->length);
    return $value;
  }
}

class LineHeight_Relative {
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

  function copy() {
    $value = new LineHeight_Relative($this->fraction);
    return $value;
  }
}

function is_default_line_height($value) { 
  return $value == default_line_height(); 
};
function default_line_height() { return new LineHeight_Relative(1.1); };
function get_line_height() { global $g_line_height; return $g_line_height[0]; }
function push_line_height($align) { global $g_line_height; array_unshift($g_line_height, $align); }
function pop_line_height() { global $g_line_height; array_shift($g_line_height); }

function css_line_height($value, $root) { 
  pop_line_height(); 

  // <Number>
  // The used value of the property is this number multiplied by the element's font size. 
  // Negative values are illegal. The computed value is the same as the specified value.
  if (preg_match("/^\d+(\.\d+)?$/",$value)) { 
    push_line_height(new LineHeight_Relative((float)$value)); 
    return; 
  };

  // <percentage>
  // The computed value of the property is this percentage multiplied by the element's 
  // computed font size. Negative values are illegal.  
  if (preg_match("/^\d+%$/",$value)) { 
    push_line_height(new LineHeight_Relative(((float)$value)/100)); 
    return; 
  };

  // normal
  // Tells user agents to set the used value to a "reasonable" value based on the font of the element. 
  // The value has the same meaning as <number>. We recommend a used value for 'normal' between 1.0 to 1.2. 
  // The computed value is 'normal'.
  if (trim($value) === "normal") { 
    push_line_height(default_line_height());
    return;
  };
  
  // <length>
  // The specified length is used in the calculation of the line box height. 
  // Negative values are illegal.  
  push_line_height(new LineHeight_Absolute($value));
};

$g_line_height = array(default_line_height());
?>
