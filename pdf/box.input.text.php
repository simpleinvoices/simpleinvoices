<?php
// $Header: /cvsroot/html2ps/box.input.text.php,v 1.23 2006/04/12 15:17:21 Konstantin Exp $

/// define('SIZE_SPACE_KOEFF',1.65); (defined in tag.input.inc.php)

class TextInputBox extends InlineControlBox {
  /**
   * @var String contains the default value of this text field
   * @access private
   */
  var $_value;

  function &create(&$root, &$pipeline) {
    // Control size
    $size = (int)$root->get_attribute("size"); 
    if (!$size) { $size = DEFAULT_TEXT_SIZE; };

    // Text to be displayed
    if ($root->has_attribute('value')) {
      $text = str_pad($root->get_attribute("value"), $size, " ");
    } else {
      $text = str_repeat(" ",$size*SIZE_SPACE_KOEFF);
    };

    /**
     * Input field name
     */
    $name = $root->get_attribute('name');

    $box =& new TextInputBox($size, $text, $root->get_attribute("value"), $name);
    return $box;
  }

  function TextInputBox($size, $text, $value, $name) {
    // Call parent constructor
    $this->InlineBox();

    $this->_value = $value;
    $this->_field_name = $name;
    
    /**
     * Contents of the text box are somewhat similar to the inline box: 
     * a sequence of the text and whitespace boxes; we generate this sequence using
     * the InlineBox, then copy contents of the created inline box to our button.
     *
     * @todo probably, create_from_text() function should be extracted to the common parent 
     * of inline boxes.
     */
    $ibox = InlineBox::create_from_text($text, WHITESPACE_PRE);

    $size = count($ibox->content);
    for ($i=0; $i<$size; $i++) {
      $this->add_child($ibox->content[$i]);
    };
  }

  function show(&$driver) {   
    // Now set the baseline of a button box to align it vertically when flowing isude the 
    // text line

    $this->default_baseline = $this->content[0]->baseline + $this->get_extra_top();
    $this->baseline         = $this->content[0]->baseline + $this->get_extra_top();

    /**
     * If we're rendering the interactive form, the field content should not be rendered
     */
    global $g_config;
    if ($g_config['renderforms']) {
      /**
       * Render background/borders only
       */
      $status = GenericFormattedBox::show($driver);

      /**
       * @todo encoding name?
       * @todo font name?
       * @todo check if font is embedded for PDFLIB
       */
      $driver->field_text($this->get_left_padding(), 
                          $this->get_top_padding(),
                          $this->get_width()  + $this->get_padding_left() + $this->get_padding_right(),
                          $this->get_height() + $this->get_padding_top()  + $this->get_padding_bottom(),
                          $this->_value,
                          $this->_field_name);
    } else {
      /**
       * Render everything, including content
       */ 
      $status = GenericContainerBox::show($driver);
    }

    return $status;
  }
}
?>