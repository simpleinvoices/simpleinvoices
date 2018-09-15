<?php

class CSSSubFieldProperty extends CSSSubProperty {
  var $_owner;
  var $_owner_field;

  function __construct(&$owner, $field) {
    parent::__construct($owner);
    $this->_owner_field = $field;
  }

  function setValue(&$owner_value, &$value) {
    $field = $this->_owner_field;
    $owner_value->$field = $value;
  }

  function &getValue(&$owner_value) {
    $field = $this->_owner_field;
    return $owner_value->$field;
  }
}
