<?php
class FlowContext {
  var $absolute_positioned;
  var $fixed_positioned;

  var $viewport;
  var $_floats;
  var $collapsed_margins;
  var $container_uid;

  function add_absolute_positioned(&$box) {
    $this->absolute_positioned[] =& $box;
  }

  function add_fixed_positioned(&$box) {
    $this->fixed_positioned[] =& $box;
  }

  function add_float(&$float) {
    $this->_floats[0][] =& $float;
  }

  function container_uid() { 
    return $this->container_uid[0];
  }

  function &current_floats() {
    return $this->_floats[0];
  }

  // Get the bottom edge coordinate of the bottommost float in 
  // current formatting context
  //
  // @return null in case of no floats exists in current context
  // numeric coordinate value otherwise
  // 
  function float_bottom() {
    $floats =& $this->current_floats();

    if (count($floats) == 0) { return null; }

    $bottom = $floats[0]->get_bottom_margin();
    $size = count($floats);
    for ($i=1; $i<$size; $i++) {
      $bottom = min($bottom, $floats[$i]->get_bottom_margin());
    };

    return $bottom;
  }

  // Calculates the leftmost x-coordinate not covered by floats in current context 
  // at the given level (y-coordinate)
  //
  // @param $x starting X coordinate (no point to the left of this allowed)
  // @param $y Y coordinate we're searching at
  // @return the leftmost X coordinate value
  //
  function float_left_x($x, $y) {
    $floats =& $this->current_floats();

    $size = count($floats);
    for ($i=0; $i<$size; $i++) {
      $float =& $floats[$i];

      // Process only left-floating boxes
      if ($float->getCSSProperty(CSS_FLOAT) == FLOAT_LEFT) {
        // Check if this float contains given Y-coordinate
        //
        // Note that top margin coordinate is inclusive but 
        // bottom margin coordinate is exclusive! The cause is following:
        // - if we have several floats in one line, their top margin edge Y coordinates will be equal,
        //   so we must use agreater or equal sign to avod placing all floats at one X coordinate
        // - on the other side, if we place one float under the other, the top margin Y coordinate 
        //   of bottom float will be equal to bottom margin Y coordinate of the top float and 
        //   we should NOT offset tho bottom float in this case
        //

        if ($float->get_top_margin() + EPSILON >= $y &&
            $float->get_bottom_margin() < $y) {
          $x = max($x, $float->get_right_margin());
        };
      };
    };
    
    return $x;
  }

  // Calculates position of left floating box (taking into account the possibility 
  // of "wrapping" float to next line in case we have not enough space at current level (Y coordinate)
  //
  // @param $parent reference to a parent box
  // @param $width width of float being placed. Full width! so, extra horizontal space (padding, margins and borders) is added here too
  // @param $x [out] X coordinate of float upper-left corner
  // @param $y [in,out] Y coordinate of float upper-left corner
  //
  function float_left_xy(&$parent, $width, &$x, &$y) {
    // Numbler of floats to clear; we need this because of the following example:
    // <div style="width: 150px; background-color: red; padding: 5px;">
    // <div style="float: left; background-color: green; height: 40px; width: 100px;">T</div>
    // <div style="float: left; background-color: yellow; height: 20px; width: 50px;">T</div>
    // <div style="float: left; background-color: cyan; height: 20px; width: 50px;">T</div>
    // in this case the third float will be rendered directly under the second, so only the 
    // second float should be cleared
    
    $clear = 0;

    $floats =& $this->current_floats();

    // Prepare information about the float bottom coordinates
    $float_bottoms = array();
    $size = count($floats);
    for ($i=0; $i<$size; $i++) {
      $float_bottoms[] = $floats[$i]->get_bottom_margin();
    };

    // Note that the sort function SHOULD NOT maintain key-value assotiations!
    rsort($float_bottoms); 
   
    do {
      $x  = $this->float_left_x($parent->get_left(), $y);
      
      // Check if current float will fit into the parent box
      // OR if there's no parent boxes with constrained width (it will expanded in this case anyway)

      // small value to hide the rounding errors
      $parent_wc = $parent->getCSSProperty(CSS_WIDTH);
      if ($parent->get_right() + EPSILON >= $x + $width ||
          $parent->mayBeExpanded()) {

        // Will fit; 
        // Check if current float will intersect the existing left-floating box
        //
        $x1 = $this->float_right_x($parent->get_right(), $y);
        if ($x1 + EPSILON > $x + $width) {
          return;
        };
        return;
      };

      //      print("CLEAR<br/>");

      // No, float does not fit at current level, let's try to 'clear' some previous floats
      $clear++;
      
      // Check if we've cleared all existing floats; the loop will be terminated in this case, of course,
      // but we can get a notice/warning message if we'll try to access the non-existing array element
      if ($clear <= count($floats)) { $y = min( $y, $float_bottoms[$clear-1] ); };

    } while ($clear <= count($floats)); // We need to check if all floats have been cleared to avoid infinite loop

    // All floats are cleared; fall back to the leftmost X coordinate
    $x = $parent->get_left();
  }

  // Get the right edge coordinate of the rightmost float in 
  // current formatting context
  //
  // @return null in case of no floats exists in current context
  // numeric coordinate value otherwise
  // 
  function float_right() {
    $floats =& $this->current_floats();

    if (count($floats) == 0) { return null; }

    $right = $floats[0]->get_right_margin();
    $size = count($floats);
    for ($i=1; $i<$size; $i++) {
      $right = max($right, $floats[$i]->get_right_margin());
    };

    return $right;
  }

