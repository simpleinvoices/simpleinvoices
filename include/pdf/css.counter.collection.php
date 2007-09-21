<?php

class CSSCounterCollection {
  var $_counters;

  function CSSCounterCollection() {
    $this->_counters = array();
  }

  function add(&$counter) {
    $this->_counters[$counter->get_name()] =& $counter;
  }

  function &get($name) {
    if (!isset($this->_counters[$name])) {
      $null = null;
      return $null;
    };

    return $this->_counters[$name];
  }
}

?>