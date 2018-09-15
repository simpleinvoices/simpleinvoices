<?php

class RenderQueue {
  var $_root_context;

  function __construct() {
    $this->set_root_context(null);
  }

  function get_root_context() {
    return $this->_root_context;
  }

  function set_root_context(&$context) {
    $this->_root_context =& $context;
  }
}
