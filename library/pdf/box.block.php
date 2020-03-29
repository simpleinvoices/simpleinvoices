<?php
// $Header: /cvsroot/html2ps/box.block.php,v 1.56 2007/01/24 18:55:43 Konstantin Exp $

/**
 * @package HTML2PS
 * @subpackage Document
 * 
 * Class defined in this file handles the layout of block HTML elements
 */

/**
 * @package HTML2PS
 * @subpackage Document
 *
 * The BlockBox class describes the layout and behavior of HTML element having
 * 'display: block' CSS property.
 *
 * @link http://www.w3.org/TR/CSS21/visuren.html#block-box CSS 2.1 Block-level elements and block boxes
 */
class BlockBox extends GenericContainerBox {
  /**
   * Create empty block element
   */
  function BlockBox() {
    $this->GenericContainerBox();
  }

  /**
   * Create new block element and automatically fill in its contents using 
   * parsed HTML data
   *
   * @param mixed $root the HTML element corresponding to the element being created
   * 
   * @return BlockBox new BlockBox object (with contents filled)
   *
   * @see GenericContainerBox::create_content()
   */
  function &create(&$root, &$pipeline) {
    $box = new BlockBox();
    $box->readCSS($pipeline->getCurrentCSSState());
    $box->create_content($root, $pipeline);
    return $box;
  }

  /** 
   * Create new block element and automatically initialize its contents 
   * with the given text string
   *
   * @param string $content The text string to be put inside the block box
   *
   * @return BlockBox new BlockBox object (with contents filled)
   *
   * @see InlineBox
   * @see InlineBox::create_from_text()
   */
  function &create_from_text($content, &$pipeline) {
    $box = new BlockBox();
    $box->readCSS($pipeline->getCurrentCSSState());
    $box->add_child(InlineBox::create_from_text($content, 
                                                $box->getCSSProperty(CSS_WHITE_SPACE),
                                                $pipeline));
    return $box;
  }

  /**
   * Layout current block element 
   *
   * @param GenericContainerBox $parent The document element which should be treated as the parent of current element
   * @param FlowContext $context The flow context containing the additional layout data
   * 
   * @see FlowContext
   * @see GenericContainerBox
   * @see InlineBlockBox::reflow
   * 
   * @todo this 'reflow' skeleton is common for all element types; thus, we probably should move the generic 'reflow' 
   * definition to the GenericFormattedBox class, leaving only box-specific 'reflow_static' definitions in specific classes.
   *
   * @todo make relative positioning more CSS 2.1 compliant; currently, 'bottom' and 'right' CSS properties are ignored.
   *
   * @todo check whether percentage values should be really ignored during relative positioning
   */
  function reflow(&$parent, &$context) {
    switch ($this->getCSSProperty(CSS_POSITION)) {
    case POSITION_STATIC:
      $this->reflow_static($parent, $context);
      return;

    case POSITION_RELATIVE:
      /**
       * CSS 2.1:
       * Once a box has been laid out according to the normal flow or floated, it may be shifted relative 
       * to this position. This is called relative positioning. Offsetting a box (B1) in this way has no
       * effect on the box (B2) that follows: B2 is given a position as if B1 were not offset and B2 is 
       * not re-positioned after B1's offset is applied. This implies that relative positioning may cause boxes
       * to overlap. However, if relative positioning causes an 'overflow:auto' box to have overflow, the UA must
       * allow the user to access this content, which, through the creation of scrollbars, may affect layout.
       * 
       * @link http://www.w3.org/TR/CSS21/visuren.html#x28 CSS 2.1 Relative positioning
       */      
      $this->reflow_static($parent, $context);
      $this->offsetRelative();
      return;
      
    case POSITION_ABSOLUTE:
      /**
       * If this box is positioned absolutely, it is not laid out as usual box;
       * The reference to this element is stored in the flow context for
       * futher reference.
       */
      $this->guess_corner($parent);
      return;

    case POSITION_FIXED:
      /**
       * If this box have 'position: fixed', it is not laid out as usual box;
       * The reference to this element is stored in the flow context for
       * futher reference.
       */
      $this->guess_corner($parent);
      return;
    };
  }

  /**
   * Reflow absolutely positioned block box. Note that according to CSS 2.1 
   * the only types of boxes which could be absolutely positioned are 
   * 'block' and 'table'
   * 
   * @param FlowContext $context A flow context object containing the additional layout data.
   *
   * @link http://www.w3.org/TR/CSS21/visuren.html#dis-pos-flo CSS 2.1: Relationships between 'display', 'position', and 'float'
   */
  function reflow_absolute(&$context) {
    $parent_node =& $this->get_parent_node();
    parent::reflow($parent_node, $context);
  
    $width_strategy =& new StrategyWidthAbsolutePositioned();
    $width_strategy->apply($this, $context);

    $position_strategy =& new StrategyPositionAbsolute();
    $position_strategy->apply($this);
    
    $this->reflow_content($context);

    /**
     * As absolute-positioned box generated new flow context, extend the height to fit all floats
     */
    $this->fitFloats($context);
  }

