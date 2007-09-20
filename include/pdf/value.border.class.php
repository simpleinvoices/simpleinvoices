<?php

require_once(HTML2PS_DIR.'value.generic.php');

class BorderPDF extends CSSValue {
  var $left;
  var $right;
  var $top;
  var $bottom;

  function BorderPDF() {
    $this->left   =& new EdgePDF();
    $this->right  =& new EdgePDF();
    $this->top    =& new EdgePDF();
    $this->bottom =& new EdgePDF();
  }

  function create($data) {
    $border         =& new BorderPDF();
    $border->left   =& EdgePDF::create($data['left']);
    $border->right  =& EdgePDF::create($data['right']);
    $border->top    =& EdgePDF::create($data['top']);
    $border->bottom =& EdgePDF::create($data['bottom']);
    return $border;
  }

  /**
   * Optimization: note usage of '!=='.  It is faster than '!=' in our
   * case (PHP 5.1.1, Win)
   */
  function &copy() {
    $border =& new BorderPDF();

    if ($this->left !== CSS_PROPERTY_INHERIT) {
      $border->left = $this->left->copy();
    } else {
      $border->left = CSS_PROPERTY_INHERIT;
    };

    if ($this->right !== CSS_PROPERTY_INHERIT) {
      $border->right = $this->right->copy();
    } else {
      $border->right = CSS_PROPERTY_INHERIT;
    };

    if ($this->top !== CSS_PROPERTY_INHERIT) {
      $border->top = $this->top->copy();
    } else {
      $border->top = CSS_PROPERTY_INHERIT;
    };

    if ($this->bottom !== CSS_PROPERTY_INHERIT) {
      $border->bottom = $this->bottom->copy();
    } else {
      $border->bottom = CSS_PROPERTY_INHERIT;
    };

    return $border;
  }

  function doInherit(&$state) {
    if ($this->top === CSS_PROPERTY_INHERIT) {
      $value = $state->getInheritedProperty(CSS_BORDER_TOP);
      $this->top = $value->copy();
    };

    if ($this->right === CSS_PROPERTY_INHERIT) {
      $value = $state->getInheritedProperty(CSS_BORDER_RIGHT);
      $this->right = $value->copy();
    };

    if ($this->bottom === CSS_PROPERTY_INHERIT) {
      $value = $state->getInheritedProperty(CSS_BORDER_BOTTOM);
      $this->bottom = $value->copy();
    };
    
    if ($this->left === CSS_PROPERTY_INHERIT) {
      $value = $state->getInheritedProperty(CSS_BORDER_LEFT);
      $this->left = $value->copy();
    };

    $this->top->doInherit($state, 
                          CSS_BORDER_TOP_WIDTH, 
                          CSS_BORDER_TOP_COLOR,
                          CSS_BORDER_TOP_STYLE);
    $this->right->doInherit($state, 
                          CSS_BORDER_RIGHT_WIDTH, 
                          CSS_BORDER_RIGHT_COLOR,
                          CSS_BORDER_RIGHT_STYLE);
    $this->bottom->doInherit($state, 
                          CSS_BORDER_BOTTOM_WIDTH, 
                          CSS_BORDER_BOTTOM_COLOR,
                          CSS_BORDER_BOTTOM_STYLE);
    $this->left->doInherit($state, 
                          CSS_BORDER_LEFT_WIDTH, 
                          CSS_BORDER_LEFT_COLOR,
                          CSS_BORDER_LEFT_STYLE);
  }

  function &get_bottom() {
    return $this->bottom;
  }

  function &get_left() {
    return $this->left;
  }

  function &get_right() {
    return $this->right;
  }

  function &get_top() {
    return $this->top;
  }

  function is_default() {
    return 
      $this->left->style   == BS_NONE &&
      $this->right->style  == BS_NONE &&
      $this->top->style    == BS_NONE &&
      $this->bottom->style == BS_NONE;
  }

  function show(&$viewport, $box) {
    // Show left border
    if ($this->left->is_visible()) {
      $this->left->show($viewport, $box,
                        $box->get_left_border()  , $box->get_bottom_border(),
                        $box->get_left_border()  , $box->get_top_border(),
                        $box->get_left_border()+$this->left->get_width(), $box->get_top_border()-$this->top->get_width(),
                        $box->get_left_border()+$this->left->get_width(), $box->get_bottom_border()+$this->bottom->get_width(),
                        true);
    }

    // Show right border
    if ($this->right->is_visible()) {
      $this->right->show($viewport, $box,
                         $box->get_right_border()  , $box->get_bottom_border(),
                         $box->get_right_border()  , $box->get_top_border(),
                         $box->get_right_border()-$this->right->get_width(), $box->get_top_border()-$this->top->get_width(),
                         $box->get_right_border()-$this->right->get_width(), $box->get_bottom_border()+$this->bottom->get_width(),
                         false);
    }

    // Show top border
    if ($this->top->is_visible()) {
      $this->top->show($viewport, $box,
                       $box->get_left_border()  , $box->get_top_border(),
                       $box->get_right_border() , $box->get_top_border(),
                       $box->get_right_border()-$this->right->get_width() , $box->get_top_border() - $this->top->get_width(),
                       $box->get_left_border() +$this->left->get_width()  , $box->get_top_border() - $this->top->get_width(),
                       true);
    }

    // Show bottom border
    if ($this->bottom->is_visible()) {
      $this->bottom->show($viewport, $box,
                          $box->get_left_border()  , $box->get_bottom_border(),
                          $box->get_right_border() , $box->get_bottom_border(),
                          $box->get_right_border()- $this->right->get_width() , $box->get_bottom_border() + $this->bottom->get_width(),
                          $box->get_left_border() + $this->left->get_width()  , $box->get_bottom_border() + $this->bottom->get_width(),
                          false);
    }
  }

  function units2pt($base_font_size) {
    $this->left->units2pt($base_font_size);
    $this->right->units2pt($base_font_size);
    $this->top->units2pt($base_font_size);
    $this->bottom->units2pt($base_font_size);
  }
}

?>