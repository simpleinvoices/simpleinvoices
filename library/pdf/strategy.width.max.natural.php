<?php

class StrategyWidthMaxNatural {
  var $_limit;
  var $_maxw;
  var $_cmaxw;

  function StrategyWidthMaxNatural($limit = 10E6) {
    $this->_limit = $limit;
  }

  function add_width($delta) {
    if ($this->_cmaxw + $delta > $this->_limit) {
      $this->line_break();
    };
    $this->_cmaxw += $delta;
  }

  function line_break() {
    $this->_maxw  = max($this->_maxw, $this->_cmaxw);
    $this->_cmaxw = 0;
  }

  function apply(&$box, &$context) {
    $this->_maxw = 0;

    // We need to add text indent to the max width
    $text_indent = $box->getCSSProperty(CSS_TEXT_INDENT);
    $this->_cmaxw = $text_indent->calculate($box);
    
    for ($i=0, $size = count($box->content); $i<$size; $i++) {
      $child =& $box->content[$i];

      // Note that while BR-generated box is out of flow,
      // it should break the current line
      if ($child->isLineBreak()) {
        $this->line_break();

      } elseif (!$child->out_of_flow()) {
        if (is_inline($child)) {
          $this->add_width($child->get_max_width_natural($context, $this->_limit));

        } elseif ($child->getCSSProperty(CSS_FLOAT) !== FLOAT_NONE) {
          $wc = $child->getCSSProperty(CSS_WIDTH);

          if (!$wc->isFraction()) {
            $delta = $child->get_max_width($context, $this->_limit);
          } else {
            $delta = $child->get_max_width_natural($context, $this->_limit);
          };

          $this->add_width($delta);
        } else {
          $this->_maxw  = max($this->_maxw, $this->_cmaxw);
          $this->_cmaxw = $child->get_max_width_natural($context, $this->_limit);
          
          // Process special case with percentage constrained table
          $item = $child;
          $item_wc = $item->getCSSProperty(CSS_WIDTH);
          
          if (is_a($item, "TableBox") &&
              $item_wc->isFraction()) {
            if (isset($child->parent) && $child->parent) {
              $this->_cmaxw = max($this->_cmaxw, 
                                  $item_wc->apply($child->get_width(), 
                                                  $child->parent->get_expandable_width()));
            } else {
              $this->_cmaxw = max($this->_cmaxw, 
                                  $item_wc->apply($child->get_width(), 
                                                  $child->get_width()));
            };
          };

          $this->line_break();
        };
      };
    }

    // Check if last line have maximal width
    //
    $this->_maxw = max($this->_maxw, $this->_cmaxw);

    return $this->_maxw + $box->_get_hor_extra();
  }
}

?>