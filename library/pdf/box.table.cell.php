<?php
// $Header: /cvsroot/html2ps/box.table.cell.php,v 1.40 2007/01/24 18:55:45 Konstantin Exp $

class TableCellBox extends GenericContainerBox {
  var $colspan;
  var $rowspan;
  var $column;

  var $_suppress_first;
  var $_suppress_last;

  function TableCellBox() {
    // Call parent constructor
    $this->GenericContainerBox();

    $this->_suppress_first = false;
    $this->_suppress_last  = false;
    
    $this->colspan = 1;
    $this->rowspan = 1;

    // This value will be overwritten in table 'normalize_parent' method
    //
    $this->column  = 0;
    $this->row     = 0;
  }

  function get_min_width(&$context) {   
    if (isset($this->_cache[CACHE_MIN_WIDTH])) {
      return $this->_cache[CACHE_MIN_WIDTH];
    };

    $content_size = count($this->content);

    /**
     * If box does not have any context, its minimal width is determined by extra horizontal space:
     * padding, border width and margins
     */
    if ($content_size == 0) { 
      $min_width = $this->_get_hor_extra();
      $this->_cache[CACHE_MIN_WIDTH] = $min_width;
      return $min_width;
    };

    /**
     * If we're in 'nowrap' mode, minimal and maximal width will be equal
     */
    $white_space = $this->getCSSProperty(CSS_WHITE_SPACE);
    $pseudo_nowrap = $this->getCSSProperty(CSS_HTML2PS_NOWRAP);
    if ($white_space   == WHITESPACE_NOWRAP || 
        $pseudo_nowrap == NOWRAP_NOWRAP) { 
      $min_width = $this->get_min_nowrap_width($context);
      $this->_cache[CACHE_MIN_WIDTH] = $min_width;
      return $min_width; 
    }

    /**
     * We need to add text indent size to the with of the first item
     */
    $start_index = 0;
    while ($start_index < $content_size && 
           $this->content[$start_index]->out_of_flow()) {
      $start_index++; 
    };
    
    if ($start_index < $content_size) {
      $ti = $this->getCSSProperty(CSS_TEXT_INDENT);
      $minw = 
        $ti->calculate($this) + 
        $this->content[$start_index]->get_min_width($context);
    } else {
      $minw = 0;
    };

    for ($i=$start_index; $i<$content_size; $i++) {
      $item =& $this->content[$i];
      if (!$item->out_of_flow()) {
        $minw = max($minw, $item->get_min_width_natural($context));
      };
    }

    /**
     * Apply width constraint to min width. Return maximal value
     */
    $wc = $this->getCSSProperty(CSS_WIDTH);
    $min_width = max($minw, 
                     $wc->apply($minw, $this->parent->get_width())) + $this->_get_hor_extra();
    $this->_cache[CACHE_MIN_WIDTH] = $min_width;
    return $min_width;
  }

  function readCSS(&$state) {
    parent::readCSS($state);

    $this->_readCSS($state,
                    array(CSS_BORDER_COLLAPSE));

    $this->_readCSSLengths($state,
                           array(CSS_HTML2PS_CELLPADDING,
                                 CSS_HTML2PS_CELLSPACING,
                                 CSS_HTML2PS_TABLE_BORDER));
  }

  function isCell() { 
    return true;
  }

  function is_fake() {
    return false;
  }

