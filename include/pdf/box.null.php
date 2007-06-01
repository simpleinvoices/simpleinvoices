<?php
// $Header: /cvsroot/html2ps/box.null.php,v 1.15 2006/05/27 15:33:26 Konstantin Exp $

class NullBox extends GenericInlineBox {
  function get_min_width(&$context) { return 0; }
  function get_max_width(&$context) { return 0; }
  function get_height() { return 0; }

  function NullBox() {
    // No CSS rules should be applied to null box
    push_css_defaults();
    $this->GenericFormattedBox();
    pop_css_defaults();
  }
  
  function &create(&$root, &$pipeline) { 
    $box =& new NullBox;
    return $box; 
  }

  function show(&$viewport) {
    return true;
  }

  function reflow_static(&$parent, &$context) {
    // Move current "box" to parent current coordinates. It is REQUIRED, 
    // as some other routines uses box coordinates.
    $this->put_left($parent->get_left());
    $this->put_top($parent->get_top());
  }

  function is_null() { return true; }
}
?>