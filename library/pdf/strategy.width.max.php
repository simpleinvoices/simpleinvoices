<?php

class StrategyWidthMax {
  var $_limit;
  var $_maxw;
  var $_cmaxw;

  function StrategyWidthMax($limit = 10E6) {
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

      if ($child->isLineBreak()) {
        $this->line_break();
        
      } elseif (!$child->out_of_flow()) {
        if (is_inline($child) || 
            $child->getCSSProperty(CSS_FLOAT) !== FLOAT_NONE) {
          $this->add_width($child->get_max_width($context, $this->_limit));
        } else {
          $this->line_break();
          $this->add_width($child->get_max_width($context, $this->_limit));
          
          // Process special case with percentage constrained table
          $item_wc = $child->getCSSProperty(CSS_WIDTH);
          
          if (is_a($child,    "TableBox") &&
              is_a($item_wc, "WCFraction")) {
            $this->_cmaxw = max($this->_cmaxw, 
                                $item_wc->apply($box->get_width(), 
                                                $box->parent->get_expandable_width()));
          };
          $this->line_break();
        };
      };
    }

    // Check if last line have maximal width
    //
    $this->line_break();

    // Note that max width cannot differ from constrained width,
    // if any width constraints apply
    //
    $wc = $box->getCSSProperty(CSS_WIDTH);
    if ($wc->applicable($box)) {
      if ($box->parent) {
        $this->_maxw = $wc->apply($this->_maxw, $box->parent->get_width());
      } else {
        $this->_maxw = $wc->apply($this->_maxw, $this->_maxw);
      };
    };

    return $this->_maxw + $box->_get_hor_extra();
  }
}

?>