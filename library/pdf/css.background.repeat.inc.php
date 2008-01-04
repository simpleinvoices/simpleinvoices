<?php
// $Header: /cvsroot/html2ps/css.background.repeat.inc.php,v 1.7 2006/04/16 16:54:56 Konstantin Exp $

define('BR_REPEAT',0);
define('BR_REPEAT_X',1);
define('BR_REPEAT_Y',2);
define('BR_NO_REPEAT',3);

class CSSBackgroundRepeat extends CSSSubProperty {
  function default_value() { return BR_REPEAT; }

  function parse($value) {
    // Note that we cannot just compare $value with these strings for equality, 
    // as 'parse' can be called with composite 'background' value as a parameter,
    // say, 'black url(picture.gif) repeat', instead of just using 'repeat'

    // Also, note that 
    // 1) 'repeat-x' value will match 'repeat' term 
    // 2) background-image 'url' values may contain these values as substrings
    // to avoid these problems, we'll add spaced to the beginning and to the end of value,
    // and will search for space-padded values, instead of raw substrings
    $value = " ".$value." ";
    if (strpos($value, ' repeat-x ')  !== false) { return BR_REPEAT_X; };
    if (strpos($value, ' repeat-y ')  !== false) { return BR_REPEAT_Y; };
    if (strpos($value, ' no-repeat ') !== false) { return BR_NO_REPEAT; };
    if (strpos($value, ' repeat ')    !== false) { return BR_REPEAT; };
    return CSSBackgroundRepeat::default_value();
  }
}

?>