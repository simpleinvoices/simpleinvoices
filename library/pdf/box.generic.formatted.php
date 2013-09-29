<?php
// $Header: /cvsroot/html2ps/box.generic.formatted.php,v 1.21 2007/02/18 09:55:10 Konstantin Exp $

require_once(HTML2PS_DIR.'doc.anchor.class.php');
require_once(HTML2PS_DIR.'layout.vertical.php');

class GenericFormattedBox extends GenericBox {
  var $uid;

  function _get_collapsable_top_margin_internal() {
    $positive_margin = 0;
    $negative_margin = 0;

    $current_box = $this;

    $border  = $current_box->getCSSProperty(CSS_BORDER);
    $padding = $current_box->getCSSProperty(CSS_PADDING);
    if ($border->top->get_width() > 0 ||
        $padding->top->value > 0) {
      return 0;
    };

    while (!is_null($current_box) && 
           $current_box->isBlockLevel()) {
      $margin  = $current_box->getCSSProperty(CSS_MARGIN);
      $border  = $current_box->getCSSProperty(CSS_BORDER);
      $padding = $current_box->getCSSProperty(CSS_PADDING);

      $top_margin = $margin->top->value;

      if ($top_margin >= 0) {
        $positive_margin = max($positive_margin, $top_margin);
      } else {
        $negative_margin = min($negative_margin, $top_margin);
      };

      if ($border->top->get_width() > 0 ||
          $padding->top->value > 0) {
        $current_box = null;
      } else {
        $current_box = $current_box->get_first();
      };
    };

    return $positive_margin /*- $negative_margin*/;
  }

  function _get_collapsable_top_margin_external() {
    $positive_margin = 0;
    $negative_margin = 0;

    $current_box = $this;
    while (!is_null($current_box) && 
           $current_box->isBlockLevel()) {
      $margin  = $current_box->getCSSProperty(CSS_MARGIN);
      $border  = $current_box->getCSSProperty(CSS_BORDER);
      $padding = $current_box->getCSSProperty(CSS_PADDING);

      $top_margin = $margin->top->value;

      if ($top_margin >= 0) {
        $positive_margin = max($positive_margin, $top_margin);
      } else {
        $negative_margin = min($negative_margin, $top_margin);
      };

      if ($border->top->get_width() > 0 ||
          $padding->top->value > 0) {
        $current_box = null;
      } else {
        $current_box = $current_box->get_first();
      };
    };

    return $positive_margin + $negative_margin;
  }

  function _get_collapsable_bottom_margin_external() {
    $positive_margin = 0;
    $negative_margin = 0;

    $current_box = $this;
    while (!is_null($current_box) && 
           $current_box->isBlockLevel()) {
      $margin  = $current_box->getCSSProperty(CSS_MARGIN);
      $border  = $current_box->getCSSProperty(CSS_BORDER);
      $padding = $current_box->getCSSProperty(CSS_PADDING);

      $bottom_margin = $margin->bottom->value;

      if ($bottom_margin >= 0) {
        $positive_margin = max($positive_margin, $bottom_margin);
      } else {
        $negative_margin = min($negative_margin, $bottom_margin);
      };

      if ($border->bottom->get_width() > 0 ||
          $padding->bottom->value > 0) {
        $current_box = null;
      } else {
        $current_box = $current_box->get_last();
      };
    };

    return $positive_margin + $negative_margin;
  }

