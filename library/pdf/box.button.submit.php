<?php

/**
 * Handles INPUT type="submit" boxes generation.
 */
class ButtonSubmitBox extends ButtonBox {
  /**
   * @var String URL to post the form to; may be null if this is not a 'submit' button
   * @access private
   */
  var $_action_url;

  /**
   * Note: required for interative forms only
   *
   * @var String textual name of the input field 
   * @access private
   */
  var $_field_name;

  /**
   * Note: required for interactive forms only
   *
   * @var String button name to display
   * @access private
   */
  var $_value;

  /**
   * Constructs new (possibly interactive) button box
   *
   * @param String $text text to display
   * @param String $field field name (interactive forms)
   * @param String $value field value (interactive forms)
   */
  function ButtonSubmitBox($field, $value, $action) {
    $this->ButtonBox();
    $this->_action_url = $action;
    $this->_field_name = $field;
    $this->_value = $value;
  }

  /**
   * Create input box using DOM tree data
   *
   * @param Object $root DOM tree node corresponding to the box being created
   * @param Pipeline $pipeline reference to current pipeline object (unused)
   *
   * @return input box
   */
  function &create(&$root, &$pipeline) {
    /**
     * If no "value" attribute is specified, display the default button text.
     * Note the difference between displayed text and actual field value!
     */
    if ($root->has_attribute("value")) {
      $text = $root->get_attribute("value");
    } else {
      $text = DEFAULT_SUBMIT_TEXT;
    };

    $field = $root->get_attribute('name');
    $value = $root->get_attribute('value');
    
    $css_state =& $pipeline->getCurrentCSSState();
    $box =& new ButtonSubmitBox($field, $value, $css_state->getProperty(CSS_HTML2PS_FORM_ACTION));
    $box->readCSS($css_state);
    $box->_setup($text, $pipeline);

    return $box;
  }

  /**
   * Render interactive field using the driver-specific capabilities;
   * button is rendered as a rectangle defined by margin and padding areas (note that unlike most other boxes,
   * borders are _outside_ the box, so we may treat 
   *
   * @param OutputDriver $driver reference to current output driver object
   */
  function _render_field(&$driver) {
    $driver->field_pushbuttonsubmit($this->get_left_padding() - $this->get_margin_left(), 
                                    $this->get_top_padding() + $this->get_margin_top(), 
                                    $this->get_width() + $this->get_padding_left() + $this->get_padding_right() + $this->get_margin_left() + $this->get_margin_right(), 
                                    $this->get_height() + $this->get_padding_top() + $this->get_padding_bottom() + $this->get_margin_top() + $this->get_margin_bottom(),
                                    $this->_field_name,
                                    $this->_value,
                                    $this->_action_url);
  }
}

?>