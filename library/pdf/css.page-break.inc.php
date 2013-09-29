<?php

define('PAGE_BREAK_AUTO'  ,0);
define('PAGE_BREAK_ALWAYS',1);
define('PAGE_BREAK_AVOID' ,2);
define('PAGE_BREAK_LEFT'  ,3);
define('PAGE_BREAK_RIGHT' ,4);

class CSSPageBreak extends CSSPropertyStringSet {
  function CSSPageBreak() { 
    $this->CSSPropertyStringSet(false, 
                                false,
                                array('inherit' => CSS_PROPERTY_INHERIT,
                                      'auto'    => PAGE_BREAK_AUTO,
                                      'always'  => PAGE_BREAK_ALWAYS,
                                      'avoid'   => PAGE_BREAK_AVOID,
                                      'left'    => PAGE_BREAK_LEFT,
                                      'right'   => PAGE_BREAK_RIGHT)); 
  }

  function default_value() { 
    return PAGE_BREAK_AUTO; 
  }
}
?>