  function collapse_margin_bottom(&$parent, &$context) {
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
    $parent_border  = $parent->getCSSProperty(CSS_BORDER);
    $parent_padding = $parent->getCSSProperty(CSS_PADDING);

    /**
     * The  bottom margin  of an  in-flow block-level  element  with a
     * 'height'  of 'auto'  and 'min-height'  less than  the element's
     * used height  and 'max-height'  greater than the  element's used
     * height  is adjoining  to its  last in-flow  block-level child's
     * bottom margin if the element has NO BOTTOM PADDING OR BORDER.
     */

    $last =& $parent->get_last();
    $is_last = !is_null($last) && $this->uid == $last->uid;

    if (!is_null($last) && 
        $is_last &&                                  // This element is a last in-flow block level element AND
        $parent->uid != $context->container_uid() && // Parent element did not generate new flow context (like table-cell) AND
        $parent_border->bottom->get_width() == 0  && // Parent have NO bottom border AND
        $parent_padding->bottom->value == 0)  {      // Parent have NO bottom padding AND
      $parent->extend_height($this->get_bottom_border());
    } else {
      // Otherwise (in particular, if this box is not last), bottom
      // margin of the current box will be contained inside the current box
      $parent->extend_height($this->get_bottom_margin());      
    }

    $cm = $context->get_collapsed_margin();
    $context->pop_collapsed_margin();
    $context->pop_collapsed_margin();

    /**
     * shift current parent 'watermark' to the current box margin edge; 
     * all content now will be drawn below this mark (with a small exception 
     * of elements having negative vertical margins, of course).
     */
    if ($is_last &&
        ($parent_border->bottom->get_width() > 0 ||
         $parent_padding->bottom->value > 0)) {
      $context->push_collapsed_margin( 0 );
      return $this->get_bottom_border() - $cm;
    } else {
      $collapsable = $this->_get_collapsable_bottom_margin_external();
      $context->push_collapsed_margin( $collapsable );

      return $this->get_bottom_border();
    };
  }

  function collapse_margin(&$parent, &$context) {
    // Do margin collapsing

    // Margin collapsing is done as follows:
    // 1. If previous sibling was an inline element (so, parent line box was not empty),
    //    then no collapsing will take part
    // 2. If NO previous element exists at all, then collapse current box top margin
    //    with parent's collapsed top margin.
    // 2.1. If parent element was float, no collapsing should be
    // 3. If there's previous block element, collapse current box top margin 
    //    with previous elemenent's collapsed bottom margin
      
    // Check if current parent line box contains inline elements only. In this case the only 
    // margin will be current box margin 

    if (!$parent->line_box_empty()) {
      // Case (1). Previous element was inline element; no collapsing

      $parent->close_line($context);

      $vmargin = $this->_get_collapsable_top_margin_external();
    } else {   
      $parent_first = $this->parent->get_first();

      if (is_null($parent_first) || // Unfortunately, we sometimes get null as a value of $parent_first; this should be checked
          $parent_first->uid == $this->uid) {
        // Case (2). No previous block element at all; Collapse with parent margins
        $collapsable = $this->_get_collapsable_top_margin_external();
        $collapsed   = $context->get_collapsed_margin();

        $vmargin = max(0, $collapsable - $collapsed);

      } else {
        // Case (3). There's a previous block element

        $collapsable = $this->_get_collapsable_top_margin_external();
        $collapsed   = $context->get_collapsed_margin();
        
        // In this case, base position is a bottom border of the previous element
        // $vmargin - offset from a base position - should be at least $collapsed
        // (value of collapsed bottom margins from the previous element and its
        // children). If current element have $collapsable - collapsed top margin 
        // (from itself and children too) greater that this value, we should 
        // offset it further to the bottom

        $vmargin = max($collapsable, $collapsed);
      };
    };

    // Determine the base Y coordinate of box margin edge
    $y = $parent->_current_y - $vmargin;

    $internal_margin = $this->_get_collapsable_top_margin_internal();
    $context->push_collapsed_margin($internal_margin);

    return $y;
  }

  function GenericFormattedBox() {
    $this->GenericBox();

    // Layout data
    $this->baseline = 0;
    $this->parent = null;
  }

