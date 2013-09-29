<?php
// $Header: /cvsroot/html2ps/box.br.php,v 1.31 2006/11/11 13:43:51 Konstantin Exp $

require_once(HTML2PS_DIR.'layout.vertical.php');

/**
 * @package HTML2PS
 * @subpackage Document
 * 
 * Class defined in this file handles the layout of "BR" HTML elements
 */

/**
 * @package HTML2PS
 * @subpackage Document
 *
 * The BRBox class dessribed the behavior of the BR HTML elements
 *
 * @link http://www.w3.org/TR/html4/struct/text.html#edef-BR HTML 4.01 Forcing a line break: the BR element 
 */
class BRBox extends GenericBox {
  /**
   * Create new BR element
   */
  function BRBox() {
    $this->GenericBox();
  }

  function apply_clear($y, &$context) {
    return LayoutVertical::apply_clear($this, $y, $context);
  }

  function out_of_flow() {
    return true;
  }

  function readCSS(&$state) {
    parent::readCSS($state);

    /**
     * We treat BR as a block box; as default value of 'display' property is not 'block', we should 
     * set it up manually.
     */
    $this->setCSSProperty(CSS_DISPLAY, 'block');

    $this->_readCSS($state,
                    array(CSS_CLEAR));

    $this->_readCSSLengths($state, 
                           array(CSS_MARGIN,
                                 CSS_LINE_HEIGHT));
  }

  /**
   * Create new BR element
   * 
   * @return BRBox new BR element object
   */
  function &create(&$pipeline) {
    $box =& new BRBox();
    $box->readCSS($pipeline->getCurrentCSSState());
    return $box;
  }

  /**
   * BR tags do not take any horizontal space, so if minimal width is zero.
   *
   * @param FlowContext $context The object containing auxiliary flow data; not used here/
   *
   * @return int should always return constant zero.
   */
  function get_min_width(&$context) {
    return 0;
  }

  /**
   * BR tags do not take any horizontal space, so if maximal width is zero.
   *
   * @param FlowContext $context The object containing auxiliary flow data; not used here.
   *
   * @return int should always return constant zero.
   */
  function get_max_width(&$context) {
    return 0;
  }

  /**
   * Layout current BR element. The reflow routine is somewhat similar to the block box reflow routine.
   * As most CSS properties do not apply to BR elements, and BR element always have parent element,
   * the routine is much simpler.
   *
   * @param GenericContainerBox $parent The document element which should be treated as the parent of current element
   * @param FlowContext $context The flow context containing the additional layout data
   * 
   * @see FlowContext
   * @see GenericContainerBox
   */
  function reflow(&$parent, &$context) {  
    parent::reflow($parent, $context);

    /**
     * Apply 'clear' property; the current Y coordinate can be modified as a result of 'clear'.
     */
    $y = $this->apply_clear($parent->_current_y, $context);

    /**
     * Move current "box" to parent current coordinates. It is REQUIRED, in spite of the generated 
     * box itself have no dimensions and will never be drawn, as several other routines uses box coordinates.
     */
    $this->put_left($parent->_current_x);
    $this->put_top($y);

    /**
     * If we met a sequence of BR tags (like <BR><BR>), we'll have an only one item in the parent's
     * line box - whitespace; in this case we'll need to additionally offset current y coordinate by the font size,
     * as whitespace alone does not affect the Y-coordinate.
     */
    if ($parent->line_box_empty()) {
      /**
       * There's no elements in the parent line box at all (e.g in the following situation:
       * <div><br/> .. some text here...</div>); thus, as we're initiating
       * a new line, we need to offset current Y coordinate by the font-size value.
       */

      // Note that _current_y should be modified before 'close_line' call, as it checks for 
      // left-floating boxes, causing an issues if line bottom will be placed below
      // float while line top is above float bottom margin
      $font = $this->getCSSProperty(CSS_FONT);
      $fs = $font->size;
      $parent->_current_y = min($this->get_bottom(), 
                                $parent->_current_y - $font->line_height->apply($fs->getPoints()));

      $parent->close_line($context, true);
    } else { 
     /**
       * There's at least 1 non-whitespace element in the parent line box, we do not need to use whitespace 
       * height; the bottom of the line box is defined by the non-whitespace elements. Top of the new line
       * should be equal to that value.
       */
      $parent->close_line($context, true);
    };

    /**
     * We need to explicitly extend the parent's height, to make it contain the generated line,
     * as we don't know if it have any children _after_ this BR box. If we will not do it,
     * the following code will be rendred incorrectly:
     * <div>...some text...<br/></div>
     */
    $parent->extend_height($parent->_current_y);
  }

  /**
   * Render the BR element; as BR element is non-visual, we do nothing here.
   * 
   * @param OutputDriver $driver Current output device driver object.
   *
   * @return boolean true in case the box was successfully rendered
   */
  function show(&$driver) {
    return true;
  }

  /**
   * As BR element generated a line break, it means that a new line box will be started
   * (thus, any whitespaces immediately following the BR tag should not be rendered). 
   * To indicate this, we reset the linebox_started flag to 'false' value.
   *
   * @param boolean $linebox_started Flag indicating that a new line box have just started and it already contains 
   * some inline elements 
   * @param boolean $previous_whitespace Flag indicating that a previous inline element was an whitespace element.
   *
   * @see GenericFormattedBox::reflow_whitespace()
   */
  function reflow_whitespace(&$linebox_started, &$previous_whitespace) {
    $linebox_started = false;
  }

  function get_height() {
    return 0;
  }

  function get_width() {
    return 0;
  }

  /**
   * BRBox may be placed inside InlineBox (white-space: pre)
   */
  function get_ascender() {
    return 0;
  }

  function get_descender() {
    return 0;
  }

  function isLineBreak() {
    return true;
  }
}
?>