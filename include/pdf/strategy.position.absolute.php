<?php

class StrategyPositionAbsolute {
  function StrategyPositionAbsolute() {
  }

  function apply(&$box) {
    /**
     * Box having 'position: absolute' are positioned relatively to their "containing blocks".
     *
     * @link http://www.w3.org/TR/CSS21/visudet.html#x0 CSS 2.1 Definition of "containing block"
     */
    $containing_block =& $box->_get_containing_block();

    $this->_positionAbsoluteVertically($box, $containing_block);
    $this->_positionAbsoluteHorizontally($box, $containing_block);    
  }

  /**
   * Note that if both top and bottom are 'auto', box will use vertical coordinate 
   * calculated using guess_corder in 'reflow' method which could be used if this
   * box had 'position: static'
   */
  function _positionAbsoluteVertically(&$box, &$containing_block) {
    $bottom = $box->getCSSProperty(CSS_BOTTOM);
    $top    = $box->getCSSProperty(CSS_TOP);

    if (!$top->isAuto()) {
      if ($top->isPercentage()) {
        $top_value = ($containing_block['top'] - $containing_block['bottom']) / 100 * $top->getPercentage();
      } else {
        $top_value = $top->getPoints();
      };
      $box->put_top($containing_block['top'] - $top_value - $box->get_extra_top());
    } elseif (!$bottom->isAuto()) { 
      if ($bottom->isPercentage()) {
        $bottom_value = ($containing_block['top'] - $containing_block['bottom']) / 100 * $bottom->getPercentage();
      } else {
        $bottom_value = $bottom->getPoints();
      };
      $box->put_top($containing_block['bottom'] + $bottom_value + $box->get_extra_bottom() + $box->get_height());
    };

//     $bottom = $box->getCSSProperty(CSS_BOTTOM);
//     $top    = $box->getCSSProperty(CSS_TOP);
//     if ($top->isAuto() && !$bottom->isAuto()) {
//       $box->offset(0, $box->get_height());
//     };
  }

  /**
   * Note that  if both  'left' and 'right'  are 'auto', box  will use
   * horizontal coordinate  calculated using guess_corder  in 'reflow'
   * method which could be used if this box had 'position: static'
   */
  function _positionAbsoluteHorizontally(&$box, &$containing_block) {
    $left  = $box->getCSSProperty(CSS_LEFT);
    $right = $box->getCSSProperty(CSS_RIGHT);

    if (!$left->isAuto()) { 
      if ($left->isPercentage()) {
        $left_value = ($containing_block['right'] - $containing_block['left']) / 100 * $left->getPercentage();
      } else {
        $left_value = $left->getPoints();
      };
      $box->put_left($containing_block['left'] + $left_value + $box->get_extra_left());
    } elseif (!$right->isAuto()) {
      if ($right->isPercentage()) {
        $right_value = ($containing_block['right'] - $containing_block['left']) / 100 * $right->getPercentage();
      } else {
        $right_value = $right->getPoints();
      };
      $box->put_left($containing_block['right'] - $right_value - $box->get_extra_right() - $box->get_width());
    };

//     $right = $box->getCSSProperty(CSS_RIGHT);
//     $left  = $box->getCSSProperty(CSS_LEFT);
//     if ($left->isAuto() && !$right->isAuto()) {
//       $box->offset(-$box->get_width(), 0);
//     };
  }
}

?>
