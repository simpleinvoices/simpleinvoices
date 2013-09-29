<?php
// $Header: /cvsroot/html2ps/box.text.string.php,v 1.5 2006/10/06 20:10:52 Konstantin Exp $

// TODO: from my POV, it wll be better to pass the font- or CSS-controlling object to the constructor
// instead of using globally visible functions in 'show'.

class TextBoxString extends TextBox {
  function &create($text, $encoding) {
    $box =& new TextBoxString($text, $encoding);
    $box->readCSS($pipeline->getCurrentCSSState());
    return $box;
  }

  function TextBoxString($word, $encoding) {
    // Call parent constructor
    $this->TextBox();
    $this->add_subword($word, $encoding, array());
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

  // Note that we don't need to call complicated 'get_width' function inherited from GenericFormattedBox, 
  // a TextBox never have width constraints nor children; its width is always defined by the string length
  function get_width() {
    return $this->width;
  }
}
?>