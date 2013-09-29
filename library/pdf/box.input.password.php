<?php
// $Header: /cvsroot/html2ps/box.input.password.php,v 1.6 2006/10/06 20:10:52 Konstantin Exp $

class PasswordInputBox extends TextInputBox {
  function &create(&$root, &$pipeline) {
    // Text to be displayed
    if ($root->has_attribute('value')) {
      $text = str_repeat("*",strlen($root->get_attribute("value")));
    } else {
      $text = "";
    };

    /**
     * Input field name
     */
    $name = $root->get_attribute('name');

    $box =& new PasswordInputBox($text, $root->get_attribute("value"), $name);
    $box->readCSS($pipeline->getCurrentCSSState());

    $ibox = InlineBox::create_from_text(" ", WHITESPACE_PRE, $pipeline);
    for ($i=0, $size = count($ibox->content); $i<$size; $i++) {
      $box->add_child($ibox->content[$i]);
    };

    return $box;
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
      $driver->field_password($this->get_left_padding(), 
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