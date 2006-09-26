<?php
// $Header: /cvsroot/html2ps/box.text.php,v 1.40 2006/05/27 15:33:26 Konstantin Exp $

// TODO: from my POV, it wll be better to pass the font- or CSS-controlling object to the constructor
// instead of using globally visible functions in 'show'.

class TextBox extends GenericInlineBox {
  var $word;

  var $encoding;
  var $src_encoding;

  var $size;
  var $decoration;

  var $family;
  var $weight;
  var $style;

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

    if ($height > 0) {
      $scale = $this->line_height->apply($this->ascender + $this->descender) / ($this->ascender + $this->descender);
    } else {
      $scale = 0;
    };

    // Calculate the height delta of the text box

    $delta = $height * ($scale-1);
    $this->put_height(($this->ascender + $this->descender)*$scale);
    $this->default_baseline = $this->default_baseline + $delta/2;
  }

  function _get_font_name(&$viewport) {
    $font_resolver =& $viewport->get_font_resolver();
    return $font_resolver->ps_font_family($this->family, $this->weight, $this->style, $this->src_encoding);
  }

  function &create($text, $encoding) {
    $box =& new TextBoxString($text, $encoding);
    return $box;
  }

  function TextBox($word, $encoding) {
    $this->GenericFormattedBox();

    $this->word         = $word;
    $this->src_encoding = $encoding;
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

  function get_min_width(&$context) {
    return $this->get_full_width();
  }

  function get_max_width(&$context) {
    return $this->get_full_width();
  }

  function reflow_static(&$parent, &$context) {  
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
    $parent->_current_x += $this->get_full_width();

    // Extends parents height
    $parent->extend_height($this->get_bottom_margin());

    // Update the value of current collapsed margin; pure text (non-span)
    // boxes always have zero margin

    $context->pop_collapsed_margin();
    $context->push_collapsed_margin( 0 );
  }

  function reflow_text(&$viewport) {
    $this->encoding = $viewport->encoding($this->src_encoding);

    // Determine font metrics
    $ascender  = $viewport->font_ascender($this->_get_font_name($viewport), $this->encoding);
    if (is_null($ascender)) {
      return null;
    };

    $descender = $viewport->font_descender($this->_get_font_name($viewport), $this->encoding); 
    if (is_null($descender)) {
      return null;
    };

    // Setup box size:
    $this->ascender         = $ascender  * $this->font_size;
    $this->descender        = $descender * $this->font_size;
    $this->default_baseline = $this->ascender; 
    $this->height           = $this->ascender + $this->descender; 

    if ($this->font_size > 0) {
      $this->width = $viewport->stringwidth($this->word, $this->_get_font_name($viewport), $this->encoding, $this->font_size);
    } else {
      $this->width = 0;
    };

    return true;
  }

  function show(&$viewport) {
    // Check if font-size have been set to 0; in this case we should not draw this box at all
    if ($this->font_size == 0) { return true; }

    // Check if current text box will be cut-off by the page edge
    // Get Y coordinate of the top edge of the box
    $top    = $this->get_top_margin();
    // Get Y coordinate of the bottom edge of the box
    $bottom = $this->get_bottom_margin();
    
    $top_inside    = $top > $viewport->get_bottom();
    $bottom_inside = $bottom > $viewport->get_bottom();
    
    if ($top_inside && !$bottom_inside) {
      // If yes, do not draw current text box at all; add an required value
      // to the viewport page offset to make the text box fully visible on the next page
      $viewport->offset_delta = max($viewport->offset_delta, $top - $viewport->get_bottom());
      return true;
    };
    
    if (!$top_inside && !$bottom_inside) { 
      return true; 
    }

    // draw generic box
    parent::show($viewport);

    // Activate font
    $status = $viewport->setfont($this->_get_font_name($viewport), $this->encoding, $this->font_size);
    if (is_null($status)) { 
      return null; 
    };

    // draw text decoration
    $viewport->decoration($this->decoration['U'],
                          $this->decoration['O'],
                          $this->decoration['T']);
    
    // Output text with the selected font
    // note that we're using $default_baseline; 
    // the alignment offset - the difference between baseline and default_baseline values
    // is taken into account inside the get_top/get_bottom functions
    //
    $viewport->show_xy($this->word, $this->get_left(), $this->get_top() - $this->default_baseline);

    return true;
  }

  function show_fixed(&$viewport) {
    // Check if font-size have been set to 0; in this case we should not draw this box at all
    if ($this->font_size == 0) { 
      return true; 
    }

    // draw generic box
    parent::show($viewport);

    // Activate font
    if (is_null($viewport->setfont($this->_get_font_name($viewport), $this->encoding, $this->font_size))) {
      return null;
    };

    // draw text decoration
    $viewport->decoration($this->decoration['U'],
                          $this->decoration['O'],
                          $this->decoration['T']);
    
    // Output text with the selected font
    // note that we're using $default_baseline; 
    // the alignment offset - the difference between baseline and default_baseline values
    // is taken into account inside the get_top/get_bottom functions
    //
    $viewport->show_xy($this->word, $this->get_left(), $this->get_top() - $this->default_baseline);

    return true;
  }

  function reflow_whitespace(&$linebox_started, &$previous_whitespace) {
    $linebox_started = true;
    $previous_whitespace = false;
    return;
  }

  function is_null() { return false; }
}
?>