<?php
// $Header: /cvsroot/html2ps/box.null.php,v 1.18 2006/07/09 09:07:44 Konstantin Exp $

class NullBox extends GenericInlineBox {
  function get_min_width(&$context, $limit = 10E6) { return 0; }
  function get_max_width(&$context, $limit = 10E6) { return 0; }
  function get_height() { return 0; }

  function __construct() {
    parent::__construct();
  }

  function &create() {
    $box =  new NullBox;

    $css_state = new CSSState(CSS::get());
    $css_state->pushState();
    $box->readCSS($css_state);

    return $box;
  }

  function show(&$viewport) {
    return true;
  }

  function reflow_static(&$parent, &$context) {
    if (!$parent) {
      $this->put_left(0);
      $this->put_top(0);
      return;
    };

    // Move current "box" to parent current coordinates. It is REQUIRED,
    // as some other routines uses box coordinates.
    $this->put_left($parent->get_left());
    $this->put_top($parent->get_top());
  }

  function is_null() { return true; }
}
?>