  /**
   * Reflow fixed-positioned block box. Note that according to CSS 2.1 
   * the only types of boxes which could be absolutely positioned are 
   * 'block' and 'table'
   * 
   * @param FlowContext $context A flow context object containing the additional layout data.
   *
   * @link http://www.w3.org/TR/CSS21/visuren.html#dis-pos-flo CSS 2.1: Relationships between 'display', 'position', and 'float'
   *
   * @todo it seems that percentage-constrained fixed block width will be calculated incorrectly; we need 
   * to use containing block width instead of $this->get_width() when applying the width constraint
   */
  function reflow_fixed(&$context) {
    GenericFormattedBox::reflow($this->parent, $context);

    /**
     * As fixed-positioned elements are placed relatively to page (so that one element may be shown
     * several times on different pages), we cannot calculate its position at the moment.
     * The real position of the element is calculated when it is to be shown - once for each page.
     * 
     * @see BlockBox::show_fixed()
     */
    $this->put_left(0);
    $this->put_top(0);

    /**
     * As sometimes left/right values may not be set, we need to use the "fit" width here.
     * If box have a width constraint, 'get_max_width' will return constrained value; 
     * othersise, an intrictic width will be returned. 
     *
     * @see GenericContainerBox::get_max_width()
     */
    $this->put_full_width($this->get_max_width($context));
    
    /**
     * Update the width, as it should be calculated based upon containing block width, not real parent.
     * After this we should remove width constraints or we may encounter problem 
     * in future when we'll try to call get_..._width functions for this box
     *
     * @todo Update the family of get_..._width function so that they would apply constraint
     * using the containing block width, not "real" parent width
     */
    $containing_block =& $this->_get_containing_block();
    $wc = $this->getCSSProperty(CSS_WIDTH);
    $this->put_full_width($wc->apply($this->get_width(),
                                     $containing_block['right'] - $containing_block['left']));
    $this->setCSSProperty(CSS_WIDTH, new WCNone());
   
    /**
     * Layout element's children 
     */
    $this->reflow_content($context);

    /**
     * As fixed-positioned box generated new flow context, extend the height to fit all floats
     */
    $this->fitFloats($context);
  }

  /** 
   * Layout static-positioned block box.
   * 
   * Note that static-positioned boxes may be floating boxes
   *
   * @param GenericContainerBox $parent The document element which should be treated as the parent of current element
   * @param FlowContext $context The flow context containing the additional layout data
   * 
   * @see FlowContext
   * @see GenericContainerBox
   */
  function reflow_static(&$parent, &$context) {   
    if ($this->getCSSProperty(CSS_FLOAT) === FLOAT_NONE) {
      $this->reflow_static_normal($parent, $context);
    } else {
      $this->reflow_static_float($parent, $context);
    }
  }

  /**
   * Layout normal (non-floating) static-positioned block box.
   *
   * @param GenericContainerBox $parent The document element which should be treated as the parent of current element
   * @param FlowContext $context The flow context containing the additional layout data
   * 
   * @see FlowContext
   * @see GenericContainerBox
   */
  function reflow_static_normal(&$parent, &$context) {
    GenericFormattedBox::reflow($parent, $context);

    if ($parent) { 
      /**
       * Child block will fill the whole content width of the parent block.
       *
       * 'margin-left' + 'border-left-width' + 'padding-left' + 'width' + 'padding-right' +
       * 'border-right-width' + 'margin-right' = width of containing block
       *
       * See CSS 2.1 for more detailed explanation
       *
       * @link http://www.w3.org/TR/CSS21/visudet.html#blockwidth CSS 2.1. 10.3.3 Block-level, non-replaced elements in normal flow
       */

      /**
       * Calculate margin values if they have been set as a percentage; replace percentage-based values 
       * with fixed lengths.
       */
      $this->_calc_percentage_margins($parent);
      $this->_calc_percentage_padding($parent);

      /**
       * Calculate width value if it had been set as a percentage; replace percentage-based value
       * with fixed value
       */
      $this->put_full_width($parent->get_width());
      $this->_calc_percentage_width($parent, $context);

      /**
       * Calculate 'auto' values of width and margins. Unlike tables, DIV width is either constrained
       * by some CSS rules or expanded to the parent width; thus, we can calculate 'auto' margin 
       * values immediately.
       *
       * @link http://www.w3.org/TR/CSS21/visudet.html#Computing_widths_and_margins CSS 2.1 Calculating widths and margins
       */
      $this->_calc_auto_width_margins($parent); 

      /**
       * Collapse top margin
       *
       * @see GenericFormattedBox::collapse_margin()
       *
       * @link http://www.w3.org/TR/CSS21/box.html#collapsing-margins CSS 2.1 Collapsing margins
       */
      $y = $this->collapse_margin($parent, $context);

      /**
       * At this moment we have top parent/child collapsed margin at the top of context object
       * margin stack
       */

      /**
       * Apply 'clear' property; the current Y coordinate can be modified as a result of 'clear'.
       */
      $y = $this->apply_clear($y, $context);

      /**
       * Store calculated Y coordinate as current Y coordinate in the parent box
       * No more content will be drawn abowe this mark; current box padding area will 
       * start below.
       */
      $parent->_current_y = $y;

      /**
       * Terminate current parent line-box (as current box is not inline)
       */
      $parent->close_line($context);

      /**
       * Add current box to the parent's line-box; we will close the line box below 
       * after content will be reflown, so the line box will contain only current box.
       */
      $parent->append_line($this);

      /**
       * Now, place the current box upper left content corner. Note that we should not 
       * use get_extra_top here, as _current_y value already culculated using the top margin value
       * of the current box! The top content edge should be offset from that level only of padding and
       * border width.
       */
      $border  = $this->getCSSProperty(CSS_BORDER);
      $padding = $this->getCSSProperty(CSS_PADDING);

      $this->moveto( $parent->get_left() + $this->get_extra_left(),
                     $parent->_current_y - $border->top->get_width()  - $padding->top->value );
    }

    /**
     * Reflow element's children
     */
    $this->reflow_content($context);

    if ($this->getCSSProperty(CSS_OVERFLOW) != OVERFLOW_VISIBLE) {
      $this->fitFloats($context);
    }

    /**
     * After child elements have been reflown, we should the top collapsed margin stack value
     * replaced by the value of last child bottom collapsed margin; 
     * if no children contained, then this value should be reset to 0. 
     *
     * Note that invisible and 
     * whitespaces boxes would not affect the collapsed margin value, so we need to 
     * use 'get_first' function instead of just accessing the $content array.
     *
     * @see GenericContainerBox::get_first
     */
    if (!is_null($this->get_first())) {
      $cm = 0;
    } else {
      $cm = $context->get_collapsed_margin();
    };

    /**
     * Update the bottom  value, collapsing the latter value with 
     * current box bottom margin.
     *
     * Note that we need to remove TWO values from the margin stack:
     * first - the value of collapsed bottom margin of the last child AND
     * second - the value of collapsed top margin of current element.
     */
    $margin = $this->getCSSProperty(CSS_MARGIN);
       
    if ($parent) {
      /**
       * Terminate parent's line box (it contains the current box only)
       */
      $parent->close_line($context);

      $parent->_current_y = $this->collapse_margin_bottom($parent, $context);
    };
  }

