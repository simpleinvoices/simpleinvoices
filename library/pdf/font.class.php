<?php

$GLOBALS['g_last_assigned_font_id'] = 0;

class Font {
  var $underline_position;
  var $underline_thickness;
  var $ascender;
  var $descender;
  var $char_widths;
  var $bbox;

  function ascender() { 
    return $this->ascender; 
  }

  function descender() { 
    return $this->descender; 
  }
  
  function error_message() { 
    return $this->error_message; 
  }

  function Font() {}

  function linethrough_position() {
    return $this->bbox[3]*0.25;
  }

  function name() {
    return $this->name;
  }

  function overline_position() {
    return $this->bbox[3]*0.8;
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

class FontTrueType extends Font {
  function create($fontfile, $encoding) {
    $font = new FontTrueType();
    $font->_read(TTF_FONTS_REPOSITORY.$fontfile, $encoding);
    return $font;
  }

  /**
   * TODO: cache results; replace makefont with this utility
   */
  function _read($file, $encoding) {
    error_log(sprintf("Parsing font file file %s for encoding %s", $file, $encoding));
    
    $font = new OpenTypeFile();
    $font->open($file);
    $hhea = $font->getTable('hhea');
    $head = $font->getTable('head');
    $hmtx = $font->getTable('hmtx');
    $post = $font->getTable('post');
    $cmap = $font->getTable('cmap');
    $subtable = $cmap->findSubtable(OT_CMAP_PLATFORM_WINDOWS,
                                    OT_CMAP_PLATFORM_WINDOWS_UNICODE);  

    /**
     * Read character widths for selected encoding
     */
    $widths = array();
    $manager = ManagerEncoding::get();
    $map = $manager->getEncodingVector($encoding);
    foreach ($map as $code => $ucs2) {
      $glyphIndex = $subtable->lookup($ucs2);
      if (!is_null($glyphIndex)) {
        $widths[$code] = floor($hmtx->_hMetrics[$glyphIndex]['advanceWidth']*1000/$head->_unitsPerEm);
      } else {
        $widths[$code] = DEFAULT_CHAR_WIDTH;
      };
    };

    // Fill unknown characters with the default char width
    for ($i=0; $i<256; $i++) {
      if (!isset($widths[chr($i)])) {
        $widths[chr($i)] = DEFAULT_CHAR_WIDTH;
      };
    };

    $this->ascender            = floor($hhea->_ascender*1000/$head->_unitsPerEm);
    $this->descender           = floor($hhea->_descender*1000/$head->_unitsPerEm);
    $this->bbox                = array($head->_xMin*1000/$head->_unitsPerEm,
                                       $head->_yMin*1000/$head->_unitsPerEm,
                                       $head->_xMax*1000/$head->_unitsPerEm,
                                       $head->_yMax*1000/$head->_unitsPerEm);
    $this->underline_position  = floor($post->_underlinePosition*1000/$head->_unitsPerEm);
    $this->underline_thickness = floor($post->_underlineThickness*1000/$head->_unitsPerEm);
    $this->char_widths         = $widths;

    $font->close();
  }
}

// Note that ALL font dimensions are measured in 1/1000 of font size units;
//
class FontType1 extends Font {
  function &create($typeface, $encoding, $font_resolver, &$error_message) {
    $font = new FontType1();

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
}
?>