<?php
define('PAGE_BREAK_AUTO'  ,0);
define('PAGE_BREAK_ALWAYS',1);
define('PAGE_BREAK_AVOID' ,2);
define('PAGE_BREAK_LEFT'  ,3);
define('PAGE_BREAK_RIGHT' ,4);

class CSSPageBreak extends CSSProperty {
  function CSSPageBreak() { 
    $this->CSSProperty(false, false); 
  }

  function default_value() { return PAGE_BREAK_AUTO; }

  function parse($value) {
    switch (strtolower($value)) {
    case 'auto':
      return PAGE_BREAK_AUTO;
    case 'always':
      return PAGE_BREAK_ALWAYS;
    case 'avoid':
      return PAGE_BREAK_AVOID;
    case 'left':
      return PAGE_BREAK_LEFT;
    case 'right':
      return PAGE_BREAK_RIGHT;
    default:
      return PAGE_BREAK_AUTO;
    };
  }
}
?>