  function readCSS(&$state) {
    parent::readCSS($state);
    
    $this->_readCSS($state,
                    array(CSS_OVERFLOW,
                          CSS_PAGE_BREAK_AFTER,
                          CSS_PAGE_BREAK_BEFORE,
                          CSS_PAGE_BREAK_INSIDE,
                          CSS_ORPHANS,
                          CSS_WIDOWS,
                          CSS_POSITION,
                          CSS_TEXT_ALIGN,
                          CSS_WHITE_SPACE,
                          CSS_CLEAR,
                          CSS_CONTENT,
                          CSS_HTML2PS_PSEUDOELEMENTS,
                          CSS_FLOAT,
                          CSS_Z_INDEX,
                          CSS_HTML2PS_ALIGN,
                          CSS_HTML2PS_NOWRAP,
                          CSS_DIRECTION,
                          CSS_PAGE));
 
    $this->_readCSSLengths($state,
                           array(CSS_BACKGROUND,
                                 CSS_BORDER,
                                 CSS_BOTTOM,
                                 CSS_TOP,
                                 CSS_LEFT, 
                                 CSS_RIGHT,
                                 CSS_MARGIN,
                                 CSS_PADDING,
                                 CSS_TEXT_INDENT,
                                 CSS_HTML2PS_COMPOSITE_WIDTH,
                                 CSS_HEIGHT,
                                 CSS_MIN_HEIGHT,
                                 CSS_MAX_HEIGHT,
                                 CSS_LETTER_SPACING
                                 ));   

    /**
     * CSS 2.1,  p 8.5.2: 
     *
     * If an  element's border  color is not  specified with  a border
     * property,  user agents  must  use the  value  of the  element's
     * 'color' property as the computed value for the border color.
     */
    $border =& $this->getCSSProperty(CSS_BORDER);
    $color  =& $this->getCSSProperty(CSS_COLOR);

    if ($border->top->isDefaultColor()) {
      $border->top->setColor($color);
    };

    if ($border->right->isDefaultColor()) {
      $border->right->setColor($color);
    };

    if ($border->bottom->isDefaultColor()) {
      $border->bottom->setColor($color);
    };

    if ($border->left->isDefaultColor()) {
      $border->left->setColor($color);
    };

    $this->setCSSProperty(CSS_BORDER, $border);

    $this->_height_constraint =& HCConstraint::create($this);
    $this->height = 0;

    // 'width'
    $wc =& $this->getCSSProperty(CSS_WIDTH);
    $this->width = $wc->apply(0,0);

    // 'PSEUDO-CSS' properties

    // '-localalign'
    switch ($state->getProperty(CSS_HTML2PS_LOCALALIGN)) {
    case LA_LEFT:
      break;
    case LA_RIGHT:
      $margin =& $this->getCSSProperty(CSS_MARGIN);
      $margin->left->auto = true;
      $this->setCSSProperty(CSS_MARGIN, $margin);
      break;
    case LA_CENTER:
      $margin =& $this->getCSSProperty(CSS_MARGIN);
      $margin->left->auto  = true;
      $margin->right->auto = true;
      $this->setCSSProperty(CSS_MARGIN, $margin);
      break;
    };
  }

  function _calc_percentage_margins(&$parent) {
    $margin = $this->getCSSProperty(CSS_MARGIN);
    $containing_block =& $this->_get_containing_block();
    $margin->calcPercentages($containing_block['right'] - $containing_block['left']);
    $this->setCSSProperty(CSS_MARGIN, $margin);
  }

  function _calc_percentage_padding(&$parent) {
    $padding = $this->getCSSProperty(CSS_PADDING);
    $containing_block =& $this->_get_containing_block();
    $padding->calcPercentages($containing_block['right'] - $containing_block['left']);
    $this->setCSSProperty(CSS_PADDING, $padding);
  }

  function apply_clear($y, &$context) {
    return LayoutVertical::apply_clear($this, $y, $context);
  }


  /**
   * CSS 2.1:
   * 10.2 Content width: the 'width' property
   * Values have the following meanings:
   * <percentage> Specifies a percentage width. The percentage is calculated with respect to the width of the generated box's containing block.
   *
   * If the containing block's width depends on this element's width, 
   * then the resulting layout is undefined in CSS 2.1. 
   */
  function _calc_percentage_width(&$parent, &$context) {
    $wc = $this->getCSSProperty(CSS_WIDTH);
    if ($wc->isFraction()) { 
      $containing_block =& $this->_get_containing_block();

      // Calculate actual width
      $width = $wc->apply($this->width, $containing_block['right'] - $containing_block['left']);

      // Assign calculated width
      $this->put_width($width);
        
      // Remove any width constraint
      $this->setCSSProperty(CSS_WIDTH, new WCConstant($width));
    }
  }

  function _calc_auto_width_margins(&$parent) {
    $float = $this->getCSSProperty(CSS_FLOAT);

    if ($float !== FLOAT_NONE) {
      $this->_calc_auto_width_margins_float($parent);
    } else {
      $this->_calc_auto_width_margins_normal($parent);
    }
  }

