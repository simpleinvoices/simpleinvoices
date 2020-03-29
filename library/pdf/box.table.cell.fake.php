<?php

class FakeTableCellBox extends TableCellBox {
  var $colspan;
  var $rowspan;

  function create(&$pipeline) {
    $box =& new FakeTableCellBox;
    
    $css_state =& $pipeline->getCurrentCSSState();
    $css_state->pushDefaultState();

    $box->readCSS($css_state);

    $nullbox =& new NullBox;
    $nullbox->readCSS($css_state);
    $box->add_child($nullbox);

    $box->readCSS($css_state);

    $css_state->popState();

    return $box;
  }

  function FakeTableCellBox() {
    // Required to reset any constraints initiated by CSS properties
    $this->colspan = 1;
    $this->rowspan = 1;
    $this->GenericContainerBox();

    $this->setCSSProperty(CSS_DISPLAY, 'table-cell');
    $this->setCSSProperty(CSS_VERTICAL_ALIGN, VA_MIDDLE);
  }

  function show(&$viewport) {
    return true;
  }
  
  function is_fake() {
    return true;
  }

  function get_width_constraint() {
    return new WCNone();
  }

  function get_height_constraint() {
    return new HCConstraint(null, null, null);
  }

  function get_height() {
    return 0;
  }

  function get_top_margin() {
    return 0;
  }

  function get_full_height() {
    return 0;
  }

  function get_max_width() {
    return 0;
  }

  function get_min_width() {
    return 0;
  }
}

?>