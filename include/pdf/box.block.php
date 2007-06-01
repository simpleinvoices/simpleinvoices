<?php
// $Header: /cvsroot/html2ps/box.block.php,v 1.48 2006/05/27 15:33:25 Konstantin Exp $

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
  function &create_from_text($content) {
    $box = new BlockBox();
    $box->add_child(InlineBox::create_from_text($content, $box->white_space));
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
    switch ($this->position) {
    case POSITION_STATIC:
      return $this->reflow_static($parent, $context);

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

      /**
       * Note that percentage positioning values are ignored for relative positioning
       */

      /**
       * Check if 'top' value is percentage
       */
      if ($this->top[1]) { 
        $top = 0;
      } else {
        $top = $this->top[0];
      }

      /**
       * Offset the box according to the calculated 'left' and 'top' values
       */
      $left = $this->get_css_left_value();
      if ($this->left[1]) {
        $left_offset = 0;
      } else {
        $left_offset = $left[0];
      };
      $this->offset($left_offset,-$top);

      return;
      
    case POSITION_ABSOLUTE:
      /**
       * If this box is positioned absolutely, it is not laid out as usual box;
       * The reference to this element is stored in the flow context for
       * futher reference.
       */
      return $context->add_absolute_positioned($this);

    case POSITION_FIXED:
      /**
       * If this box have 'position: fixed', it is not laid out as usual box;
       * The reference to this element is stored in the flow context for
       * futher reference.
       */
      return $context->add_fixed_positioned($this);
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
    GenericFormattedBox::reflow($this->parent, $context);

    /**
     * Box having 'position: absolute' are positioned relatively to their "containing blocks".
     *
     * @link http://www.w3.org/TR/CSS21/visudet.html#x0 CSS 2.1 Definition of "containing block"
     */
    $containing_block = $this->_get_containing_block();
    
    /** 
     * Calculate horizontal and vertical position of current block. 
     * Positioning properties are grouped to two pairs: 'left' + 'right' and 
     * 'top' + 'bottom'. 
     *
     * If both properies in the pair are not specified, 
     * the box is positioned at the coordinate it would take normally with 
     * 'position: static'
     *
     * If only one property is specified, the other is treated as 'auto'.
     *
     * If both properties are specified, the 'right' and 'bottom' have the precedence.
     *
     * @link http://www.w3.org/TR/CSS21/visuren.html#position-props Box offsets: 'top', 'right', 'bottom', 'left'
     */
    if (is_null($this->left) && is_null($this->right)) {
      $this->put_left($this->parent->get_left() + $this->get_extra_left());
    } elseif (!is_null($this->right)) {
      $this->put_left($containing_block['right'] - $this->right - $this->get_extra_right());
    } else {
      if ($this->left[1]) {
        $left = ($containing_block['right'] - $containing_block['left']) / 100 * $this->left[0];
      } else {
        $left = $this->left[0];
      };

      $this->put_left($left + $containing_block['left'] + $this->get_extra_left());
    };

    if (is_null($this->top) && is_null($this->bottom)) {
      $this->put_top($this->parent->get_top() + $this->get_extra_top());
    } elseif (!is_null($this->bottom)) {
      $this->put_top($containing_block['bottom'] + $this->bottom + $this->get_extra_bottom());
    } else {
      if ($this->top[1]) {
        $top = ($containing_block['top'] - $containing_block['bottom']) / 100 * $this->top[0];
      } else {
        $top = $this->top[0];
      };
      $this->put_top($containing_block['top'] - $top - $this->get_extra_top());
    };

    /**
     * As sometimes left/right values may not be set, we need to use the "fit" width here.
     * If box have a width constraint, 'get_max_width' will return constrained value; 
     * othersise, an intrictic width will be returned. 
     * 
     * Note that get_max_width returns width _including_ external space line margins, borders and padding;
     * as we're setting the "internal" - content width, we must subtract "extra" space width from the 
     * value received
     *
     * @see GenericContainerBox::get_max_width()
     */

    $this->put_width($this->get_max_width($context) - $this->_get_hor_extra());
    
    /**
     * Update the width, as it should be calculated based upon containing block width, not real parent.
     * After this we should remove width constraints or we may encounter problem 
     * in future when we'll try to call get_..._width functions for this box
     *
     * @todo Update the family of get_..._width function so that they would apply constraint
     * using the containing block width, not "real" parent width
     */
    $this->put_width($this->_width_constraint->apply($this->get_width(), 
                                                     $containing_block['right'] - $containing_block['left']));
    $this->put_width_constraint(new WCNone());

    /**
     * Layout element's children 
     */
    $this->reflow_content($context);

    /**
     * As absolute-positioned box generated new flow contexy, extend the height to fit all floats
     */
    $float_bottom = $context->float_bottom();     
    if (!is_null($float_bottom)) {
      $this->extend_height($float_bottom);
    };

    /** 
     * If element have been positioned using 'right' or 'bottom' property,
     * we need to offset it, as we assumed it had zero width and height at
     * the moment we placed it
     */
    if (is_null($this->left) && !is_null($this->right)) {
      $this->offset(-$this->get_width(), 0);
    };
    if (is_null($this->top) && !is_null($this->bottom)) {
      $this->offset(0, $this->get_height());
    };
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
    $this->put_full_width($this->_width_constraint->apply($this->get_width(), $this->get_width()));
    $this->put_width_constraint(new WCNone());
   
    /**
     * Layout element's children 
     */
    $this->reflow_content($context);

    /**
     * As fixed-positioned box generated new flow context, extend the height to fit all floats
     */
    $float_bottom = $context->float_bottom();     
    if (!is_null($float_bottom)) {
      $this->extend_height($float_bottom);
    };
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
    if ($this->float === FLOAT_NONE) {
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
      $this->put_full_width($parent->get_width());

      /**
       * Calculate margin values if they have been set as a percentage; replace percentage-based values 
       * with fixed lengths.
       */
      $this->_calc_percentage_margins($parent);

      /**
       * Calculate width value if it had been set as a percentage; replace percentage-based value
       * with fixed value
       */
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
      $this->moveto( $parent->get_left() + $this->get_extra_left(),
                     $parent->_current_y - $this->border->top->get_width()  - $this->padding->top->value );
    }

    /**
     * Reflow element's children
     */
    $this->reflow_content($context);

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
    $context->pop_collapsed_margin();
    $context->pop_collapsed_margin();
    $context->push_collapsed_margin( max($cm, $this->margin->bottom->value) );
   
    if ($parent) {
      /** 
       * Now, if there's a parent for this box, we extend its height to fit current box.
       * If parent generated new flow context (like table cell or floating box), its content 
       * area should include the current box bottom margin (bottom margin does not colllapse). 
       * See CSS 2.1 for more detailed explanations.
       *
       * @see FlowContext::container_uid()
       *
       * @link http://www.w3.org/TR/CSS21/visudet.html#Computing_widths_and_margins CSS 2.1 8.3.1 Calculating widths and margins
       */
      if ($parent->uid == $context->container_uid()) {
        $parent->extend_height($this->get_bottom_margin());
      } else {
        $parent->extend_height($this->get_bottom_border());
      }

      /**
       * Terminate parent's line box (it contains the current box only)
       */
      $parent->close_line($context);

      /**
       * shift current parent 'watermark' to the current box margin edge; 
       * all content now will be drawn below this mark (with a small exception 
       * of elements having negative vertical margins, of course).
       */
      $parent->_current_y = $this->get_bottom_border() - $context->get_collapsed_margin();
    };

    /**
     * Check if we need to generate a page break after this element
     */
    $this->check_page_break_after($parent, $context);
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
    if ($this->position == POSITION_FIXED) {
      /**
       * Calculate the distance between the top page edge and top box content edge
       */
      if (is_null($this->top) && is_null($this->bottom)) {
        $top_offset = 0;
      } elseif (!is_null($this->bottom)) {
        $top_offset = $driver->height - $this->get_height() - $this->bottom;
      } else {
        $top_offset = $this->top[0];
      };

      /**
       * Calculate the distance between the right page edge and right box content edge
       */
      if (is_null($this->left) && is_null($this->right)) {
        $left_offset = 0;
      } elseif (!is_null($this->right)) {
        $left_offset = $driver->width - $this->get_width() - $this->right;
      } else {
        $left = $this->get_css_left_value();
        $left_offset = $left[0];
      };
      
      /**
       * Offset current box to the required position on the current page (note that
       * fixed-positioned element are placed relatively to the viewport - page in our case)
       */
      $this->moveto($driver->left   + $left_offset,
                    $driver->bottom + $driver->height + $driver->offset - $top_offset);
    };

    /**
     * After box have benn properly positioned, render it as usual.
     */
    return GenericContainerBox::show_fixed($driver);
  }

  function is_block() { return true; }
}
?>