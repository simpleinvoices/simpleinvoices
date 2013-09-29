<?php

require_once(HTML2PS_DIR.'box.generic.inline.php');

/**
 * @TODO: constructor required font properties to be known for the
 * "content" element; on the other side, "content" element may be one
 * without font properties defined/used. Currently, it is solved by
 * adding CSS_FONT and CSS_LETTER_SPACING to GenericFormattedBox::readCSS
 */
class BoxNoteCall extends GenericInlineBox {
  var $_note_number;
  var $_note_content;
  var $_note_marker_box;
  var $_note_call_box;

  function offset($dx, $dy) {
    parent::offset($dx, $dy);
    $this->_note_call_box->offset($dx, $dy);
  }

  function BoxNoteCall(&$content, &$pipeline) {
    $this->GenericInlineBox();

    $this->_note_content =& $content;

    $this->copy_style($content);
    $this->put_height_constraint(new HCConstraint(null, null, null));

    /**
     * Prepare ::note-call box
     */

    $this->_note_call_box = InlineBox::create_from_text(CSSListStyleType::format_number(LST_DECIMAL, 99), 
                                                        WHITESPACE_NORMAL, 
                                                        $pipeline);

    $this->_note_call_box->copy_style($content);
    $this->_note_call_box->content[0]->copy_style($content);

    $font = $this->_note_call_box->content[0]->getCSSProperty(CSS_FONT);
    $font = $font->copy();
    $font->size->scale(0.75);
    $this->_note_call_box->content[0]->setCSSProperty(CSS_FONT, $font);

    $this->_note_call_box->content[0]->setCSSProperty(CSS_VERTICAL_ALIGN, VA_SUPER);
    $this->_note_call_box->content[0]->setCSSProperty(CSS_LINE_HEIGHT, CSS::getDefaultValue(CSS_LINE_HEIGHT));

    /**
     * Prepare ::marker box
     */

    $this->_note_marker_box = InlineBox::create_from_text(CSSListStyleType::format_number(LST_DECIMAL, 99), 
                                                          WHITESPACE_NORMAL,
                                                          $pipeline);

    $this->_note_marker_box->copy_style($content);
    $this->_note_marker_box->content[0]->copy_style($content);

    $font = $this->_note_marker_box->content[0]->getCSSProperty(CSS_FONT);
    $font = $font->copy();
    $font->size->scale(0.5);
    $this->_note_marker_box->content[0]->setCSSProperty(CSS_FONT, $font);

    $margin = $this->_note_marker_box->content[0]->getCSSProperty(CSS_MARGIN);
    $margin = $margin->copy();
    $margin->right = Value::fromData(FOOTNOTE_MARKER_MARGIN, UNIT_PT);
    $this->_note_marker_box->content[0]->setCSSProperty(CSS_MARGIN, $margin);


    $this->_note_marker_box->content[0]->setCSSProperty(CSS_VERTICAL_ALIGN, VA_SUPER);
    $this->_note_marker_box->content[0]->setCSSProperty(CSS_LINE_HEIGHT, CSS::getDefaultValue(CSS_LINE_HEIGHT));
  }

  function &create(&$content, &$pipeline) {
    $box = new BoxNoteCall($content, $pipeline);

    return $box;
  }

  function reflow(&$parent, &$context) {
    $parent->append_line($this->_note_call_box);

    $body = $parent;        
    while ($body->parent) { 
      $body = $body->parent;
    };                      
    
    /**
     * Reflow note content
     */
    $this->put_full_height(1000);
    $this->put_full_width($body->get_width());

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
    $this->_note_content->reflow($this, $context);

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
    $this->_note_marker_box->reflow($this, $context);

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
    $this->_note_call_box->reflow($this, $context);
    // This prevents note-call box from affecting line height
    $this->_note_call_box->put_full_height(0);

    /**
     * Reflow note-call itself
     */
    $this->put_full_height(0);
    $this->put_full_width(0);
    $this->guess_corner($parent);
    $parent->_current_x += $this->_note_call_box->content[0]->get_width();
    $this->_note_call_box->put_full_width($this->_note_call_box->content[0]->get_width());

    $this->_note_call_box->moveto($this->get_left(), $this->get_top());

//     $last =& $parent->last_in_line();
//     $last->note_call = true;

    return true;
  }

  function reflow_whitespace(&$linebox_started, &$previous_whitespace) {
    $ls = false;
    $pw = false;
    $this->_note_content->reflow_whitespace($ls, $pw);
  }

  function reflow_text(&$driver) {
    $this->_note_content->reflow_text($driver);
    $this->_note_marker_box->reflow_text($driver);
    $this->_note_call_box->reflow_text($driver);
    return true;
  }

  function _getFootnoteHeight(&$driver) {
    if ($driver->getFootnoteCount() == 0) {
      $footnote_height = 
        $this->_note_content->get_full_height() + 
        FOOTNOTE_LINE_TOP_GAP +
        FOOTNOTE_LINE_BOTTOM_GAP;
    } else {
      $footnote_height = 
        $this->_note_content->get_full_height() + 
        FOOTNOTE_GAP;
    };

    return $footnote_height;
  }

  function show(&$driver) {
    $footnote_height = $this->_getFootnoteHeight($driver);
    if (!$driver->willContain($this, $footnote_height)) {
      return true;
    };

    $driver->setFootnoteAreaHeight($driver->getFootnoteAreaHeight() + $footnote_height);
    $driver->setFootnoteCount($driver->getFootnoteCount() + 1);

    /**
     * Prepare box containing note number
     */
    $this->_note_number = $driver->getFootnoteCount();

    /**
     * Render reference number
     */
    $this->_note_call_box->content[0]->words[0] = CSSListStyleType::format_number(LST_DECIMAL, 
                                                                                  $this->_note_number);
    $this->_note_call_box->show_fixed($driver);

    return true;
  }

  function show_footnote(&$driver, $x, $y) {
    /**
     * Render note reference number
     */
    $this->_note_marker_box->content[0]->words[0] = CSSListStyleType::format_number(LST_DECIMAL, 
                                                                                    $this->_note_number);
    $this->_note_marker_box->moveto($x, $y);
    $this->_note_marker_box->show_fixed($driver);

    /**
     * Render note content
     */
    $this->_note_content->moveto($x + $this->_note_marker_box->content[0]->get_width()*0.75, 
                                 $y);
    $this->_note_content->show_fixed($driver);


    return $y - $this->_note_content->get_full_height();
  }
}

?>