  function &create(&$root, &$pipeline) {
    $css_state = $pipeline->getCurrentCSSState();

    $box =& new TableCellBox();
    $box->readCSS($css_state);

    // Use cellspacing / cellpadding values from the containing table
    $cellspacing = $box->getCSSProperty(CSS_HTML2PS_CELLSPACING);
    $cellpadding = $box->getCSSProperty(CSS_HTML2PS_CELLPADDING);

    // FIXME: I'll need to resolve that issue with COLLAPSING border model. Now borders
    // are rendered separated

    // if not border set explicitly, inherit value set via border attribute of TABLE tag
    $border_handler = CSS::get_handler(CSS_BORDER);
    if ($border_handler->is_default($box->getCSSProperty(CSS_BORDER))) {
      $table_border = $box->getCSSProperty(CSS_HTML2PS_TABLE_BORDER);
      $box->setCSSProperty(CSS_BORDER, $table_border);
    };

    $margin =& CSS::get_handler(CSS_MARGIN);
    $box->setCSSProperty(CSS_MARGIN, $margin->default_value());
      
    $h_padding =& CSS::get_handler(CSS_PADDING);
    $padding = $box->getCSSProperty(CSS_PADDING);

    if ($h_padding->is_default($padding)) {
      $padding->left->_units       = $cellpadding;
      $padding->left->auto         = false;
      $padding->left->percentage   = null;

      $padding->right->_units      = $cellpadding;
      $padding->right->auto        = false;
      $padding->right->percentage  = null;

      $padding->top->_units        = $cellpadding;
      $padding->top->auto          = false;
      $padding->top->percentage    = null;

      $padding->bottom->_units     = $cellpadding;
      $padding->bottom->auto       = false;
      $padding->bottom->percentage = null;

      /**
       * Note that cellpadding/cellspacing values never use font-size based units
       * ('em' and 'ex'), so we may pass 0 as base_font_size parameter - it 
       * will not be used anyway
       */
      $padding->units2pt(0);

      $box->setCSSProperty(CSS_PADDING, $padding);
    };
       
    if ($box->getCSSProperty(CSS_BORDER_COLLAPSE) != BORDER_COLLAPSE) {
      $margin_value = $box->getCSSProperty(CSS_MARGIN);
      if ($margin->is_default($margin_value)) {
        $length = $cellspacing->copy();
        $length->scale(0.5);

        $margin_value->left->_units       = $length;
        $margin_value->left->auto         = false;
        $margin_value->left->percentage   = null;

        $margin_value->right->_units      = $length;
        $margin_value->right->auto        = false;
        $margin_value->right->percentage  = null;

        $margin_value->top->_units        = $length;
        $margin_value->top->auto          = false;
        $margin_value->top->percentage    = null;

        $margin_value->bottom->_units     = $length;
        $margin_value->bottom->auto       = false;
        $margin_value->bottom->percentage = null;

        /**
         * Note that cellpadding/cellspacing values never use font-size based units
         * ('em' and 'ex'), so we may pass 0 as base_font_size parameter - it 
         * will not be used anyway
         */
        $margin_value->units2pt(0);

        $box->setCSSProperty(CSS_MARGIN, $margin_value);
      }
    };

    // Save colspan and rowspan information
    $box->colspan = max(1,(int)$root->get_attribute('colspan'));
    $box->rowspan = max(1,(int)$root->get_attribute('rowspan'));

    // Create content 

    // 'vertical-align' CSS value is not inherited from the table cells
    $css_state->pushState();

    $handler =& CSS::get_handler(CSS_VERTICAL_ALIGN);
    $handler->replace($handler->default_value(),
                      $css_state);

    $box->create_content($root, $pipeline);

    global $g_config;
    if ($g_config['mode'] == "quirks") {
      // QUIRKS MODE:
      // H1-H6 and P elements should have their top/bottom margin suppressed if they occur as the first/last table cell child 
      // correspondingly; note that we cannot do it usung CSS rules, as there's no selectors for the last child. 
      //
      $child = $root->first_child();
      if ($child) {
        while ($child && $child->node_type() != XML_ELEMENT_NODE) {
          $child = $child->next_sibling();
        };
      
        if ($child) {
          if (array_search(strtolower($child->tagname()), array("h1","h2","h3","h4","h5","h6","p"))) {
            $box->_suppress_first = true;
          }
        };
      };

      $child = $root->last_child();
      if ($child) {
        while ($child && $child->node_type() != XML_ELEMENT_NODE) {
          $child = $child->previous_sibling();
        };
        
        if ($child) {
          if (array_search(strtolower($child->tagname()), array("h1","h2","h3","h4","h5","h6","p"))) {
            $box->_suppress_last = true;
          }
        };
      };
    };

    // pop the default vertical-align value
    $css_state->popState();

    return $box;
  }

  // Inherited from GenericFormattedBox

  function get_cell_baseline() {
    $content = $this->get_first_data();
    if (is_null($content)) { 
      return 0; 
    }
    return $content->baseline;
  }

  // Flow-control
  function reflow(&$parent, &$context) {
    GenericFormattedBox::reflow($parent, $context);

    global $g_config;
    $size = count($this->content);
    if ($g_config['mode'] == "quirks" && $size > 0) {
      // QUIRKS MODE:
      // H1-H6 and P elements should have their top/bottom margin suppressed if they occur as the first/last table cell child 
      // correspondingly; note that we cannot do it usung CSS rules, as there's no selectors for the last child. 
      //
      
      $first =& $this->get_first();
      if (!is_null($first) && $this->_suppress_first && $first->isBlockLevel()) {
        $first->margin->top->value = 0;
        $first->margin->top->percentage = null;
      };

      $last =& $this->get_last();
      if (!is_null($last) && $this->_suppress_last && $last->isBlockLevel()) {
        $last->margin->bottom->value = 0;
        $last->margin->bottom->percentage = null;
      };
    };

    // Determine upper-left _content_ corner position of current box 
    $this->put_left($parent->_current_x + $this->get_extra_left());

    // NOTE: Table cell margin is used as a cell-spacing value
    $border = $this->getCSSProperty(CSS_BORDER);
    $padding = $this->getCSSProperty(CSS_PADDING);
    $this->put_top($parent->_current_y - 
                   $border->top->get_width() - 
                   $padding->top->value);

    // CSS 2.1: 
    // Floats, absolutely positioned elements, inline-blocks, table-cells, and elements with 'overflow' other than
    // 'visible' establish new block formatting contexts.
    $context->push();
    $context->push_container_uid($this->uid);

    // Reflow cell content
    $this->reflow_content($context);

    // Extend the table cell height to fit all contained floats
    //
    // Determine the bottom edge corrdinate of the bottommost float
    //
    $float_bottom = $context->float_bottom();
      
    if (!is_null($float_bottom)) {
      $this->extend_height($float_bottom);
    };

    // Restore old context
    $context->pop_container_uid();
    $context->pop();
  }
}

?>