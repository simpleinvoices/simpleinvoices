<?php

class StrategyWidthMinNowrap {
  var $_maxw;
  var $_cmaxw;

  function StrategyWidthMinNowrap() {
  }

  function add_width($delta) {
    $this->_cmaxw += $delta;
  }

  function line_break() {
    $this->_maxw  = max($this->_maxw, $this->_cmaxw);
    $this->_cmaxw = 0;
  }

  function apply(&$box, &$context) {
    $this->_maxw = 0;
    
    // We need to add text indent to the width
    $ti = $box->getCSSProperty(CSS_TEXT_INDENT);
    $this->add_width($ti->calculate($box));

    for ($i=0, $size = count($box->content); $i<$size; $i++) {
      $child =& $box->content[$i];
      if ($child->isLineBreak()) {
        $this->line_break();
      } elseif (!$child->out_of_flow()) {
        if (is_inline($child)) {
          // Inline boxes content will not be wrapped, so we may calculate its max width
          $this->add_width($child->get_max_width($context));
        } else {
          // Non-inline boxes cause line break
          $this->line_break();
          $this->add_width($child->get_min_width($context));
          $this->line_break();
        }
      };
    }

    // Check if last line have maximal width
    $this->line_break();

    // Apply width constraint to min width. Return maximal value
    $wc = $box->getCSSProperty(CSS_WIDTH);
    return max($this->_maxw, $wc->apply($this->_maxw, $box->parent->get_width())) + $box->_get_hor_extra();
  }
}

?>