<?php
// $Header: /cvsroot/html2ps/box.whitespace.php,v 1.29 2006/05/27 15:33:26 Konstantin Exp $

class WhitespaceBox extends TextBox {
  function &create() {
    $box =& new WhitespaceBox();
    return $box;
  }

  function get_extra_bottom() {
    return 0;
  }

  // "Pure" Text boxes never have margins/border/padding
  function get_extra_left() {
    return 0;
  }

  // "Pure" Text boxes never have margins/border/padding
  function get_extra_right() {
    return 0;
  }

  function get_extra_top() {
    return 0;
  }

  function get_full_width() {
    return $this->width;
  }

  function get_margin_top() {
    return 0;
  }

  function get_min_width(&$context) {
    return $this->width;
  }

  function get_max_width(&$context) {
    return $this->width;
  }

  function WhitespaceBox() {
    // Call parent constructor
    $this->TextBox(" ",'iso-8859-1');
  }

  // (!) SIDE EFFECT: current whitespace box can be replaced by a null box during reflow.
  // callers of reflow should take this into account and possilby check for this 
  // after reflow returns. This can be detected by UID change.
  // 
  function reflow_static(&$parent, &$context) {  
    // Check if there are any boxes in parent's line box
    if ($parent->line_box_empty()) {
      // The very first whitespace in the line box should not affect neither height nor baseline of the line box;
      // because following boxes can be smaller that assumed whitespace height
      // Example: <br>[whitespace]<img height="2" width="2"><br>; whitespace can overextend this line

      $this->width = 0;
      $this->height = 0;
    } elseif (is_a($parent->last_in_line(),"WhitespaceBox")) {
      // Duplicate whitespace boxes should not offset further content and affect the line box length

      $this->width = 0;
      $this->height = 0;
    };

    GenericFormattedBox::reflow($parent, $context);

    // Apply 'line-height'
    $this->_apply_line_height();

    // default baseline 
    $this->baseline = $this->default_baseline;

    // append to parent line box
    $parent->append_line($this);

    // Move box to the parent current point
    $this->guess_corner($parent);

    // Offset parent's current point
    $parent->_current_x += $this->width;
    
    // Extend parent height
    $parent->extend_height($this->get_bottom_margin());

    // Update the value of current collapsed margin; pure text (non-span)
    // boxes always have zero margin

    $context->pop_collapsed_margin();
    $context->push_collapsed_margin( 0 );
  }

  function reflow_text(&$viewport) {
    if (is_null(TextBox::reflow_text($viewport))) {
      return null;
    };

    // Override widths
    $this->width = $this->font_size * WHITESPACE_FONT_SIZE_FRACTION;
    return true;
  }

  function reflow_whitespace(&$linebox_started, &$previous_whitespace) {
    if (!$linebox_started || 
        ($linebox_started && $previous_whitespace)) {     
      if ($this->pseudo_link_destination == "") {
        $this->parent->remove($this);
      } else {
        $this->font_height = 0.001;
        $this->height = 0;
        $this->width = 0;
      };
    };

    $previous_whitespace = true;

    // Note that there can (in theory) several whitespaces in a row, so
    // we could not modify a flag until we met a real text box
  }
}
?>