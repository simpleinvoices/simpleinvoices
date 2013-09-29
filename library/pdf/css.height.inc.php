<?php
// $Header: /cvsroot/html2ps/css.height.inc.php,v 1.27 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR.'value.height.php');

class CSSHeight extends CSSPropertyHandler {
  var $_autoValue;

  function CSSHeight() { 
    $this->CSSPropertyHandler(true, false); 
    $this->_autoValue = ValueHeight::fromString('auto');
  }

  /**
   * 'height' CSS property should be inherited by table cells from table rows
   */
  function inherit($old_state, &$new_state) { 
    $parent_display = $old_state[CSS_DISPLAY];
    $this->replace_array(($parent_display === 'table-row') ? $old_state[CSS_HEIGHT] : $this->default_value(),
                         $new_state);
  }

  function _getAutoValue() {
    return $this->_autoValue->copy();
  }

  function default_value() { 
    return $this->_getAutoValue();
  }

  function parse($value) { 
    return ValueHeight::fromString($value);
  }

  function getPropertyCode() {
    return CSS_HEIGHT;
  }

  function getPropertyName() {
    return 'height';
  }
}
 
CSS::register_css_property(new CSSHeight);

?>