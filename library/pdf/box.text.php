<?php
// $Header: /cvsroot/html2ps/box.text.php,v 1.56 2007/05/07 12:15:53 Konstantin Exp $

require_once(HTML2PS_DIR.'box.inline.simple.php');

// TODO: from my POV, it wll be better to pass the font- or CSS-controlling object to the constructor
// instead of using globally visible functions in 'show'.

define('SYMBOL_NBSP', chr(160));

class TextBox extends SimpleInlineBox {
  var $words;
  var $encodings;
  var $hyphens;
  var $_widths;
  var $_word_widths;
  var $_wrappable;
  var $wrapped;

  function TextBox() {
    $this->SimpleInlineBox();

    $this->words        = array();
    $this->encodings    = array();
    $this->hyphens      = array();
    $this->_word_widths = array();
    $this->_wrappable   = array();
    $this->wrapped      = null;
    $this->_widths      = array();

    $this->font_size = 0;
    $this->ascender  = 0;
    $this->descender = 0;
    $this->width     = 0;
    $this->height    = 0;
  }

  /**
   * Check if given subword contains soft hyphens and calculate 
   */
  function _make_wrappable(&$driver, $base_width, $font_name, $font_size, $subword_index) {
    $hyphens = $this->hyphens[$subword_index];
    $wrappable = array();
    
    foreach ($hyphens as $hyphen) {
      $subword_wrappable_index = $hyphen;
      $subword_wrappable_width = $base_width + $driver->stringwidth(substr($this->words[$subword_index], 0, $subword_wrappable_index),
                                                                    $font_name,
                                                                    $this->encodings[$subword_index],
                                                                    $font_size);
      $subword_full_width = $subword_wrappable_width + $driver->stringwidth('-',
                                                                            $font_name,
                                                                            "iso-8859-1",
                                                                            $font_size);
      
      $wrappable[] = array($subword_index, $subword_wrappable_index, $subword_wrappable_width, $subword_full_width);
    };
    return $wrappable;
  }

  function get_content() {
    return join('', array_map(array($this, 'get_content_callback'), $this->words, $this->encodings));
  }

  function get_content_callback($word, $encoding) {
    $manager_encoding =& ManagerEncoding::get();
    return $manager_encoding->toUTF8($word, $encoding);
  }

  function get_height() {
    return $this->height;
  }

  function put_height($value) {
    $this->height = $value;
  }

  // Apply 'line-height' CSS property; modifies the default_baseline value 
  // (NOT baseline, as it is calculated - and is overwritten - in the close_line
  // method of container box
  //
  // Note that underline position (or 'descender' in terms of PDFLIB) - 
  // so, simple that space of text box under the baseline - is scaled too
  // when 'line-height' is applied
  //
  function _apply_line_height() {
    $height     = $this->get_height();
    $under      = $height - $this->default_baseline;

    $line_height = $this->getCSSProperty(CSS_LINE_HEIGHT);

    if ($height > 0) {
      $scale = $line_height->apply($this->ascender + $this->descender) / ($this->ascender + $this->descender);
    } else {
      $scale = 0;
    };

    // Calculate the height delta of the text box

    $delta = $height * ($scale-1);
    $this->put_height(($this->ascender + $this->descender)*$scale);
    $this->default_baseline = $this->default_baseline + $delta/2;
  }

  function _get_font_name(&$viewport, $subword_index) {
    if (isset($this->_cache[CACHE_TYPEFACE][$subword_index])) {
      return $this->_cache[CACHE_TYPEFACE][$subword_index];
    };

    $font_resolver =& $viewport->get_font_resolver();

    $font = $this->getCSSProperty(CSS_FONT);

    $typeface = $font_resolver->getTypefaceName($font->family, 
                                                $font->weight, 
                                                $font->style, 
                                                $this->encodings[$subword_index]);

    $this->_cache[CACHE_TYPEFACE][$subword_index] = $typeface;

    return $typeface;
  }

