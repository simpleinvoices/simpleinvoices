<?php

class CSSSubProperty extends CSSPropertyHandler {
  var $_owner;

  function CSSSubProperty(&$owner) {
    $this->_owner =& $owner;
  }

  function &get(&$state) {
    $owner =& $this->owner();
    $value =& $owner->get($state);
    $subvalue =& $this->getValue($value);
    return $subvalue;
  }

  function is_subproperty() { 
    return true; 
  }

  function &owner() { 
    return $this->_owner; 
  }
 
  function default_value() { 
  }

  function inherit($old_state, &$new_state) { 
  }

  function inherit_text($old_state, &$new_state) { 
  }

  function replace_array($value, &$state_array) {
    $owner =& $this->owner();

    $owner_value = $state_array[$owner->getPropertyCode()];

    if (is_object($owner_value)) {
      $owner_value = $owner_value->copy();
    };

    if (is_object($value)) {
      $this->setValue($owner_value, $value->copy());
    } else {
      $this->setValue($owner_value, $value);
    };

    $state_array[$owner->getPropertyCode()] = $owner_value;
  }

  function replace($value, &$state) { 
    $owner =& $this->owner();
    $owner_value = $owner->get($state->getState());

    if (is_object($owner_value)) {
      $owner_value =& $owner_value->copy();
    };

    if (is_object($value)) {
      $value_copy =& $value->copy();
      $this->setValue($owner_value, $value_copy);
    } else {
      $this->setValue($owner_value, $value);
    };

    $owner->replaceDefault($owner_value, $state);
    $state->setPropertyDefaultFlag($this->getPropertyCode(), false);
  }

  function setValue(&$owner_value, &$value) {
    error_no_method('setValue', get_class($this));
  }

  function &getValue(&$owner_value) {
    error_no_method('getValue', get_class($this));
  }
}

?>