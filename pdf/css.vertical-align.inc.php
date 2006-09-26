<?php
// $Header: /cvsroot/html2ps/css.vertical-align.inc.php,v 1.20 2006/04/16 16:54:57 Konstantin Exp $

define('VA_SUPER'      ,0);
define('VA_SUB'        ,1);
define('VA_TOP'        ,2);
define('VA_MIDDLE'     ,3);
define('VA_BOTTOM'     ,4);
define('VA_BASELINE'   ,5);
define('VA_TEXT_TOP'   ,6);
define('VA_TEXT_BOTTOM',7);

class VerticalAlignSuper { 
//   function apply(&$child, &$parent) { 
//     $child->baseline /= 2; 
//   } 

  function apply_cell(&$cell, $row_height, $row_baseline) {
    return; // Do nothing
  }
}

class VerticalAlignSub   { 
//   function apply(&$child, &$parent) { 
//     $child->baseline = $child->baseline/2 - $parent->_line_baseline; 
//   } 

  function apply_cell(&$cell, $row_height, $row_baseline) {
    return; // Do nothing
  }
}

class VerticalAlignTop { 
//   function apply(&$child, &$parent) { 
//     return; // Do nothing 
//   } 

  function apply_cell(&$cell, $row_height, $row_baseline) {
    return; // Do nothing
  }
}

class VerticalAlignMiddle {
//   function apply(&$child, &$parent) {
//     if ($parent->_line_baseline > $child->baseline) {
//       $child->baseline = $parent->_line_baseline;
//     } else {
//       $delta = $parent->_line_baseline > $child->baseline;

//       for ($i=0; $i<count($parent->_line); $i++) {
//         $parent->_line[$i]->baseline -= $delta;
//       }

//       $parent->_line_baseline += $delta;
//     }

//     $child->baseline += ($child->default_baseline - $child->baseline)*0.5;
//   }

  function apply_cell(&$cell, $row_height, $row_baseline) {    
    $delta = max(0, ($row_height - $cell->get_real_full_height()) / 2);

    $old_top = $cell->get_top();
    $cell->offset(0, -$delta);
    $cell->put_top($old_top);
  }
}

class VerticalAlignBottom {
  function apply_cell(&$cell, $row_height, $row_baseline) {
    $delta = ($row_height - $cell->get_real_full_height());

    $old_top = $cell->get_top();
    $cell->offset(0, -$delta);
    $cell->put_top($old_top);
  }
}

class VerticalAlignBaseline {
  function apply_cell(&$cell, $row_height, $row_baseline) {
    $delta = ($row_baseline - $cell->get_cell_baseline());

    $old_top = $cell->get_top();
    $cell->offset(0, -$delta);
    $cell->put_top($old_top);
  }
}

class VerticalAlignTextTop {
//   function apply(&$child, &$parent) {
//     // FIXME
//   }

  function apply_cell(&$cell, $row_height, $row_baseline) {
    return; // Do nothing
  }
}

class VerticalAlignTextBottom {
//   function apply(&$child, &$parent) {
//     if (-$parent->_line_baseline > $child->baseline) {
//       $child->baseline = -($parent->_line_baseline);
//     } else {
//       $baseline = $child->baseline;
//       $delta = -$parent->_line_baseline - $child->baseline;

//       for ($i=0; $i<count($parent->_line); $i++) {
//         $parent->_line[$i]->baseline -= $delta;
//       }

//       // Child could be already in parent line box; in this case
//       // its baseline value have been modified; return it to the correct value
//       $child->baseline = $baseline;

//       $parent->_line_baseline += $delta;
//     }
//   }

  function apply_cell(&$cell, $row_height, $row_baseline) {
    $delta = ($row_baseline - $cell->get_cell_baseline());

    $old_top = $cell->get_top();
    $cell->offset(0, -$delta);
    $cell->put_top($old_top);
  }
}

class CSSVerticalAlign extends CSSProperty {
  function CSSVerticalAlign() { 
    // Note that in general, parameters 'true' and 'false' are non meaningful in out case,
    // as we anyway override 'inherit' and 'inherit_text' in this class.
    $this->CSSProperty(true, false); 
  }

  function inherit() { 
    // Determine parent 'display' value
    $handler =& get_css_handler('display');
    $parent_display = $handler->get_parent();

    // Inherit vertical-align from table-rows 
    if ($parent_display === "table-row") {
      $this->push($this->get());
      return;
    }

    $this->push(is_inline_element($parent_display) ? $this->get() : $this->default_value());
  }
  
  function inherit_text() { 
    // Determine parent 'display' value
    $handler =& get_css_handler('display');
    $parent_display = $handler->get_parent();

    $this->push(is_inline_element($parent_display) ? $this->get() : $this->default_value());
  }

  function default_value() { return VA_BASELINE; }

  function css($value) { 
    if ($this->applicable()) {
      $this->replace($this->parse($value)); 
    }
  }

  function parse($value) {
    // Convert value to lower case, as html allows values 
    // in both cases to be entered
    $value = strtolower($value);

    if ($value === 'baseline')    { return VA_BASELINE; };
    if ($value === 'sub')         { return VA_SUB; };
    if ($value === 'super')       { return VA_SUPER; };
    if ($value === 'top')         { return VA_TOP; };
    if ($value === 'middle')      { return VA_MIDDLE; };

    // As some brainless designers sometimes use 'center' instead of 'middle',
    // we'll add support for it
    if ($value === 'center')      { return VA_MIDDLE; }

    if ($value === 'bottom')      { return VA_BOTTOM; };
    if ($value === 'text-top')    { return VA_TEXT_TOP; };
    if ($value === 'text-bottom') { return VA_TEXT_BOTTOM; };
    return $this->default_value();
  }

  function value2pdf($value) {
    if ($value === VA_SUPER)       { return new VerticalAlignSuper; }
    if ($value === VA_SUB)         { return new VerticalAlignSub; }
    if ($value === VA_TOP)         { return new VerticalAlignTop; }
    if ($value === VA_MIDDLE)      { return new VerticalAlignMiddle; }
    if ($value === VA_BOTTOM)      { return new VerticalAlignBottom; }
    if ($value === VA_BASELINE)    { return new VerticalAlignBaseline; }
    if ($value === VA_TEXT_TOP)    { return new VerticalAlignTextTop; }
    if ($value === VA_TEXT_BOTTOM) { return new VerticalAlignTextBottom; }
    return new VerticalAlignBaseline;
  }

  function applicable() {
    $handler =& get_css_handler('display');
    $display = $handler->get();
    return
      $display === 'table-cell' ||
      $display === 'table-row' ||
      is_inline_element($display);
  }
}

register_css_property('vertical-align', new CSSVerticalAlign);

?>