  function show(&$driver) {
    if ($this->getCSSProperty(CSS_FLOAT)    != FLOAT_NONE || 
        $this->getCSSProperty(CSS_POSITION) == POSITION_RELATIVE) {
      // These boxes will be rendered separately
      return true;
    };

    return parent::show($driver);
  }

  function show_postponed(&$driver) {
    return parent::show($driver);
  }

  /**
   * Show fixed positioned block box using the specified output driver
   * 
   * Note that 'show_fixed' is called to box _nested_ to the fixed-positioned boxes too! 
   * Thus, we need to check whether actual 'position' values is 'fixed' for this box 
   * and only in that case attempt to move box
   *
   * @param OutputDriver $driver The output device driver object
   */
  function show_fixed(&$driver) {
    $position = $this->getCSSProperty(CSS_POSITION);

    if ($position == POSITION_FIXED) {
      /**
       * Calculate the distance between the top page edge and top box content edge
       */
      $bottom = $this->getCSSProperty(CSS_BOTTOM);
      $top    = $this->getCSSProperty(CSS_TOP);

      if (!$top->isAuto()) {
        if ($top->isPercentage()) {
          $vertical_offset = $driver->getPageMaxHeight() / 100 * $top->getPercentage();
        } else {
          $vertical_offset = $top->getPoints();
        };

      } elseif (!$bottom->isAuto()) { 
        if ($bottom->isPercentage()) {
          $vertical_offset = $driver->getPageMaxHeight() * (100 - $bottom->getPercentage())/100 - $this->get_height();
        } else {
          $vertical_offset = $driver->getPageMaxHeight() - $bottom->getPoints() - $this->get_height();
        };

      } else {
        $vertical_offset = 0;
      };

      /**
       * Calculate the distance between the right page edge and right box content edge
       */
      $left  = $this->getCSSProperty(CSS_LEFT);
      $right = $this->getCSSProperty(CSS_RIGHT);

      if (!$left->isAuto()) {
        if ($left->isPercentage()) {
          $horizontal_offset = $driver->getPageWidth() / 100 * $left->getPercentage();
        } else {
          $horizontal_offset = $left->getPoints();
        };

      } elseif (!$right->isAuto()) { 
        if ($right->isPercentage()) {
          $horizontal_offset = $driver->getPageWidth() * (100 - $right->getPercentage())/100 - $this->get_width();
        } else {
          $horizontal_offset = $driver->getPageWidth() - $right->getPoints() - $this->get_width();
        };

      } else {
        $horizontal_offset = 0;
      };
     
      /**
       * Offset current box to the required position on the current page (note that
       * fixed-positioned element are placed relatively to the viewport - page in our case)
       */
      $this->moveto($driver->getPageLeft() + $horizontal_offset,
                    $driver->getPageTop()  - $vertical_offset);
    };

    /**
     * After box have benn properly positioned, render it as usual.
     */
    return GenericContainerBox::show_fixed($driver);
  }

  function isBlockLevel() {
    return true;
  }
}
?>