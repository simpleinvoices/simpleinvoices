<?php

class RenderStackingLevel {
  var $_boxes;

  function RenderStackingLevel() {
    $this->set_boxes(array());
  }

  function get_boxes() {
    return $this->_boxes;
  }

  function set_boxes($boxes) {
    $this->_boxes = $boxes;
  }
}

?>