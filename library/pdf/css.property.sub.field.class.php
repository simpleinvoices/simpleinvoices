<?php

class CSSSubFieldProperty extends CSSSubProperty {
  var $_owner;
  var $_owner_field;

  function CSSSubFieldProperty(&$owner, $field) {
    $this->CSSSubProperty($owner);
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

?>