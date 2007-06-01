<?php
// $Header: /cvsroot/html2ps/css.utils.inc.php,v 1.23 2006/03/19 09:25:36 Konstantin Exp $

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
      if ($pe !== null) { return $pe; };
    }
    return null;
  default:
    return null;
  }
}

function _fix_tag_display($default_display, &$pipeline) {
  // In some cases 'display' CSS property should be ignored for element-generated boxes
  // Here we will use the $default_display stored above
  // Note that "display: none" should _never_ be changed
  //
  $handler =& get_css_handler('display');
  if ($handler->get() === "none") {
    return;
  };

  switch ($default_display) {
  case 'table-cell':
    // TD will always have 'display: table-cell'
    $handler->css('table-cell', $pipeline);
    break;
    
  case '-button':
    // INPUT buttons will always have 'display: -button' (in latter case if display = 'block', we'll use a wrapper box)
    if ($handler->get() === 'block') {
      $need_block_wrapper = true;
    };
    $handler->css('-button', $pipeline);
    break;
  };
}

function is_percentage($value) { return $value{strlen($value)-1} == "%"; }

function css_remove_value_quotes($value) {
  if (strlen($value) == 0) { return $value; };

  if ($value{0} === "'" || $value{0} === "\"") {
    $value = substr($value, 1, strlen($value)-2);
  };
  return $value;
}

function css_import($src, &$pipeline) {
// Update the base url; 
// all urls will be resolved relatively to the current stylesheet url
  $url = $pipeline->guess_url($src);

  $data = $pipeline->fetch($url);

  /**
   * If referred file could not be fetched return immediately
   */
  if (is_null($data)) { return; };

  $css = $data->get_content();
  if (!empty($css)) { 
    parse_css($css, $pipeline); 
  };
  
  $pipeline->pop_base_url();
};

?>