  // 'auto' margin value became 0, 'auto' width is 'shrink-to-fit'
  function _calc_auto_width_margins_float(&$parent) {
    // If 'width' is set to 'auto' the used value is the "shrink-to-fit" width
    // TODO
    if (false) {
      // Calculation of the shrink-to-fit width is similar to calculating the
      // width of a table cell using the automatic table layout
      // algorithm. Roughly: calculate the preferred width by formatting the
      // content without breaking lines other than where explicit line breaks
      // occur, and also calculate the preferred minimum width, e.g., by trying
      // all possible line breaks. CSS 2.1 does not define the exact
      //  algorithm. Thirdly, find the available width: in this case, this is
      // the width of the containing block minus minus the used values of
      // 'margin-left', 'border-left-width', 'padding-left', 'padding-right',
      //  'border-right-width', 'margin-right', and the widths of any relevant
      // scroll bars.
      
      // Then the shrink-to-fit width is: min(max(preferred minimum width, available width), preferred width).
      
      // Store used value
    };
  
    // If 'margin-left', or 'margin-right' are computed as 'auto', their used value is '0'.
    $margin = $this->getCSSProperty(CSS_MARGIN);
    if ($margin->left->auto) { $margin->left->value = 0; }
    if ($margin->right->auto) { $margin->right->value = 0; }
    $this->setCSSProperty(CSS_MARGIN, $margin);

    $this->width = $this->get_width();
  }

  // 'margin-left' + 'border-left-width' + 'padding-left' + 'width' + 'padding-right' + 'border-right-width' + 'margin-right' = width of containing block
  function _calc_auto_width_margins_normal(&$parent) {
    // get the containing block width
    $containing_block =& $this->_get_containing_block();
    $parent_width = $containing_block['right'] - $containing_block['left'];
   
    // If 'width' is set to 'auto', any other 'auto' values become '0'  and 'width' follows from the resulting equality.

    // If both 'margin-left' and 'margin-right' are 'auto', their used values are equal. 
    // This horizontally centers the element with respect to the edges of the containing block.
    
    $margin = $this->getCSSProperty(CSS_MARGIN);
    if ($margin->left->auto && $margin->right->auto) {
      $margin_value = ($parent_width - $this->get_full_width()) / 2;
      $margin->left->value = $margin_value;
      $margin->right->value = $margin_value;
    } else {
      // If there is exactly one value specified as 'auto', its used value follows from the equality.
      if ($margin->left->auto) {
        $margin->left->value = $parent_width - $this->get_full_width();
      } elseif ($margin->right->auto) {
        $margin->right->value = $parent_width - $this->get_full_width();
      };
    };
    $this->setCSSProperty(CSS_MARGIN, $margin);
    
    $this->width = $this->get_width();
  }

  function get_descender() {
    return 0;
  }

  function get_ascender() {
    return 0;
  }

  function _get_vert_extra() {
    return
      $this->get_extra_top() +
      $this->get_extra_bottom();
  }

  function _get_hor_extra() {
    return
      $this->get_extra_left() + 
      $this->get_extra_right();
  }
  
  // Width:
  // 'get-min-width' stub
  function get_min_width(&$context) {
    die("OOPS! Unoverridden get_min_width called in class ".get_class($this)." inside ".get_class($this->parent));
  }

  function get_preferred_width(&$context) {
    return $this->get_max_width($context);
  }

  function get_preferred_minimum_width(&$context) {
    return $this->get_min_width($context);
  }

  // 'get-max-width' stub
  function get_max_width(&$context) {
    die("OOPS! Unoverridden get_max_width called in class ".get_class($this)." inside ".get_class($this->parent));
  }

  function get_max_width_natural(&$context) {
    return $this->get_max_width($context);
  }

  function get_full_width() { 
    return $this->get_width() + $this->_get_hor_extra(); 
  }

  function put_full_width($value) {
    // Calculate value of additional horizontal space consumed by margins and padding
    $this->width = $value - $this->_get_hor_extra();
  }

