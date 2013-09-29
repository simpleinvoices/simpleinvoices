<?php
// $Header: /cvsroot/html2ps/css.vertical-align.inc.php,v 1.23 2006/09/07 18:38:14 Konstantin Exp $

define('VA_SUPER'      ,0);
define('VA_SUB'        ,1);
define('VA_TOP'        ,2);
define('VA_MIDDLE'     ,3);
define('VA_BOTTOM'     ,4);
define('VA_BASELINE'   ,5);
define('VA_TEXT_TOP'   ,6);
define('VA_TEXT_BOTTOM',7);

class VerticalAlignSuper { 
  function apply_cell(&$cell, $row_height, $row_baseline) {
    return; // Do nothing
  }
}

class VerticalAlignSub   { 
  function apply_cell(&$cell, $row_height, $row_baseline) {
    return; // Do nothing
  }
}

class VerticalAlignTop { 
  function apply_cell(&$cell, $row_height, $row_baseline) {
    return; // Do nothing
  }
}

class VerticalAlignMiddle {
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
  function apply_cell(&$cell, $row_height, $row_baseline) {
    return; // Do nothing
  }
}

class VerticalAlignTextBottom {
  function apply_cell(&$cell, $row_height, $row_baseline) {
    $delta = ($row_baseline - $cell->get_cell_baseline());

    $old_top = $cell->get_top();
    $cell->offset(0, -$delta);
    $cell->put_top($old_top);
  }
}

class CSSVerticalAlign extends CSSPropertyHandler {
  function CSSVerticalAlign() { 
    // Note that in general, parameters 'true' and 'false' are non meaningful in out case,
    // as we anyway override 'inherit' and 'inherit_text' in this class.
    $this->CSSPropertyHandler(true, true); 
  }

  function inherit($old_state, &$new_state) { 
    // Determine parent 'display' value
    $parent_display = $old_state[CSS_DISPLAY];

    // Inherit vertical-align from table-rows 
    if ($parent_display === "table-row") {
      $this->replace_array($this->get($old_state),
                           $new_state);
      return;
    }

    if (is_inline_element($parent_display)) {
      $this->replace_array($this->get($old_state), $new_state);
      return;
    };
        
    $this->replace_array($this->default_value(), $new_state);
    return;
  }
  
  function inherit_text($old_state, &$new_state) { 
    // Determine parent 'display' value
    $parent_display = $old_state[CSS_DISPLAY];

    $this->replace_array(is_inline_element($parent_display) ? $this->get($old_state) : $this->default_value(),
                         $new_state);
  }

  function default_value() { return VA_BASELINE; }

  function parse($value) {
    if ($value === 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

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

  function applicable($css_state) {
    $handler =& CSS::get_handler(CSS_DISPLAY);
    $display = $handler->get($css_state->getState());
    return
      $display === 'table-cell' ||
      $display === 'table-row' ||
      is_inline_element($display);
  }

  function getPropertyCode() {
    return CSS_VERTICAL_ALIGN;
  }

  function getPropertyName() {
    return 'vertical-align';
  }
}

CSS::register_css_property(new CSSVerticalAlign);

?>