  function add_subword($raw_subword, $encoding, $hyphens) {
    $text_transform = $this->getCSSProperty(CSS_TEXT_TRANSFORM);
    switch ($text_transform) {
    case CSS_TEXT_TRANSFORM_CAPITALIZE:
      $subword = ucwords($raw_subword);
      break;
    case CSS_TEXT_TRANSFORM_UPPERCASE:
      $subword = strtoupper($raw_subword);
      break;
    case CSS_TEXT_TRANSFORM_LOWERCASE:
      $subword = strtolower($raw_subword);
      break;
    case CSS_TEXT_TRANSFORM_NONE:
      $subword = $raw_subword;
      break;
    }

    $this->words[]     = $subword;
    $this->encodings[] = $encoding;
    $this->hyphens[]   = $hyphens;
  }

  function &create($text, $encoding, &$pipeline) {
    $box =& TextBox::create_empty($pipeline);
    $box->add_subword($text, $encoding, array());
    return $box;
  }

  function &create_empty(&$pipeline) {
    $box =& new TextBox();
    $css_state = $pipeline->getCurrentCSSState();

    $box->readCSS($css_state);
    $css_state = $pipeline->getCurrentCSSState();

    return $box;
  }

  function readCSS(&$state) {
    parent::readCSS($state);

    $this->_readCSSLengths($state,
                           array(CSS_TEXT_INDENT,
                                 CSS_LETTER_SPACING));
  }

  // Inherited from GenericFormattedBox
  function get_descender() {
    return $this->descender;
  }

  function get_ascender() {
    return $this->ascender;
  }

  function get_baseline() {
    return $this->baseline;
  }

  function get_min_width_natural(&$context) {
    return $this->get_full_width();
  }

  function get_min_width(&$context) {
    return $this->get_full_width();
  }

  function get_max_width(&$context) {
    return $this->get_full_width();
  }

  // Checks if current inline box should cause a line break inside the parent box
  //
  // @param $parent reference to a parent box
  // @param $content flow context
  // @return true if line break occurred; false otherwise
  //
  function maybe_line_break(&$parent, &$context) {
    if (!$parent->line_break_allowed()) {
      return false;
    };

    $last =& $parent->last_in_line();
    if ($last) {
      // Check  if last  box was  a note  call box.  Punctuation marks
      // after  a note-call  box should  not be  wrapped to  new line,
      // while "plain" words may be wrapped.
      if ($last->is_note_call() && $this->is_punctuation()) { 
        return false; 
      };
    };

    // Calculate the x-coordinate of this box right edge 
    $right_x = $this->get_full_width() + $parent->_current_x;

    $need_break = false;

    // Check for right-floating boxes
    // If upper-right corner of this inline box is inside of some float, wrap the line
    $float = $context->point_in_floats($right_x, $parent->_current_y);
    if ($float) {
      $need_break = true;
    };

    // No floats; check if we had run out the right edge of container
    // TODO: nobr-before, nobr-after
    if (($right_x > $parent->get_right()+EPSILON)) {
      // Now check if parent line box contains any other boxes;
      // if not, we should draw this box unless we have a floating box to the left

      $first = $parent->get_first();

      $ti = $this->getCSSProperty(CSS_TEXT_INDENT);
      $indent_offset = $ti->calculate($parent);

      if ($parent->_current_x > $parent->get_left() + $indent_offset + EPSILON) {
        $need_break = true;
      };
    }

    // As close-line will not change the current-Y parent coordinate if no 
    // items were in the line box, we need to offset this explicitly in this case
    //
    if ($parent->line_box_empty() && $need_break) {
      $parent->_current_y -= $this->get_height();
    };

    if ($need_break) { 
      // Check if current box contains soft hyphens and use them, breaking word into parts
      $size = count($this->_wrappable);
      if ($size > 0) {
        $width_delta = $right_x - $parent->get_right();
        if (!is_null($float)) {
          $width_delta = $right_x - $float->get_left_margin();
        };

        $this->_find_soft_hyphen($parent, $width_delta);
      };

      $parent->close_line($context); 

      // Check if parent inline boxes have left padding/margins and add them to current_x
      $element = $this->parent;
      while (!is_null($element) && is_a($element,"GenericInlineBox")) {
        $parent->_current_x += $element->get_extra_left();
        $element = $element->parent;
      };
    };

    return $need_break;
  }

