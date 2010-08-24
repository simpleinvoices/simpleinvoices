<?php

class Rectangle {
  var $ur;
  var $ll;
  
  function Rectangle($ll, $ur) {
    $this->ll = $ll;
    $this->ur = $ur;
  }

  function getWidth() {
    return $this->ur->x - $this->ll->x;
  }

  function getHeight() {
    return $this->ur->y - $this->ll->y;
  }

  function normalize() {
    if ($this->ur->x < $this->ll->x) {
      $x = $this->ur->x;
      $this->ur->x = $this->ll->x;
      $this->ll->x = $x;
    };

    if ($this->ur->y < $this->ll->y) {
      $y = $this->ur->y;
      $this->ur->y = $this->ll->y;
      $this->ll->y = $y;
    };
  }
}

?>