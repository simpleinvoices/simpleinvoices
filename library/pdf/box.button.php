<?php
// $Header: /cvsroot/html2ps/box.button.php,v 1.29 2007/01/24 18:55:43 Konstantin Exp $
/**
 * @package HTML2PS
 * @subpackage Document
 *
 * This file contains the class desribing layout and behavior of 'input type="button"' 
 * elements
 */

/**
 * @package HTML2PS
 * @subpackage Document
 * 
 * The ButtonBox class desribes the HTML buttons layout. (Note that 
 * button elements have 'display' CSS property set to HTML2PS-specific
 * '-button' value )
 *
 * @link http://www.w3.org/TR/html4/interact/forms.html#h-17.4 HTML 4.01 The INPUT element
 */
class ButtonBox extends InlineControlBox {
  function ButtonBox() {
    $this->InlineControlBox();
  }

  function get_max_width(&$context, $limit = 10E6) { 
    return 
      GenericContainerBox::get_max_width($context, $limit);
  }

  /**
   * Create a new button element from the DOM tree element
   *
   * @param DOMElement $root pointer to the DOM tree element corresponding to the button.
   * 
   * @return ButtonBox new button element
   */
  function &create(&$root, &$pipeline) {
    /**
     * Button text is defined by its 'value' attrubute;
     * if this attribute is not specified, we should provide some 
     * appropriate defaults depending on the exact button type: 
     * reset, submit or generic button.
     *
     * Default button text values are specified in config file config.inc.php.
     *
     * @see config.inc.php
     * @see DEFAULT_SUBMIT_TEXT
     * @see DEFAULT_RESET_TEXT
     * @see DEFAULT_BUTTON_TEXT
     */
    if ($root->has_attribute("value")) {
      $text = $root->get_attribute("value");
    } else {
      $text = DEFAULT_BUTTON_TEXT;
    };

    $box =& new ButtonBox();
    $box->readCSS($pipeline->getCurrentCSSState());

    /**
     * If button width is not constrained, then we'll add some space around the button text
     */
    $text = " ".$text." ";

    $box->_setup($text, $pipeline);

    return $box;
  }

  function _setup($text, &$pipeline) {
    $this->setup_content($text, $pipeline);

    /**
     * Button height includes vertical padding (e.g. the following two buttons 
     * <input type="button" value="test" style="padding: 10px; height: 50px;"/>
     * <input type="button" value="test" style="padding: 0px; height: 30px;"/>
     * are render by browsers with the same height!), so we'll need to adjust the 
     * height constraint, subtracting the vertical padding value from the constraint 
     * height value.
     */
    $hc = $this->get_height_constraint();
    if (!is_null($hc->constant)) {
      $hc->constant[0] -= $this->get_padding_top() + $this->get_padding_bottom();
    };
    $this->put_height_constraint($hc);
  }

  /**
   * Render the form field corresponding to this button
   * (Will be overridden by subclasses; they may render more specific button types)
   *
   * @param OutputDriver $driver The output driver object
   */
  function _render_field(&$driver) {
    $driver->field_pushbutton($this->get_left_padding(), 
                              $this->get_top_padding(), 
                              $this->get_width() + $this->get_padding_left() + $this->get_padding_right(), 
                              $this->get_height() + $this->get_padding_top() + $this->get_padding_bottom());
  }

  /**
   * Render the button using the specified output driver
   * 
   * @param OutputDriver $driver The output driver object
   * 
   * @return boolean flag indicating an error (null value) or success (true)
   */
  function show(&$driver) {   
    /**
     * Set the baseline of a button box so that the button text will be aligned with 
     * the line box baseline
     */
    $this->default_baseline = $this->content[0]->baseline + $this->get_extra_top();
    $this->baseline         = $this->content[0]->baseline + $this->get_extra_top();


    /**
     * Render the interactive button (if requested and possible)
     */
    if ($GLOBALS['g_config']['renderforms']) {
      $status = GenericContainerBox::show($driver);
      $this->_render_field($driver);
    } else {
      $status = GenericContainerBox::show($driver);
    };

    return $status;
  }
}
?>