  function _find_soft_hyphen(&$parent, $width_delta) {
    /**
     * Now we search for soft hyphen closest to the right margin
     */
    $size = count($this->_wrappable);
    for ($i=$size-1; $i>=0; $i--) {
      $wrappable = $this->_wrappable[$i];
      if ($this->get_width() - $wrappable[3] > $width_delta) {
        $this->save_wrapped($wrappable, $parent, $context);
        $parent->append_line($this);
        return;
      };
    };
  }

  function save_wrapped($wrappable, &$parent, &$context) {
    $this->wrapped = array($wrappable,
                           $parent->_current_x + $this->get_extra_left(),
                           $parent->_current_y - $this->get_extra_top());
  }

  function reflow(&$parent, &$context) {    
    // Check if we need a line break here (possilble several times in a row, if we
    // have a long word and a floating box intersecting with this word
    // 
    // To prevent infinite loop, we'll use a limit of 100 sequental line feeds
    $i=0;

    do { $i++; } while ($this->maybe_line_break($parent, $context) && $i < 100);
   
    // Determine the baseline position and height of the text-box using line-height CSS property
    $this->_apply_line_height();
    
    // set default baseline
    $this->baseline = $this->default_baseline;

    // append current box to parent line box
    $parent->append_line($this);

    // Determine coordinates of upper-left _margin_ corner
    $this->guess_corner($parent);

    // Offset parent current X coordinate
    if (!is_null($this->wrapped)) {
      $parent->_current_x += $this->get_full_width() - $this->wrapped[0][2];
    } else {
      $parent->_current_x += $this->get_full_width();
    };

    // Extends parents height
    $parent->extend_height($this->get_bottom());

    // Update the value of current collapsed margin; pure text (non-span)
    // boxes always have zero margin

    $context->pop_collapsed_margin();
    $context->push_collapsed_margin( 0 );
  }

  function getWrappedWidthAndHyphen() {
    return $this->wrapped[0][3];
  }

  function getWrappedWidth() {
    return $this->wrapped[0][2];
  }

  function reflow_text(&$driver) {
    $num_words = count($this->words);

    /**
     * Empty text box
     */
    if ($num_words == 0) {
      return true;
    };

    /**
     * A simple assumption is made: fonts used for different encodings
     * have equal ascender/descender values  (while they have the same
     * typeface, style and weight).
     */
    $font_name = $this->_get_font_name($driver, 0);

    /**
     * Get font vertical metrics
     */
    $ascender  = $driver->font_ascender($font_name, $this->encodings[0]);
    if (is_null($ascender)) {
      error_log("TextBox::reflow_text: cannot get font ascender");
      return null;
    };

    $descender = $driver->font_descender($font_name, $this->encodings[0]); 
    if (is_null($descender)) {
      error_log("TextBox::reflow_text: cannot get font descender");
      return null;
    };

    /**
     * Setup box size
     */
    $font = $this->getCSSProperty(CSS_FONT_SIZE);
    $font_size = $font->getPoints();

    // Both ascender and descender should make $font_size 
    // as it is not guaranteed that $ascender + $descender == 1,
    // we should normalize the result
    $koeff = $font_size / ($ascender + $descender);
    $this->ascender         = $ascender  * $koeff;
    $this->descender        = $descender * $koeff;

    $this->default_baseline = $this->ascender; 
    $this->height           = $this->ascender + $this->descender; 

    /**
     * Determine box width
     */
    if ($font_size > 0) {
      $width = 0;

      for ($i=0; $i<$num_words; $i++) {
        $font_name = $this->_get_font_name($driver, $i);

        $current_width = $driver->stringwidth($this->words[$i], 
                                                $font_name, 
                                                $this->encodings[$i], 
                                                $font_size);
        $this->_word_widths[] = $current_width;

        // Add information about soft hyphens
        $this->_wrappable = array_merge($this->_wrappable, $this->_make_wrappable($driver, $width, $font_name, $font_size, $i));

        $width += $current_width;
      };

      $this->width = $width;
    } else {
      $this->width = 0;
    };

    $letter_spacing = $this->getCSSProperty(CSS_LETTER_SPACING);

    if ($letter_spacing->getPoints() != 0) {
      $this->_widths = array();

      for ($i=0; $i<$num_words; $i++) {
        $num_chars = strlen($this->words[$i]);

        for ($j=0; $j<$num_chars; $j++) {
          $this->_widths[] = $driver->stringwidth($this->words[$i]{$j}, 
                                                    $font_name, 
                                                    $this->encodings[$i], 
                                                    $font_size);
        };

        $this->width += $letter_spacing->getPoints()*$num_chars;
      };
    };

    return true;
  }

