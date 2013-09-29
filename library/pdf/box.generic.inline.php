<?php
class GenericInlineBox extends GenericContainerBox {
  function GenericInlineBox() {
    $this->GenericContainerBox();
  }

  // @todo this code is duplicated in box.block.php
  //
  function reflow(&$parent, &$context) {
    switch ($this->getCSSProperty(CSS_POSITION)) {
    case POSITION_STATIC:
      return $this->reflow_static($parent, $context);

    case POSITION_RELATIVE:
      /**
       * CSS 2.1:
       * Once a box has been laid out according to the normal flow or floated, it may be shifted relative 
       * to this position. This is called relative positioning. Offsetting a box (B1) in this way has no
       * effect on the box (B2) that follows: B2 is given a position as if B1 were not offset and B2 is 
       * not re-positioned after B1's offset is applied. This implies that relative positioning may cause boxes
       * to overlap. However, if relative positioning causes an 'overflow:auto' box to have overflow, the UA must
       * allow the user to access this content, which, through the creation of scrollbars, may affect layout.
       * 
       * @link http://www.w3.org/TR/CSS21/visuren.html#x28 CSS 2.1 Relative positioning
       */

      $this->reflow_static($parent, $context);
      $this->offsetRelative();
      return;
    }
  }

  // Checks if current inline box should cause a line break inside the parent box
  //
  // @param $parent reference to a parent box
  // @param $content flow context
  // @return true if line break occurred; false otherwise
  //
  function maybe_line_break(&$parent, &$context) {
    if (!$parent->line_break_allowed()) { 
      return false; 
    };

    // Calculate the x-coordinate of this box right edge 
    $right_x = $this->get_full_width() + $parent->_current_x;

    $need_break = false;

    // Check for right-floating boxes
    // If upper-right corner of this inline box is inside of some float, wrap the line
    if ($context->point_in_floats($right_x, $parent->_current_y)) {
      $need_break = true;
    };

    // No floats; check if we had run out the right edge of container
    // TODO: nobr-before, nobr-after

    if (($right_x > $parent->get_right() + EPSILON)) {
      // Now check if parent line box contains any other boxes;
      // if not, we should draw this box unless we have a floating box to the left

      $first = $parent->get_first();

      // FIXME: what's this? This condition is invariant!
      $text_indent = $parent->getCSSProperty(CSS_TEXT_INDENT);
      $indent_offset = ($first->uid == $this->uid || 1) ? $text_indent->calculate($parent) : 0;

      if ($parent->_current_x > $parent->get_left() + $indent_offset + EPSILON) {
        $need_break = true;
      };
    }

    // As close-line will not change the current-Y parent coordinate if no 
    // items were in the line box, we need to offset this explicitly in this case
    //
    if ($parent->line_box_empty() && $need_break) {
      $parent->_current_y -= $this->get_height();
    };

    if ($need_break) { 
      $parent->close_line($context); 

      // Check if parent inline boxes have left padding/margins and add them to current_x
      $element = $this->parent;
      while (!is_null($element) && is_a($element,"GenericInlineBox")) {
        $parent->_current_x += $element->get_extra_left();
        $element = $element->parent;
      };
    };

    return $need_break;
  }

  function get_ascender() {
    $first =& $this->get_first();
    if (is_null($first)) { return 0; };
    return $first->get_ascender();
  }

  function get_baseline() {
    $first =& $this->get_first();
    if (is_null($first)) { return 0; };
    return $first->get_baseline();
  }

  function get_descender() {
    $first =& $this->get_first();
    if (is_null($first)) { return 0; };
    return $first->get_descender();
  }
}
?>