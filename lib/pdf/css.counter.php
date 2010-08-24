<?php

class CSSCounter {
  var $_name;
  var $_value;
  
  function CSSCounter($name) {
    $this->set_name($name);
    $this->reset();
  }

  function get() {
    return $this->_value;
  }

  function get_name() {
    return $this->_name;
  }

  function reset() {
    $this->_value = 0;
  }

  function set($value) {
    $this->_value = $value;
  }

  function set_name($value) {
    $this->_name = $value;
  }
}

?>