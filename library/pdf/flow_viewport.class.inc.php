<?php
class FlowViewport {
  var $left;
  var $top;
  var $width;
  var $height;

  function FlowViewport() {
    $this->left = 0;
    $this->top = 0;
    $this->width = 0;
    $this->height = 0;
  }

  function &create(&$box) {
    $viewport = new FlowViewport;
    $viewport->left   = $box->get_left_padding();
    $viewport->top    = $box->get_top_padding();
    
    $padding = $box->getCSSProperty(CSS_PADDING);
    
    $viewport->width  = $box->get_width() + $padding->left->value + $padding->right->value;
    $viewport->height = $box->get_height() + $padding->top->value + $padding->bottom->value;

    return $viewport;
  }

  function get_left() { return $this->left; }
  function get_top() { return $this->top; }
  function get_height() { return $this->height; }
  function get_width() { return $this->width; }
}
?>