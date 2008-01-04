<?php

require_once(HTML2PS_DIR.'path.point.php');
require_once(HTML2PS_DIR.'path.rectangle.php');

class Path {
  var $_points;

  function Path() {
    $this->clear();
  }

  /**
   * Returns a bounding box rectangle object
   *
   * Pre-conditions:
   * - there's at least one point in the path
   */
  function getBbox() {
    if (count($this->_points) < 1) {
      die("Path::getBbox() called for path without points");
    }

    $rect = new Rectangle($this->_points[0]->_clone(),
                          $this->_points[0]->_clone());

    foreach ($this->_points as $point) {
      $rect->ur->x = max($rect->ur->x, $point->x);
      $rect->ur->y = max($rect->ur->y, $point->y);
      $rect->ll->x = min($rect->ll->x, $point->x);
      $rect->ll->y = min($rect->ll->y, $point->y);
    };

    return $rect;
  }

  function clear() {
    $this->_points = array();
  }

  function addPoint($point) {
    $this->_points[] = $point;
  }

  function getPoint($index) {
    return $this->_points[$index];
  }

  function getPoints() {
    return $this->_points;
  }

  function getPointArray() {
    $result = array();
    foreach ($this->_points as $point) {
      $result[] = $point->x;
      $result[] = $point->y;
    };
    return $result;
  }

  function close() {
    $this->addPoint($this->getPoint(0));
  }

  function get_point_count() {
    return count($this->_points);   
  }

  /**
   * @deprecated
   */
  function getPointCount() {
    return $this->get_point_count();
  }

  function is_empty() {
    return ($this->get_point_count() == 0);
  }

  function fill($transform, $image, $color) {
    $coords = $this->getPointArray();
    $size   = $this->getPointCount();

    for ($i=0; $i<$size; $i++) {
      $transform->apply($coords[$i*2], $coords[$i*2+1]);
    };

    imagefilledpolygon($image, $coords, $size, $color);
  }

  function stroke($transform, $image, $color) {
    $coords = $this->getPointArray();
    $size   = $this->getPointCount();

    for ($i=0; $i<$size; $i++) {
      $transform->apply($coords[$i*2], $coords[$i*2+1]);
    };

    imagepolygon($image, $coords, $size, $color);
  }
}

class PathCircle extends Path {
  var $_x;
  var $_y;
  var $_r;

  function PathCircle() {
    $this->Path();

    $this->set_x(0);
    $this->set_y(0);
    $this->set_r(0);
  }

  function fill($transform, $image, $color) {
    $x = $this->get_x();
    $y = $this->get_y();

    $transform->apply($x, $y);

    $dummy = 0;
    $transform->apply($r, $dummy);

    imagefilledellipse($image, 
                       $x,
                       $y,
                       $r*2,  // width = diameter
                       $r*2,  // height = diameter
                       $color);
  }

  function get_r() {
    return $this->_r;
  }

  function get_x() {
    return $this->_x;
  }

  function get_y() {
    return $this->_y;
  }

  function set_r($r) {
    $this->_r = $r;
  }

  function set_x($x) {
    $this->_x = $x;
  }

  function set_y($y) {
    $this->_y = $y;
  }

  function stroke($transform, $image, $color) {
    $x = $this->get_x();
    $y = $this->get_y();

    $transform->apply($x, $y);

    $dummy = 0;
    $transform->apply($r, $dummy);

    imageellipse($image, 
                 $x,
                 $y,
                 $r*2,  // width = diameter
                 $r*2,  // height = diameter
                 $color);
  }
}

?>