<?php

class CSSPropertyStringSet extends CSSPropertyHandler {
  var $_mapping;
  var $_keys;

  function CSSPropertyStringSet($inherit, $inherit_text, $mapping) {
    $this->CSSPropertyHandler($inherit, $inherit_text);

    $this->_mapping = $mapping;

    /**
     * Unfortunately, isset($this->_mapping[$key]) will return false
     * for $_mapping[$key] = null. As CSS_PROPERTY_INHERIT value is 'null',
     * this should be avoided using the hack below
     */
    $this->_keys    = $mapping;
    foreach ($this->_keys as $key => $value) {
      $this->_keys[$key] = 1;
    };
  }

  function parse($value) {
    $value = trim(strtolower($value));

    if (isset($this->_keys[$value])) {
      return $this->_mapping[$value];
    };

    return $this->default_value();
  }
}

?>