  function show(&$driver) {
    /**
     * Check if font-size have been set to 0; in this case we should not draw this box at all
     */
    $font_size = $this->getCSSProperty(CSS_FONT_SIZE);
    if ($font_size->getPoints() == 0) { 
      return true; 
    }

    // Check if current text box will be cut-off by the page edge
    // Get Y coordinate of the top edge of the box
    $top    = $this->get_top_margin();
    // Get Y coordinate of the bottom edge of the box
    $bottom = $this->get_bottom_margin();
    
    $top_inside    = $top    >= $driver->getPageBottom()-EPSILON;
    $bottom_inside = $bottom >= $driver->getPageBottom()-EPSILON;
       
    if (!$top_inside && !$bottom_inside) { 
      return true; 
    }
    
    return $this->_showText($driver);
  }

  function _showText(&$driver) {
    if (!is_null($this->wrapped)) {
      return $this->_showTextWrapped($driver);
    } else {
      return $this->_showTextNormal($driver);
    };
  }

  function _showTextWrapped(&$driver) {
    // draw generic box
    parent::show($driver);

    $font_size = $this->getCSSProperty(CSS_FONT_SIZE);
    
    $decoration = $this->getCSSProperty(CSS_TEXT_DECORATION);
    
    // draw text decoration
    $driver->decoration($decoration['U'],
                        $decoration['O'],
                        $decoration['T']);

    $letter_spacing = $this->getCSSProperty(CSS_LETTER_SPACING);
    
    // Output text with the selected font
    // note that we're using $default_baseline; 
    // the alignment offset - the difference between baseline and default_baseline values
    // is taken into account inside the get_top/get_bottom functions
    //
    $current_char = 0;

    $left = $this->wrapped[1];
    $top  = $this->get_top() - $this->default_baseline;
    $num_words = count($this->words);

    /**
     * First part of wrapped word (before hyphen)
     */
    for ($i=0; $i<$this->wrapped[0][0]; $i++) {
      // Activate font
      $status = $driver->setfont($this->_get_font_name($driver, $i), 
                                 $this->encodings[$i], 
                                 $font_size->getPoints());
      if (is_null($status)) { 
        error_log("TextBox::show: setfont call failed");
        return null; 
      };
        
      $driver->show_xy($this->words[$i], 
                       $left, 
                       $this->wrapped[2] - $this->default_baseline);
      $left += $this->_word_widths[$i];
    };

    $index = $this->wrapped[0][0];
      
    $status = $driver->setfont($this->_get_font_name($driver, $index), 
                               $this->encodings[$index], 
                               $font_size->getPoints());
    if (is_null($status)) { 
      error_log("TextBox::show: setfont call failed");
      return null; 
    };

    $driver->show_xy(substr($this->words[$index],0,$this->wrapped[0][1])."-", 
                     $left, 
                     $this->wrapped[2] - $this->default_baseline);

    /**
     * Second part of wrapped word (after hyphen)
     */

    $left = $this->get_left();
    $top  = $this->get_top();
    $driver->show_xy(substr($this->words[$index],$this->wrapped[0][1]), 
                     $left, 
                     $top - $this->default_baseline);

    $size = count($this->words);
    for ($i = $this->wrapped[0][0]+1; $i<$size; $i++) {
      // Activate font
      $status = $driver->setfont($this->_get_font_name($driver, $i), 
                                 $this->encodings[$i], 
                                 $font_size->getPoints());
      if (is_null($status)) { 
        error_log("TextBox::show: setfont call failed");
        return null; 
      };

      $driver->show_xy($this->words[$i], 
                       $left, 
                       $top - $this->default_baseline);

      $left += $this->_word_widths[$i];      
    };

    return true;
  }