  function &_get_containing_block() {
    $position = $this->getCSSProperty(CSS_POSITION);

    switch ($position) {
    case POSITION_ABSOLUTE:
      $containing_block =& $this->_get_containing_block_absolute();
      return $containing_block;
    case POSITION_FIXED:
      $containing_block =& $this->_get_containing_block_fixed();
      return $containing_block;
    case POSITION_STATIC:
    case POSITION_RELATIVE:
      $containing_block =& $this->_get_containing_block_static();
      return $containing_block;
    default:
      die(sprintf('Unexpected position enum value: %d', $position));
    };
  }

  function &_get_containing_block_fixed() {
    $media = $GLOBALS['g_media'];

    $containing_block = array();
    $containing_block['left']   = mm2pt($media->margins['left']);
    $containing_block['right']  = mm2pt($media->margins['left'] + $media->real_width());
    $containing_block['top']    = mm2pt($media->margins['bottom'] + $media->real_height());
    $containing_block['bottom'] = mm2pt($media->margins['bottom']);

    return $containing_block;    
  }

  // Get the position and size of containing block for current 
  // ABSOLUTE POSITIONED element. It is assumed that this function
  // is called for ABSOLUTE positioned boxes ONLY
  //
  // @return associative array with 'top', 'bottom', 'right' and 'left' 
  // indices in data space describing the position of containing block
  //
  function &_get_containing_block_absolute() {
    $parent =& $this->parent;

    // No containing block at all... 
    // How could we get here?
    if (is_null($parent)) { 
      trigger_error("No containing block found for absolute-positioned element",
                    E_USER_ERROR);
    };

    // CSS 2.1:
    // If the element has 'position: absolute', the containing block is established by the 
    // nearest ancestor with a 'position' of 'absolute', 'relative' or 'fixed', in the following way:
    // - In the case that the ancestor is inline-level, the containing block depends on 
    //   the 'direction' property of the ancestor:
    //   1. If the 'direction' is 'ltr', the top and left of the containing block are the top and left 
    //      content edges of the first box generated by the ancestor, and the bottom and right are the 
    //      bottom and right content edges of the last box of the ancestor.
    //   2. If the 'direction' is 'rtl', the top and right are the top and right edges of the first
    //      box generated by the ancestor, and the bottom and left are the bottom and left content 
    //      edges of the last box of the ancestor.
    // - Otherwise, the containing block is formed by the padding edge of the ancestor.
    // TODO: inline-level ancestors
    while ((!is_null($parent->parent)) && 
           ($parent->getCSSProperty(CSS_POSITION) === POSITION_STATIC)) { 
      $parent =& $parent->parent; 
    }

    // Note that initial containg block (containig BODY element) will be formed by BODY margin edge,
    // unlike other blocks which are formed by padding edges
    
    if ($parent->parent) {
      // Normal containing block
      $containing_block = array();
      $containing_block['left']   = $parent->get_left_padding();
      $containing_block['right']  = $parent->get_right_padding();
      $containing_block['top']    = $parent->get_top_padding();
      $containing_block['bottom'] = $parent->get_bottom_padding();
    } else {
      // Initial containing block 
      $containing_block = array();
      $containing_block['left']   = $parent->get_left_margin();
      $containing_block['right']  = $parent->get_right_margin();
      $containing_block['top']    = $parent->get_top_margin();
      $containing_block['bottom'] = $parent->get_bottom_margin();
    };

    return $containing_block;
  }

  function &_get_containing_block_static() {
    $parent =& $this->parent;
    
    // No containing block at all... 
    // How could we get here?

    if (is_null($parent)) { 
      die("No containing block found for static-positioned element"); 
    };
    
    while (!is_null($parent->parent) && 
           !$parent->isBlockLevel() && 
           !$parent->isCell()) { 
      $parent =& $parent->parent; 
    };

    // Note that initial containg block (containing BODY element) 
    // will be formed by BODY margin edge,
    // unlike other blocks which are formed by content edges
    
    $containing_block = array();
    $containing_block['left']   = $parent->get_left();
    $containing_block['right']  = $parent->get_right();
    $containing_block['top']    = $parent->get_top();
    $containing_block['bottom'] = $parent->get_bottom();

    return $containing_block;
  }

