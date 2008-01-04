<?php

$g_last_assigned_font_id = 0;

// Note that ALL font dimensions are measured in 1/1000 of font size units;
//
class Font {
  var $underline_position;
  var $underline_thickness;
  var $ascender;
  var $descender;
  var $char_widths;
  var $bbox;

  function ascender() { return $this->ascender; }

  function descender() { return $this->descender; }

  function error_message() { return $this->error_message; }

  function Font() {}

  function &create($typeface, $encoding, $font_resolver, &$error_message) {
    $font = new Font();

    $font->underline_position = 0;
    $font->underline_thickness = 0;
    $font->ascender;
    $font->descender;
    $font->char_widths = array();
    $font->bbox = array();

    global $g_last_assigned_font_id;
    $g_last_assigned_font_id++;

    $font->name = "font".$g_last_assigned_font_id;

    // Get and load the metrics file
    $afm = $font_resolver->get_afm_mapping($typeface);

    if (!$font->_parse_afm($afm, $typeface, $encoding)) { 
      $error_message = $font->error_message();
      $dummy = null; 
      return $dummy; 
    };

    return $font;
  }

  function linethrough_position() {
    return $this->bbox[3]*0.25;
  }

  function name() {
    return $this->name;
  }

  function overline_position() {
    return $this->bbox[3]*0.8;
  }

  // Parse the AFM metric file; keep only sized of glyphs present in the chosen encoding
  function _parse_afm($afm, $typeface, $encoding) {
    global $g_manager_encodings;
    $encoding_data = $g_manager_encodings->get_glyph_to_code_mapping($encoding);

    $filename = TYPE1_FONTS_REPOSITORY.$afm.".afm";

    $file = @fopen($filename, 'r');
    if (!$file) {
      $_filename = $filename;
      $_typeface = $typeface;

      ob_start();
      include(HTML2PS_DIR.'/templates/error._missing_afm.tpl');
      $this->error_message = ob_get_contents();
      ob_end_clean();

      error_log(sprintf("Missing font metrics file: %s",$filename));
      return false;
    };

    while ($line = fgets($file)) {
      if (preg_match("/C\s-?\d+\s;\sWX\s(\d+)\s;\sN\s(\S+)\s;/",$line,$matches)) {
        $glyph_width = $matches[1];
        $glyph_name  = $matches[2];
       
        // This line is a character width definition
        if (isset($encoding_data[$glyph_name])) {
          foreach ($encoding_data[$glyph_name] as $c) {
            $this->char_widths[$c] = $glyph_width;
          };
        };
        
      } elseif (preg_match("/UnderlinePosition ([\d-]+)/",$line,$matches)) {
        // This line is an underline position line
        $this->underline_position = $matches[1];
        
      } elseif (preg_match("/UnderlineThickness ([\d-]+)/",$line,$matches)) {
        // This line is an underline thickness line
        $this->underline_thickness = $matches[1];
        
      } elseif (preg_match("/Ascender ([\d-]+)/",$line,$matches)) {
        // This line is an ascender line
        $this->ascender = $matches[1];
        
      } elseif (preg_match("/Descender ([\d-]+)/",$line,$matches)) {
        // This line is an descender line
        $this->descender = $matches[1];
        
      } elseif (preg_match("/FontBBox ([\d-]+) ([\d-]+) ([\d-]+) ([\d-]+)/",$line,$matches)) {
        // This line is an font BBox line
        $this->bbox = array($matches[1], $matches[2], $matches[3], $matches[4]);
      };
    };

    fclose($file);

    // Fill unknown characters with the default char width
    for ($i=0; $i<256; $i++) {
      if (!isset($this->char_widths[chr($i)])) {
        $this->char_widths[chr($i)] = DEFAULT_CHAR_WIDTH;
      };
    };

    return true;
  }

  function points($fontsize, $dimension) {
    return $dimension * $fontsize / 1000;
  }

  function stringwidth($string) {
    $width = 0;

    $length = strlen($string);
    for ($i=0; $i<$length; $i++) {
      $width += $this->char_widths[$string{$i}];
    };

    return $width;
  }

  function underline_position() {
    return $this->underline_position;
  }

  function underline_thickness() {
    return $this->underline_thickness;
  }
}
?>