  function _showTextNormal(&$driver) {
    // draw generic box
    parent::show($driver);

    $font_size = $this->getCSSProperty(CSS_FONT_SIZE);
    
    $decoration = $this->getCSSProperty(CSS_TEXT_DECORATION);
    
    // draw text decoration
    $driver->decoration($decoration['U'],
                        $decoration['O'],
                        $decoration['T']);

    $letter_spacing = $this->getCSSProperty(CSS_LETTER_SPACING);
    
    if ($letter_spacing->getPoints() == 0) {
      // Output text with the selected font
      // note that we're using $default_baseline; 
      // the alignment offset - the difference between baseline and default_baseline values
      // is taken into account inside the get_top/get_bottom functions
      //
      $size = count($this->words);
      $left = $this->get_left();

      for ($i=0; $i<$size; $i++) {
        // Activate font
        $status = $driver->setfont($this->_get_font_name($driver, $i), 
                                   $this->encodings[$i], 
                                   $font_size->getPoints());
        if (is_null($status)) { 
          error_log("TextBox::show: setfont call failed");
          return null; 
        };

        $driver->show_xy($this->words[$i], 
                         $left, 
                         $this->get_top() - $this->default_baseline);

        $left += $this->_word_widths[$i];
      };
    } else {
      $current_char = 0;

      $left = $this->get_left();
      $top  = $this->get_top() - $this->default_baseline;
      $num_words = count($this->words);

      for ($i=0; $i<$num_words; $i++) {
        $num_chars = strlen($this->words[$i]);

        for ($j=0; $j<$num_chars; $j++) {
          $status = $driver->setfont($this->_get_font_name($driver, $i), 
                                     $this->encodings[$i], 
                                     $font_size->getPoints());

          $driver->show_xy($this->words[$i]{$j}, $left, $top);
          $left += $this->_widths[$current_char] + $letter_spacing->getPoints();
          $current_char++;
        };
      };
    };

    return true;
  }

  function show_fixed(&$driver) {
    $font_size = $this->getCSSProperty(CSS_FONT_SIZE);

    // Check if font-size have been set to 0; in this case we should not draw this box at all
    if ($font_size->getPoints() == 0) { 
      return true; 
    }

    return $this->_showText($driver);
  }

  function offset($dx, $dy) {
    parent::offset($dx, $dy);

    // Note that horizonal offset should be called explicitly from text-align routines
    // otherwise wrapped part will be offset twice (as offset is called both for 
    // wrapped and non-wrapped parts).
    if (!is_null($this->wrapped)) {
      $this->offset_wrapped($dx, $dy);
    };
  }

  function offset_wrapped($dx, $dy) {
    $this->wrapped[1] += $dx;
    $this->wrapped[2] += $dy;    
  }

  function reflow_whitespace(&$linebox_started, &$previous_whitespace) {
    $linebox_started = true;
    $previous_whitespace = false;
    return;
  }

  function is_null() { return false; }
}
?>