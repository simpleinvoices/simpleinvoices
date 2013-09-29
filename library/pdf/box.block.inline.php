<?php
// $Header: /cvsroot/html2ps/box.block.inline.php,v 1.21 2007/04/07 11:16:33 Konstantin Exp $

/**
 * @package HTML2PS
 * @subpackage Document
 *
 * Describes document elements with 'display: inline-block'. 
 *
 * @link http://www.w3.org/TR/CSS21/visuren.html#value-def-inline-block CSS 2.1 description of 'display: inline-block'
 */
class InlineBlockBox extends GenericContainerBox {
  /** 
   * Create new 'inline-block' element; add content from the parsed HTML tree automatically.
   *
   * @see InlineBlockBox::InlineBlockBox()
   * @see GenericContainerBox::create_content()
   */
  function &create(&$root, &$pipeline) {
    $box = new InlineBlockBox();
    $box->readCSS($pipeline->getCurrentCSSState());
    $box->create_content($root, $pipeline);
    return $box;
  }

  function InlineBlockBox() {
    $this->GenericContainerBox();
  }

  /**
   * Layout current inline-block element 
   *
   * @param GenericContainerBox $parent The document element which should be treated as the parent of current element
   * @param FlowContext $context The flow context containing the additional layout data
   * 
   * @see FlowContext
   * @see GenericContainerBox
   * @see BlockBox::reflow
   * 
   * @todo this 'reflow' skeleton is common for all element types; thus, we probably should move the generic 'reflow' 
   * definition to the GenericFormattedBox class, leaving only box-specific 'reflow_static' definitions in specific classes.
   *
   * @todo make relative positioning more CSS 2.1 compliant; currently, 'bottom' and 'right' CSS properties are ignored.
   *
   * @todo check whether percentage values should be really ignored during relative positioning
   */
  function reflow(&$parent, &$context) {
    /**
     * Note that we may not worry about 'position: absolute' and 'position: fixed', 
     * as, according to CSS 2.1 paragraph 9.7, these values of 'position' 
     * will cause 'display' value to change to either 'block' or 'table'. Thus,
     * 'inline-block' boxes will never have 'position' value other than 'static' or 'relative'
     *
     * @link http://www.w3.org/TR/CSS21/visuren.html#dis-pos-flo CSS 2.1: Relationships between 'display', 'position', and 'float'
     */

    switch ($this->getCSSProperty(CSS_POSITION)) {
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
      $this->offsetRelative();

      return;
    }
  }

  /**
   * Layout current 'inline-block' element assument it has 'position: static'
   *
   * @param GenericContainerBox $parent The document element which should 
   * be treated as the parent of current element
   *
   * @param FlowContext $context The flow context containing the additional layout data
   * 
   * @see FlowContext
   * @see GenericContainerBox
   *
   * @todo re-check this layout routine; it seems that 'inline-block' boxes have 
   * their width calculated incorrectly
   */
  function reflow_static(&$parent, &$context) {
    GenericFormattedBox::reflow($parent, $context);

    /**
     * Calculate margin values if they have been set as a percentage
     */
    $this->_calc_percentage_margins($parent);
    $this->_calc_percentage_padding($parent);

    /**
     * Calculate width value if it had been set as a percentage
     */
    $this->_calc_percentage_width($parent, $context);
    
    /**
     * Calculate 'auto' values of width and margins
     */
    $this->_calc_auto_width_margins($parent); 

    /**
     * add current box to the parent's line-box (alone)
     */
    $parent->append_line($this);

    /**
     * Calculate position of the upper-left corner of the current box
     */
    $this->guess_corner($parent);

    /**
     * By default, child block box will fill all available parent width;
     * note that actual content width will be smaller because of non-zero padding, border and margins
     */
    $this->put_full_width($parent->get_width());

    /**
     * Layout element's children 
     */
    $this->reflow_content($context);

    /**
     * Calculate element's baseline, as it should be aligned inside the 
     * parent's line box vertically
     */
    $font = $this->getCSSProperty(CSS_FONT);
    $this->default_baseline = $this->get_height() + $font->size->getPoints();
    
    /**
     * Extend parent's height to fit current box
     */
    $parent->extend_height($this->get_bottom_margin());

    /**
     * Offset current x coordinate of parent box 
     */
    $parent->_current_x = $this->get_right_margin();
  }
}
?>