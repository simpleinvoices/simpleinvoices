<?php

class StrategyWidthMin {
  var $_maxw;
  var $_cmaxw;

  function StrategyWidthMin() {
  }

  function add_width($delta) {
    $this->_cmaxw += $delta;
  }

  function line_break() {
    $this->_maxw  = max($this->_maxw, $this->_cmaxw);
    $this->_cmaxw = 0;
  }

  function apply(&$box, &$context) {
    $content_size = count($box->content);

    /**
     * If box does not have any context, its minimal width is determined by extra horizontal space:
     * padding, border width and margins
     */
    if ($content_size == 0) { 
      $min_width = $box->_get_hor_extra();
      return $min_width;
    };

    /**
     * If we're in 'nowrap' mode, minimal and maximal width will be equal
     */
    $white_space = $box->getCSSProperty(CSS_WHITE_SPACE);
    $pseudo_nowrap = $box->getCSSProperty(CSS_HTML2PS_NOWRAP);
    if ($white_space   == WHITESPACE_NOWRAP || 
        $pseudo_nowrap == NOWRAP_NOWRAP) { 
      $min_width = $box->get_min_nowrap_width($context);
      return $min_width; 
    }

    /**
     * We need to add text indent size to the with of the first item
     */
    $start_index = 0;
    while ($start_index < $content_size && 
           $box->content[$start_index]->out_of_flow()) { 
      $start_index++; 
    };

    if ($start_index < $content_size) {
      $ti = $box->getCSSProperty(CSS_TEXT_INDENT);
      $minw = 
        $ti->calculate($box) + 
        $box->content[$start_index]->get_min_width($context);
    } else {
      $minw = 0;
    };

    for ($i=$start_index; $i<$content_size; $i++) {
      $item =& $box->content[$i];
      if (!$item->out_of_flow()) {
        $minw = max($minw, $item->get_min_width($context));
      };
    };

    /**
     * Apply width constraint to min width. Return maximal value
     */
    $wc = $box->getCSSProperty(CSS_WIDTH);
    $containing_block = $box->_get_containing_block();

    $min_width = max($minw, 
                     $wc->apply($minw, $containing_block['right'] - $containing_block['left'])) + $box->_get_hor_extra();
    return $min_width;
  }
}

?>