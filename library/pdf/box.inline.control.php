<?php
// $Header: /cvsroot/html2ps/box.inline.control.php,v 1.7 2006/09/07 18:38:12 Konstantin Exp $

class InlineControlBox extends InlineBox {
  function InlineControlBox() {
    $this->InlineBox();
  }

  function get_min_width(&$context, $limit = 10E6) { 
    return $this->get_max_width($context, $limit);
  }

  function get_max_width(&$context, $limit = 10E6) { 
    return 
      GenericContainerBox::get_max_width($context, $limit) - 
      $this->_get_hor_extra(); 
  }

  function line_break_allowed() { 
    return false; 
  }
  
  function reflow_static(&$parent, &$context) {  
    GenericFormattedBox::reflow($parent, $context);

    // Determine the box width
    $this->_calc_percentage_width($parent, $context);   
    $this->put_full_width($this->get_min_width($context, $parent->get_width()));
    $this->setCSSProperty(CSS_WIDTH, new WCNone());

    // Check if we need a line break here
    $this->maybe_line_break($parent, $context);

    // append to parent line box
    $parent->append_line($this);

    // Determine coordinates of upper-left _margin_ corner
    $this->guess_corner($parent);

    $this->reflow_content($context);

    /**
     * After text content have been reflown, we may determine the baseline of the control item itself;
     *
     * As there will be some extra whitespace on the top of the control box, we must add this whitespace
     * to the calculated baseline value, so text before and after control item will be aligned 
     * with the text inside the box.
     */
    $this->default_baseline = $this->content[0]->baseline + $this->get_extra_top();
    $this->baseline         = $this->content[0]->baseline + $this->get_extra_top();

    // center the text vertically inside the control
    $text =& $this->content[0];
    $delta = ($text->get_top() - $text->get_height()/2) - ($this->get_top() - $this->get_height()/2);
    $text->offset(0,-$delta);

    // Offset parent current X coordinate
    $parent->_current_x += $this->get_full_width();

    // Extends parents height
    $parent->extend_height($this->get_bottom_margin());
  }

  function setup_content($text, &$pipeline) {
    /**
     * Contents of the text box are somewhat similar to the inline box: 
     * a sequence of the text and whitespace boxes; we generate this sequence using
     * the InlineBox, then copy contents of the created inline box to our button.
     *
     * @todo probably, create_from_text() function should be extracted to the common parent 
     * of inline boxes.
     */
    $ibox = InlineBox::create_from_text($text, WHITESPACE_PRE, $pipeline);

    if (count($ibox->content) == 0) {
      $this->append_child(TextBox::create(' ', 'iso-8859-1', $pipeline));
    } else {
      for ($i=0, $size = count($ibox->content); $i<$size; $i++) {
        $this->append_child($ibox->content[$i]);
      };
    };
  }

  function show(&$viewport) {   
    // Now set the baseline of a button box to align it vertically when flowing isude the 
    // text line
    $this->default_baseline = $this->content[0]->baseline + $this->get_extra_top();
    $this->baseline         = $this->content[0]->baseline + $this->get_extra_top();

    return GenericContainerBox::show($viewport);
  }
}
?>