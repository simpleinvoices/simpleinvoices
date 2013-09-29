<?php
// $Header: /cvsroot/html2ps/css.min-height.inc.php,v 1.3 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR.'value.min-height.php');

class CSSMinHeight extends CSSPropertyHandler {
  var $_defaultValue;

  function CSSMinHeight() { 
    $this->CSSPropertyHandler(true, false); 
    $this->_defaultValue = ValueMinHeight::fromString("0px");
  }

  /**
   * 'height' CSS property should be inherited by table cells from table rows
   * (as, obviously, )
   */
  function inherit($old_state, &$new_state) { 
    $parent_display = $old_state[CSS_DISPLAY];
    if ($parent_display === "table-row") {
      $new_state[CSS_MIN_HEIGHT] = $old_state[CSS_MIN_HEIGHT];
      return;
    }

    $new_state[CSS_MIN_HEIGHT] = 
      is_inline_element($parent_display) ? 
      $this->get($old_state) : 
      $this->default_value();
  }

  function _getAutoValue() {
    return $this->default_value();
  }

  function default_value() { 
    return $this->_defaultValue->copy();
  }

  function parse($value) { 
    return ValueMinHeight::fromString($value);
  }

  function getPropertyCode() {
    return CSS_MIN_HEIGHT;
  }

  function getPropertyName() {
    return 'min-height';
  }
}
 
CSS::register_css_property(new CSSMinHeight);

?>