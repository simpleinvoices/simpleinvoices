<?php

class LayoutVertical {
  // Calculate the vertical offset of current box due the 'clear' CSS property
  // 
  // @param $y initial Y coordinate to begin offset from
  // @param $context flow context containing the list of floats to interact with
  // @return updated value of Y coordinate
  //
  function apply_clear($box, $y, &$context) {
    $clear = $box->getCSSProperty(CSS_CLEAR);

    // Check if we need to offset box vertically due the 'clear' property
    if ($clear == CLEAR_BOTH || $clear == CLEAR_LEFT) {
      $floats =& $context->current_floats();
      for ($cf = 0; $cf < count($floats); $cf++) {
        $current_float =& $floats[$cf];
        if ($current_float->getCSSProperty(CSS_FLOAT) == FLOAT_LEFT) {
          // Float vertical margins are never collapsed
          //
          $margin = $box->getCSSProperty(CSS_MARGIN);
          $y = min($y, $current_float->get_bottom_margin() - $margin->top->value);
        };
      }
    };
    
    if ($clear == CLEAR_BOTH || $clear == CLEAR_RIGHT) {
      $floats =& $context->current_floats();
      for ($cf = 0; $cf < count($floats); $cf++) {
        $current_float =& $floats[$cf];
        if ($current_float->getCSSProperty(CSS_FLOAT) == FLOAT_RIGHT) {
          // Float vertical margins are never collapsed
          $margin = $box->getCSSProperty(CSS_MARGIN);
          $y = min($y, $current_float->get_bottom_margin() - $margin->top->value);
        };
      }
    };
    
    return $y;
  }
}

?>