  // Height constraint 
  function get_height_constraint() {
    return $this->_height_constraint;
  }
  
  function put_height_constraint(&$wc) {
    $this->_height_constraint = $wc;
  }

  // Extends the box height to cover the given Y coordinate
  // If box height is already big enough, no changes will be made
  //
  // @param $y_coord Y coordinate should be covered by the box
  //
  function extend_height($y_coord) {
    $this->put_height(max($this->get_height(), $this->get_top() - $y_coord));
  }

  function extend_width($x_coord) {
    $this->put_width(max($this->get_width(), $x_coord - $this->get_left()));
  }

  function get_extra_bottom() {
    $border = $this->getCSSProperty(CSS_BORDER);
    return 
      $this->get_margin_bottom() + 
      $border->bottom->get_width() + 
      $this->get_padding_bottom();
  }

  function get_extra_left() {
    $border = $this->getCSSProperty(CSS_BORDER);

    $left_border = $border->left;

    return 
      $this->get_margin_left() + 
      $left_border->get_width() + 
      $this->get_padding_left();
  }

  function get_extra_right() {
    $border = $this->getCSSProperty(CSS_BORDER);
    $right_border = $border->right;
    return 
      $this->get_margin_right() + 
      $right_border->get_width() + 
      $this->get_padding_right();
  }

  function get_extra_top() {
    $border = $this->getCSSProperty(CSS_BORDER);
    return 
      $this->get_margin_top() + 
      $border->top->get_width() + 
      $this->get_padding_top();
  }

  function get_extra_line_left() { return 0; }
  function get_extra_line_right() { return 0; }

  function get_margin_bottom() { 
    $margin = $this->getCSSProperty(CSS_MARGIN);
    return $margin->bottom->value; 
  }

  function get_margin_left() { 
    $margin = $this->getCSSProperty(CSS_MARGIN);
    return $margin->left->value; 
  }
  
  function get_margin_right() { 
    $margin = $this->getCSSProperty(CSS_MARGIN);
    return $margin->right->value; 
  }

  function get_margin_top() { 
    $margin = $this->getCSSProperty(CSS_MARGIN);
    return $margin->top->value; 
  }

  function get_padding_right() { 
    $padding = $this->getCSSProperty(CSS_PADDING);
    return $padding->right->value; 
  }

  function get_padding_left() { 
    $padding = $this->getCSSProperty(CSS_PADDING);
    return $padding->left->value; 
  }

  function get_padding_top() { 
    $padding = $this->getCSSProperty(CSS_PADDING);
    return $padding->top->value; 
  }

  function get_border_top_width() { 
    return $this->border->top->width; 
  }

  function get_padding_bottom() { 
    $padding = $this->getCSSProperty(CSS_PADDING);
    return $padding->bottom->value; 
  }

  function get_left_border() { 
    $padding = $this->getCSSProperty(CSS_PADDING);
    $border  = $this->getCSSProperty(CSS_BORDER);

    return 
      $this->get_left() - 
      $padding->left->value - 
      $border->left->get_width(); 
  }

  function get_right_border() { 
    $padding = $this->getCSSProperty(CSS_PADDING);
    $border  = $this->getCSSProperty(CSS_BORDER);

    return 
      $this->get_left() + 
      $this->get_width() + 
      $padding->right->value + 
      $border->right->get_width(); 
  }

  function get_top_border()     { 
    $border = $this->getCSSProperty(CSS_BORDER);

    return 
      $this->get_top_padding() + 
      $border->top->get_width();  
  }

  function get_bottom_border()  { 
    $border = $this->getCSSProperty(CSS_BORDER);
    return 
      $this->get_bottom_padding()  - 
      $border->bottom->get_width();  
  }

  function get_left_padding() { 
    $padding = $this->getCSSProperty(CSS_PADDING);
    return $this->get_left() - $padding->left->value; 
  }

  function get_right_padding() { 
    $padding = $this->getCSSProperty(CSS_PADDING);
    return $this->get_left() + $this->get_width() + $padding->right->value; 
  }

  function get_top_padding()    { 
    $padding = $this->getCSSProperty(CSS_PADDING);

    return 
      $this->get_top() + 
      $padding->top->value; 
  }