  // Calculates the rightmost x-coordinate not covered by floats in current context 
  // at the given level (y-coordinate)
  //
  // @param $x starting X coordinate (no point to the right of this allowed)
  // @param $y Y coordinate we're searching at
  // @return the rightmost X coordinate value
  //
  function float_right_x($x, $y) {
    $floats =& $this->current_floats();

    $size = count($floats);
    for ($i=0; $i<$size; $i++) {
      $float =& $floats[$i];

      // Process only right-floating boxes
      if ($float->getCSSProperty(CSS_FLOAT) == FLOAT_RIGHT) {
        // Check if this float contains given Y-coordinate
        //
        // Note that top margin coordinate is inclusive but 
        // bottom margin coordinate is exclusive! The cause is following:
        // - if we have several floats in one line, their top margin edge Y coordinates will be equal,
        //   so we must use agreater or equal sign to avod placing all floats at one X coordinate
        // - on the other side, if we place one float under the other, the top margin Y coordinate 
        //   of bottom float will be equal to bottom margin Y coordinate of the top float and 
        //   we should NOT offset tho bottom float in this case
        //

        if ($float->get_top_margin() + EPSILON >= $y &&
            $float->get_bottom_margin() < $y) {
          $x = min($x, $float->get_left_margin());
        };
      };
    };
    
    return $x;
  }

  // Calculates position of right floating box (taking into account the possibility 
  // of "wrapping" float to next line in case we have not enough space at current level (Y coordinate)
  //
  // @param $parent reference to a parent box
  // @param $width width of float being placed. Full width! so, extra horizontal space (padding, margins and borders) is added here too
  // @param $x [out] X coordinate of float upper-right corner
  // @param $y [in,out] Y coordinate of float upper-right corner
  //
  function float_right_xy(&$parent, $width, &$x, &$y) {
    // Numbler of floats to clear; we need this because of the following example:
    // <div style="width: 150px; background-color: red; padding: 5px;">
    // <div style="float: left; background-color: green; height: 40px; width: 100px;">T</div>
    // <div style="float: left; background-color: yellow; height: 20px; width: 50px;">T</div>
    // <div style="float: left; background-color: cyan; height: 20px; width: 50px;">T</div>
    // in this case the third float will be rendered directly under the second, so only the 
    // second float should be cleared
    
    $clear = 0;

    $floats =& $this->current_floats();

    // Prepare information about the float bottom coordinates
    $float_bottoms = array();
    $size = count($floats);
    for ($i=0; $i<$size; $i++) {
      $float_bottoms[] = $floats[$i]->get_bottom_margin();
    };

    // Note that the sort function SHOULD NOT maintain key-value assotiations!
    rsort($float_bottoms); 

    do {
      $x  = $this->float_right_x($parent->get_right(), $y);
      
      // Check if current float will fit into the parent box
      // OR if the parent box have width: auto (it will expanded in this case anyway)
      //
      if ($parent->get_right() + EPSILON > $x ||
          $parent->width == WIDTH_AUTO) {

        // Will fit; 
        // Check if current float will intersect the existing left-floating box
        //
        $x1 = $this->float_left_x($parent->get_left(), $y);
        if ($x1 - EPSILON < $x - $width) {
          return;
        };
      };


      // No, float does not fit at current level, let's try to 'clear' some previous floats
      $clear++;
      
      // Check if we've cleared all existing floats; the loop will be terminated in this case, of course,
      // but we can get a notice/warning message if we'll try to access the non-existing array element
      if ($clear <= count($floats)) { $y = min( $y, $float_bottoms[$clear-1] ); };

    } while($clear <= count($floats)); // We need to check if all floats have been cleared to avoid infinite loop

    // All floats are cleared; fall back to the rightmost X coordinate
    $x = $parent->get_right();
  }

  function FlowContext() {
    $this->absolute_positioned = array();
    $this->fixed_positioned = array();

    $this->viewport = array();
    $this->_floats = array(array());
    $this->collapsed_margins = array(0);
    $this->container_uid = array(1);
  }

  function get_collapsed_margin() {
    return $this->collapsed_margins[0];
  }

  function &get_viewport() {
    return $this->viewport[0];
  }

  function pop() {
    $this->pop_collapsed_margin();
    $this->pop_floats();
  }

  function pop_collapsed_margin() {
    array_shift($this->collapsed_margins);
  }

  function pop_container_uid() {
    array_shift($this->container_uid);
  }

  function pop_floats() {
    array_shift($this->_floats);
  }

  function push() {
    $this->push_collapsed_margin(0);
    $this->push_floats();
  }

  function push_collapsed_margin($margin) {
    array_unshift($this->collapsed_margins, $margin);
  }

  function push_container_uid($uid) {
    array_unshift($this->container_uid, $uid);
  }

  function push_floats() {
    array_unshift($this->_floats, array());
  }

  function push_viewport(&$box) {
    array_unshift($this->viewport, $box);
  }

  function &point_in_floats($x, $y) {
    // Scan the floating children list of the current container box
    $floats =& $this->current_floats();
    $size = count($floats);
    for ($i=0; $i<$size; $i++) {
      if ($floats[$i]->contains_point_margin($x, $y)) {
        return $floats[$i]; 
      }
    }

    $dummy = null;
    return $dummy;
  }

  function pop_viewport() {
    array_shift($this->viewport);
  }

  function sort_absolute_positioned_by_z_index() {
    usort($this->absolute_positioned, "cmp_boxes_by_z_index");
  }
}

function cmp_boxes_by_z_index($a, $b) {
  $a_z = $a->getCSSProperty(CSS_Z_INDEX);
  $b_z = $b->getCSSProperty(CSS_Z_INDEX);

  if ($a_z == $b_z) return 0;
  return ($a_z < $b_z) ? -1 : 1;
}
?>