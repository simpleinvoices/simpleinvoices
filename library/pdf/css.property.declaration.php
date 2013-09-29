<?php

class CSSPropertyDeclaration {
  var $_code;
  var $_value;
  var $_important;

  function CSSPropertyDeclaration() {
    $this->_code      = 0;
    $this->_value     = null;
    $this->_important = false;
  }

  function &getValue() {
    return $this->_value;
  }

  function setValue(&$value) {
    $this->_value =& $value;
  }

  function &create($code, $value, $pipeline) {
    $handler =& CSS::get_handler($code);
    if (is_null($handler)) {
      $null = null;
      return $null;
    };

    $declaration =& new CSSPropertyDeclaration();
    $declaration->_code = $code;

    if (preg_match("/^(.*)!\s*important\s*$/", $value, $matches)) {
      $value     = $matches[1];
      $declaration->_important = true;
    } else {
      $declaration->_important = false;
    };

    $declaration->_value = $handler->parse($value, $pipeline);
    return $declaration;
  }

  function getCode() {
    return $this->_code;
  }

  function &copy() {
    $declaration =& new CSSPropertyDeclaration();
    $declaration->_code = $this->_code;

    if (is_object($this->_value)) {
      $declaration->_value =& $this->_value->copy();
    } else {
      $declaration->_value =& $this->_value;
    };

    $declaration->_important = $this->_important;

    return $declaration;
  }
  
  function isImportant() {
    return $this->_important;
  }
}

?>