  function get_bottom_padding() { 
    $padding = $this->getCSSProperty(CSS_PADDING);
    return $this->get_bottom() - $padding->bottom->value;
  }

  function get_left_margin()    { 
    return 
      $this->get_left() - 
      $this->get_extra_left();
  }

  function get_right_margin()   { 
    return 
      $this->get_right() + 
      $this->get_extra_right();
  }

  function get_bottom_margin()  { 
    return 
      $this->get_bottom() - 
      $this->get_extra_bottom();
  }

  function get_top_margin() { 
    $margin = $this->getCSSProperty(CSS_MARGIN);

    return 
      $this->get_top_border() + 
      $margin->top->value; 
  }

  // Geometry
  function contains_point_margin($x, $y) {
    // Actually, we treat a small area around the float as "inside" float;
    // it will help us to prevent incorrectly positioning float due the rounding errors
    $eps = 0.1;
    return 
      $this->get_left_margin()-$eps   <= $x &&
      $this->get_right_margin()+$eps  >= $x &&
      $this->get_top_margin()+$eps    >= $y &&
      $this->get_bottom_margin()      <  $y;
  }

  function get_width() {
    $wc = $this->getCSSProperty(CSS_WIDTH);

    if ($this->parent) {
      return $wc->apply($this->width, $this->parent->width);
    } else {
      return $wc->apply($this->width, $this->width);
    }
  }
  
  // Unlike real/constrained width, or min/max width,
  // expandable width shows the size current box CAN be expanded;
  // it is pretty obvious that width-constrained boxes will never be expanded;
  // any other box can be expanded up to its parent _expandable_ width - 
  // as parent can be expanded too. 
  //
  function get_expandable_width() {
    $wc = $this->getCSSProperty(CSS_WIDTH);
    if ($wc->isNull() && $this->parent) {
      return $this->parent->get_expandable_width();
    } else {
      return $this->get_width();
    };
  }

  function put_width($value) {
    // TODO: constraints
    $this->width = $value;
  }

  function get_height() {
    if ($this->_height_constraint->applicable($this)) {
      return $this->_height_constraint->apply($this->height, $this);
    } else {
      return $this->height;
    };
  }

  function get_height_padded() {
    return $this->get_height() + $this->get_padding_top() + $this->get_padding_bottom();
  }

  function put_height($value) {
    if ($this->_height_constraint->applicable($this)) {
      $this->height = $this->_height_constraint->apply($value, $this);
    } else {
      $this->height = $value;
    };
  }

  function put_full_height($value) {
    $this->put_height($value - $this->_get_vert_extra());
  }

  // Returns total height of current element: 
  // top padding + top margin + content + bottom padding + bottom margin + top border + bottom border
  function get_full_height() {
    return $this->get_height() + 
      $this->get_extra_top() +
      $this->get_extra_bottom();
  }

  function get_real_full_height() { 
    return $this->get_full_height(); 
  }

  function out_of_flow() {
    $position = $this->getCSSProperty(CSS_POSITION);
    $display  = $this->getCSSProperty(CSS_DISPLAY);

    return 
      $position == POSITION_ABSOLUTE ||
      $position == POSITION_FIXED ||
      $display == 'none';
  }

  function moveto($x, $y) { $this->offset($x - $this->get_left(), $y - $this->get_top()); }

  function show(&$viewport) {
    $border     = $this->getCSSProperty(CSS_BORDER);
    $background = $this->getCSSProperty(CSS_BACKGROUND);

    // Draw border of the box
    $border->show($viewport, $this);

    // Render background of the box
    $background->show($viewport, $this);

    parent::show($viewport);

    return true;
  }

  function show_fixed(&$viewport) {
    return $this->show($viewport);
  }

  function is_null() { 
    return false; 
  }

  function line_break_allowed() { 
    $white_space = $this->getCSSProperty(CSS_WHITE_SPACE);
    $nowrap      = $this->getCSSProperty(CSS_HTML2PS_NOWRAP);

    return 
      ($white_space === WHITESPACE_NORMAL || 
       $white_space === WHITESPACE_PRE_WRAP || 
       $white_space === WHITESPACE_PRE_LINE) && 
      $nowrap === NOWRAP_NORMAL;
  }

