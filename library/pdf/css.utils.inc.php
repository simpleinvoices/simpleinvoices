<?php
// $Header: /cvsroot/html2ps/css.utils.inc.php,v 1.30 2007/04/07 11:16:34 Konstantin Exp $

// TODO: make an OO-style selectors interface instead of switches

// Searches the CSS rule selector for pseudoelement selectors 
// (assuming that there can be only one) and returns its value
//
// note that there's not sence in applying pseudoelement to any chained selector except the last
// (the deepest descendant)
// 
function css_find_pseudoelement($selector) {
  $selector_type = selector_get_type($selector);
  switch ($selector_type) {
  case SELECTOR_PSEUDOELEMENT_BEFORE:
  case SELECTOR_PSEUDOELEMENT_AFTER:
    return $selector_type;
  case SELECTOR_SEQUENCE:
    foreach ($selector[1] as $subselector) {
      $pe = css_find_pseudoelement($subselector);
      if (!is_null($pe)) { 
        return $pe; 
      };
    }
    return null;
  default:
    return null;
  }
}

function _fix_tag_display($default_display, &$state, &$pipeline) {
  // In some cases 'display' CSS property should be ignored for element-generated boxes
  // Here we will use the $default_display stored above
  // Note that "display: none" should _never_ be changed
  //
  $handler =& CSS::get_handler(CSS_DISPLAY);
  if ($handler->get($state->getState()) === "none") {
    return;
  };

  switch ($default_display) {
  case 'table-cell':
    // TD will always have 'display: table-cell'
    $handler->css('table-cell', $pipeline);
    break;
    
  case '-button':
    // INPUT buttons will always have 'display: -button' (in latter case if display = 'block', we'll use a wrapper box)
    $css_state =& $pipeline->getCurrentCSSState();
    if ($handler->get($css_state->getState()) === 'block') {
      $need_block_wrapper = true;
    };
    $handler->css('-button', $pipeline);
    break;
  };
}

function is_percentage($value) { 
  return $value{strlen($value)-1} == "%"; 
}

/**
 * Handle escape sequences in CSS string values
 *
 * 4.3.7 Strings
 *
 * Strings can  either be  written with double  quotes or  with single
 * quotes.  Double quotes  cannot occur  inside double  quotes, unless
 * escaped (e.g., as '\"' or  as '\22'). Analogously for single quotes
 * (e.g., "\'" or "\27")...
 *
 * A string cannot directly contain a newline. To include a newline in
 * a string,  use an  escape representing the  line feed  character in
 * Unicode  (U+000A),  such  as  "\A"  or  "\00000a"...
 *
 * It is possible to break strings over several lines, for esthetic or
 * other reasons,  but in  such a  case the newline  itself has  to be
 * escaped  with a  backslash  (\).
 * 
 * 4.1.3 Characters and case
 * 
 * In  CSS 2.1,  a backslash  (\) character  indicates three  types of
 * character escapes.
 *
 * First,  inside a  string,  a  backslash followed  by  a newline  is
 * ignored  (i.e., the  string is  deemed  not to  contain either  the
 * backslash or the newline).
 *
 * Second,  it cancels  the  meaning of  special  CSS characters.  Any
 * character  (except  a hexadecimal  digit)  can  be  escaped with  a
 * backslash to  remove its  special meaning. For  example, "\""  is a
 * string consisting  of one  double quote. Style  sheet preprocessors
 * must not  remove these  backslashes from a  style sheet  since that
 * would change the style sheet's meaning.
 *
 * Third, backslash escapes allow  authors to refer to characters they
 * can't  easily put in  a document.  In this  case, the  backslash is
 * followed by at most  six hexadecimal digits (0..9A..F), which stand
 * for the  ISO 10646 ([ISO10646])  character with that  number, which
 * must not be  zero. If a character in  the range [0-9a-fA-F] follows
 * the  hexadecimal number, the  end of  the number  needs to  be made
 * clear. There are two ways to do that:
 *
 * 1. with a space (or other whitespace character): "\26 B" ("&B"). In
 *    this   case,   user  agents   should   treat   a  "CR/LF"   pair
 *    (U+000D/U+000A) as a single whitespace character.
 * 2. by providing exactly 6 hexadecimal digits: "\000026B" ("&B")
 *
 * In fact,  these two  methods may be  combined. Only  one whitespace
 * character  is ignored after  a hexadecimal  escape. Note  that this
 * means that  a "real"  space after the  escape sequence  must itself
 * either be escaped or doubled.
 */
function css_process_escapes($value) {
  $value = preg_replace_callback('/\\\\([\da-f]{1,6})( |[^][\da-f])/i',
                                 'css_process_escapes_callback',
                                 $value);

  $value = preg_replace_callback('/\\\\([\da-f]{6})( ?)/i', 
                                 'css_process_escapes_callback',
                                 $value);
  return $value;
}

function css_process_escapes_callback($matches) {
  if ($matches[2] == ' ') {
    return hex_to_utf8($matches[1]);
  } else {
    return hex_to_utf8($matches[1]).$matches[2];
  };
}

function css_remove_value_quotes($value) {
  if (strlen($value) == 0) { return $value; };

  if ($value{0} === "'" || $value{0} === "\"") {
    $value = substr($value, 1, strlen($value)-2);
  };
  return $value;
}

?>