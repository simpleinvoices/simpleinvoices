<?php
// $Header: /cvsroot/html2ps/box.whitespace.php,v 1.33 2007/01/24 18:55:46 Konstantin Exp $

class WhitespaceBox extends TextBox {
  function &create(&$pipeline) {
    $box =& new WhitespaceBox();
    $box->readCSS($pipeline->getCurrentCSSState());
    $box->add_subword(" ", 'iso-8859-1', array());
    return $box;
  }

  function readCSS(&$state) {
    parent::readCSS($state);

    $this->_readCSSLengths($state,
                           array(CSS_WORD_SPACING));
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
    $this->TextBox();
  }

  // (!) SIDE EFFECT: current whitespace box can be replaced by a null box during reflow.
  // callers of reflow should take this into account and possilby check for this 
  // after reflow returns. This can be detected by UID change.
  // 
  function reflow(&$parent, &$context) {  
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
    } elseif ($this->maybe_line_break($parent, $context)) {
      $this->width = 0;
      $this->height = 0;
    };

    parent::reflow($parent, $context);
  }

  function reflow_text(&$driver) {
    if (is_null(parent::reflow_text($driver))) {
      return null;
    };

    // Override widths
    $letter_spacing = $this->getCSSProperty(CSS_LETTER_SPACING);
    $word_spacing   = $this->getCSSProperty(CSS_WORD_SPACING);

    $this->width = 
      $this->height * WHITESPACE_FONT_SIZE_FRACTION + 
      $letter_spacing->getPoints() + 
      $word_spacing->getPoints();

    return true;
  }

  function reflow_whitespace(&$linebox_started, &$previous_whitespace) {
    if (!$linebox_started || 
        ($linebox_started && $previous_whitespace)) {     
      
      $link_destination = $this->getCSSProperty(CSS_HTML2PS_LINK_DESTINATION);
      if (is_null($link_destination)) {
        $this->parent->remove($this);
        return;
      };

      $this->font_height = 0.001;
      $this->height = 0;
      $this->width = 0;
    };

    $previous_whitespace = true;

    // Note that there can (in theory) several whitespaces in a row, so
    // we could not modify a flag until we met a real text box
  }
}
?>