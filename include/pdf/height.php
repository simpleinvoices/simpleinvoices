<?php
// Height constraint "merging" function.
// 
// Constraints have the following precedece:
// 1. constant constraint
// 2. diapason constraint 
// 3. no constraint
//
// If both constraints are constant, the first one is choosen;
//
// If both constraints are diapason constraints the first one is choosen
//
function merge_height_constraint($hc1, $hc2) {
  // First constraint is constant; return this, as second constraint 
  // will never override it
  if ($hc1->constant !== null) { return $hc1; };

  // Second constraint is constant; first is not constant;
  // return second, as it is more important
  if ($hc2->constant !== null) { return $hc2; };

  // Ok, both constraints are not constant. Check if there's any diapason 
  // constraints

  // Second constraint is free constraint, return first one, as 
  // if it is a non-free it should have precedence, otherwise 
  // it will be free constraint too
  if ($hc2->min === null && $hc2->max === null) { return $hc1; };
  
  // The same rule applied if the first constraint is free constraint
  if ($hc1->min === null && $hc1->max === null) { return $hc2; };

  // If we got here it means both constraints are diapason constraints.
  return $hc1;
}

// Height constraint class
// 
// Height could be constrained as a percentage of the parent height OR 
// as a constant value. Note that in most cases percentage constraint 
// REQUIRE parent height to be constrained. 
//
// Note that constraint can be given as a diapason from min to max height
// It is applied only of no strict height constraint is given
//
class HCConstraint {
  var $constant;
  var $min;
  var $max;

  function applicable(&$box) {
    if ($this->constant !== null) { return $this->applicable_value($this->constant, $box); }

    $applicable_min = false;
    if ($this->min !== null) {
      $applicable_min = $this->applicable_value($this->min, $box);
    };

    $applicable_max = false;
    if ($this->max !== null) {
      $applicable_max = $this->applicable_value($this->max, $box);
    };

    return $applicable_min || $applicable_max;
  }

  /**
   * Since we decided to calculate percentage constraints of the top-level boxes using 
   * the page height as the basis, all height constraint values will be applicable.
   *
   * In older version, percentage height constraints on top-level boxes were silently ignored and
   * height was determined by box content
   */
  function applicable_value($value, &$box) {
    return true;

    // Constant constraints always applicable
//     if (!$value[1]) { return true; };

//     if (!$box->parent) { return false; };
//     return $box->parent->_height_constraint->applicable($box->parent);
  }
   
  function _fix_value($value, &$box, $default, $no_table_recursion) {
    // A percentage or immediate value?
    if ($value[1]) {
      // CSS 2.1: The percentage  is calculated with respect to the height of the generated box's containing block.
      // If the height of the containing  block is not specified explicitly (i.e., it  depends on  content height),
      // and this  element is  not absolutely positioned, the value is interpreted like 'auto'.

      /**
       * Check if parent exists. If there's no parent, calculate percentage relatively to the page
       * height (excluding top/bottom margins, of course)
       */
      if (!isset($box->parent) || !$box->parent) {
        global $g_media;
        return mm2pt($g_media->real_height()) * $value[0] / 100;
      }

      if (!isset($box->parent->parent) || !$box->parent->parent) {
        global $g_media;
        return mm2pt($g_media->real_height()) * $value[0] / 100;
      }

//       if (!isset($box->parent)) { return null; }
//       if (!$box->parent) { return null; }

      // if parent does not have constrained height, return null - no height constraint can be applied
      // Table cells should be processed separately
      if (!is_a($box->parent,"TableCellBox") &&
          $box->parent->_height_constraint->constant === null &&
          $box->parent->_height_constraint->min === null &&
          $box->parent->_height_constraint->max === null) {
        return $default;
      };

      if (is_a($box->parent,"TableCellBox")) {
        if (!$no_table_recursion) {
          $rhc = $box->parent->parent->get_rhc($box->parent->row);
          if ($rhc->is_null()) { return $default; };
          return $rhc->apply($box->parent->get_height(), $box, true) * $value[0] / 100;
        } else {
          return $box->parent->parent->get_height() * $value[0] / 100;
        };
      };

      return $box->parent->get_height() * $value[0] / 100;
    } else {
      // Immediate
      return $value[0];
    }
  }

  function create($box) {   
    // Determine if there's constant restriction
    $handler =& get_css_handler('height');
    if (!$handler->is_default($handler->get())) {
      $constant = $handler->get();
    } else {
      $constant = null;
    };

    // Determine if there's min restriction
    $handler =& get_css_handler('min-height');
    if (!$handler->is_default($handler->get())) {
      $min = $handler->get();
    } else {
      $min = null;
    };

    // Determine if there's max restriction
    $handler =& get_css_handler('max-height');
    if (!$handler->is_default($handler->get())) {
      $max = $handler->get();
    } else {
      $max = null;
    };

    $constraint = new HCConstraint($constant, $min, $max);
    return $constraint;
  }

  // Height constraint constructor
  //
  // @param $constant value of constant constraint or null of none
  // @param $min value of minimal box height or null if none
  // @param $max value of maximal box height or null if none
  //
  function HCConstraint($constant, $min, $max) {
    $this->constant = $constant;
    $this->min = $min;
    $this->max = $max;
  }

  function apply_min($value, &$box, $no_table_recursion) {
    if ($this->min === null) {
      return $value;
    } else {
      return max($this->_fix_value($this->min, $box, $value, $no_table_recursion), $value);
    }
  }

  function apply_max($value, &$box, $no_table_recursion) {
    if ($this->max === null) {
      return $value;
    } else {
      return min($this->_fix_value($this->max, $box, $value, $no_table_recursion), $value);
    }
  }

  function apply($value, &$box, $no_table_recursion = false) {
    if ($this->constant !== null) {
      $height = $this->_fix_value($this->constant, $box, $value, $no_table_recursion);
    } else {
      $height =  $this->apply_min($this->apply_max($value, $box, $no_table_recursion), $box, $no_table_recursion);
    }

    // Table cells contained in tables with border-collapse: separate
    // have padding included in the 'height' value. So, we'll need to subtract
    // vertical-extra from the current value to get the actual content height
    // TODO
    
    return $height;
  }

  function is_null() {
    return 
      $this->max === null && 
      $this->min == null && 
      $this->constant == null;
  }

  function units2pt($base) {
    $this->units2pt_value($this->max, $base);
    $this->units2pt_value($this->min, $base);
    $this->units2pt_value($this->constant, $base);
  }

  function units2pt_value(&$value, $base) {
    if (is_null($value)) { return; };

    if (!$value[1]) {
      $value[0] = units2pt($value[0], $base);
    };
  }
}
?>