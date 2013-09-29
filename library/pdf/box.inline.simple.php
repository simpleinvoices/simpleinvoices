<?php

require_once(HTML2PS_DIR.'box.generic.formatted.php');

class SimpleInlineBox extends GenericBox {
  function SimpleInlineBox() {
    $this->GenericBox();
  }

  function readCSS(&$state) {
    parent::readCSS($state);

    $this->_readCSS($state,
                    array(CSS_TEXT_DECORATION,
                          CSS_TEXT_TRANSFORM));
    
    // '-html2ps-link-target'
    global $g_config;
    if ($g_config["renderlinks"]) {
      $this->_readCSS($state, 
                      array(CSS_HTML2PS_LINK_TARGET));
    };
  }

  function get_extra_left() {
    return 0;
  }

  function get_extra_top() {
    return 0;
  }

  function get_extra_right() {
    return 0;
  }

  function get_extra_bottom() {
    return 0;
  }

  function show(&$driver) {
    parent::show($driver);

    $strategy =& new StrategyLinkRenderingNormal();
    $strategy->apply($this, $driver);
  }
}
?>