  function get_left_background()   { return $this->get_left_padding();   }
  function get_right_background()  { return $this->get_right_padding();  }
  function get_top_background()    { return $this->get_top_padding();    }
  function get_bottom_background() { return $this->get_bottom_padding(); }

  function isVisibleInFlow() { 
    $visibility = $this->getCSSProperty(CSS_VISIBILITY);
    $position   = $this->getCSSProperty(CSS_POSITION);

    return 
      $visibility === VISIBILITY_VISIBLE &&
      $position !== POSITION_FIXED;
  }

  function reflow_footnote(&$parent, &$context) {
    $this->reflow_static($parent, $context);
  }

  /**
   * The  'top'  and 'bottom'  properties  move relatively  positioned
   * element(s) up  or down without  changing their size.  'top' moves
   * the boxes down,  and 'bottom' moves them up.  Since boxes are not
   * split or stretched as a result of 'top' or 'bottom', the computed
   * values  are always:  top =  -bottom.  If both  are 'auto',  their
   * computed  values are  both  '0'. If  one  of them  is 'auto',  it
   * becomes the negative of the other. If neither is 'auto', 'bottom'
   * is ignored  (i.e., the computed  value of 'bottom' will  be minus
   * the value of 'top').
   */
  function offsetRelative() {
    /**
     * Note  that  percentage   positioning  values  are  ignored  for
     * relative positioning
     */

    /**
     * Check if 'top' value is percentage
     */
    $top = $this->getCSSProperty(CSS_TOP);
    if ($top->isNormal()) {
      $top_value = $top->getPoints();
    } elseif ($top->isPercentage()) {
      $containing_block = $this->_get_containing_block();
      $containing_block_height = $containing_block['top'] - $containing_block['bottom'];
      $top_value = $containing_block_height * $top->getPercentage() / 100;
    } elseif ($top->isAuto()) {
      $top_value = null;
    }

    /**
     * Check if 'bottom' value is percentage
     */
    $bottom = $this->getCSSProperty(CSS_BOTTOM);
    if ($bottom->isNormal()) {
      $bottom_value = $bottom->getPoints();
    } elseif ($bottom->isPercentage()) {
      $containing_block = $this->_get_containing_block();
      $containing_block_height = $containing_block['top'] - $containing_block['bottom'];
      $bottom_value = $containing_block_height * $bottom->getPercentage() / 100;
    } elseif ($bottom->isAuto()) {
      $bottom_value = null;
    }

    /**
     * Calculate vertical offset for relative positioned box
     */
    if (!is_null($top_value)) {
      $vertical_offset = -$top_value;
    } elseif (!is_null($bottom_value)) {
      $vertical_offset = $bottom_value;
    } else {
      $vertical_offset = 0;
    };

    /**
     * Check if 'left' value is percentage
     */
    $left = $this->getCSSProperty(CSS_LEFT);
    if ($left->isNormal()) {
      $left_value = $left->getPoints();
    } elseif ($left->isPercentage()) {
      $containing_block = $this->_get_containing_block();
      $containing_block_width = $containing_block['right'] - $containing_block['left'];
      $left_value = $containing_block_width * $left->getPercentage() / 100;
    } elseif ($left->isAuto()) {
      $left_value = null;
    }

    /**
     * Check if 'right' value is percentage
     */
    $right = $this->getCSSProperty(CSS_RIGHT);
    if ($right->isNormal()) {
      $right_value = $right->getPoints();
    } elseif ($right->isPercentage()) {
      $containing_block = $this->_get_containing_block();
      $containing_block_width = $containing_block['right'] - $containing_block['left'];
      $right_value = $containing_block_width * $right->getPercentage() / 100;
    } elseif ($right->isAuto()) {
      $right_value = null;
    }

    /**
     * Calculate vertical offset for relative positioned box
     */
    if (!is_null($left_value)) {
      $horizontal_offset = $left_value;
    } elseif (!is_null($right_value)) {
      $horizontal_offset = -$right_value;
    } else {
      $horizontal_offset = 0;
    };

    $this->offset($horizontal_offset, 
                  $vertical_offset);
  }
}
?>