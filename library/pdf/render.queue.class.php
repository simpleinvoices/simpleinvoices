<?php

class RenderQueue {
  var $_root_context;

  function RenderQueue() {
    $this->set_root_context(null);
  }

  function get_root_context() {
    return $this->_root_context;
  }

  function set_root_context(&$context) {
    $this->_root_context =& $context;
  }
}

?>