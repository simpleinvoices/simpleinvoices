<?php

class Point {
  var $x;
  var $y;
  
  function Point($x, $y) {
    $this->x = $x;
    $this->y = $y;
  }

  function _clone() {
    return new Point($this->x, $this->y);
  }
}

?>