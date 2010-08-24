<?php

class FeatureFactory {
  var $_features;

  function FeatureFactory() {
    $this->_features = array();
  }

  function &get($name) {
    $instance =& FeatureFactory::get_instance();
    return $instance->_get($name);
  }

  function &_get($name) {
    if (!isset($this->__features[$name])) {
      $this->_features[$name] =& $this->_load($name);
    };
    return $this->_features[$name];
  }

  function &_load($name) {
    $normalized_name = strtolower(preg_replace('/[^\w\d\.]/i', '_', $name));
    $file_name = HTML2PS_DIR.'features/'.$normalized_name.'.php';
    $class_name = 'Feature'.join('',array_map('ucfirst',explode('.',$normalized_name)));

    if (!file_exists($file_name)) {
      $null = null;
      return $null;
    };

    require_once($file_name);
    $feature_object =& new $class_name;
    return $feature_object;
  }

  function &get_instance() {
    static $instance = null;
    if (is_null($instance)) {
      $instance =& new FeatureFactory();
    };

    return $instance;
  }
}

?>