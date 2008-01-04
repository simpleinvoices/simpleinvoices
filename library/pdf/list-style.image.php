<?php

class ListStyleImage {
  var $_url;
  var $_image;

  function ListStyleImage($url, $image) {
    $this->_url = $url;
    $this->_image = $image;
  }

  function copy() {
    return new ListStyleImage($this->_url, $this->_image);
  }

  function is_default() { 
    return is_null($this->_url); 
  }
}

?>