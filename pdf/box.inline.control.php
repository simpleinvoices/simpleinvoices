<?php
// $Header: /cvsroot/html2ps/box.inline.control.php,v 1.6 2006/05/27 15:33:26 Konstantin Exp $

class InlineControlBox extends InlineBox {
  // get_max_width is inherited from GenericContainerBox
  function get_min_width(&$context) { 
    return $this->get_max_width($context);
  }

  function get_max_width(&$context) { 
    return GenericContainerBox::get_max_width($context); 
  }

  function show(&$viewport) {   
    // Now set the baseline of a button box to align it vertically when flowing isude the 
    // text line
    $this->default_baseline = $this->content[0]->baseline + $this->get_extra_top();
    $this->baseline         = $this->content[0]->baseline + $this->get_extra_top();

    return GenericContainerBox::show($viewport);
  }

  function line_break_allowed() { return false; }
  
  function reflow_static(&$parent, &$context) {  
    GenericFormattedBox::reflow($parent, $context);
    
    // Determine the box width
    $this->_calc_percentage_width($parent, $context);
    $this->put_full_width($this->get_min_width($context));

    // Check if we need a line break here
    $this->maybe_line_break($parent, $context);

    // append to parent line box
    $parent->append_line($this);

    // Determine coordinates of upper-left _margin_ corner
    $this->guess_corner($parent);

    $this->reflow_content($context);

    /**
     * After text content have been reflown, we may determine the baseline of the control item itself;
     *
     * As there will be some extra whitespace on the top of the control box, we must add this whitespace
     * to the calculated baseline value, so text before and after control item will be aligned 
     * with the text inside the box.
     */
    $this->default_baseline = $this->content[0]->baseline + $this->get_extra_top();
    $this->baseline         = $this->content[0]->baseline + $this->get_extra_top();

    // center the button text vertically inside the button
    $text =& $this->content[0];
    $delta = ($text->get_top() - $text->get_height()/2) - ($this->get_top() - $this->get_height()/2);
    $text->offset(0,-$delta);

    // Offset parent current X coordinate
    $parent->_current_x += $this->get_full_width();

    // Extends parents height
    $parent->extend_height($this->get_bottom_margin());
  }
}
?>