<?php

require_once(HTML2PS_DIR.'encoding.inc.php');
require_once(HTML2PS_DIR.'encoding.entities.inc.php');
require_once(HTML2PS_DIR.'encoding.glyphs.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-1.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-2.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-3.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-4.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-5.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-6.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-7.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-8.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-9.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-10.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-11.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-13.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-14.inc.php');
require_once(HTML2PS_DIR.'encoding.iso-8859-15.inc.php');
require_once(HTML2PS_DIR.'encoding.koi8-r.inc.php');
require_once(HTML2PS_DIR.'encoding.cp866.inc.php');
require_once(HTML2PS_DIR.'encoding.windows-1250.inc.php');
require_once(HTML2PS_DIR.'encoding.windows-1251.inc.php');
require_once(HTML2PS_DIR.'encoding.windows-1252.inc.php');
require_once(HTML2PS_DIR.'encoding.dingbats.inc.php');
require_once(HTML2PS_DIR.'encoding.symbol.inc.php');

// TODO: this works for PS encoding names only
class ManagerEncoding {
  var $_encodings;
  var $_custom_vector_name;

  var $_utf8_mapping;

  function toUTF8($word, $encoding) {
    $vector = $this->getEncodingVector($encoding);
    
    $converted = '';
    for ($i=0, $size=strlen($word); $i < $size; $i++) {
      $converted .= code_to_utf8($vector[$word{$i}]);
    };

    return $converted;
  }

  function getMapping($char) {
    if (!isset($this->_utf8_mapping)) {
      $this->_loadMapping(CACHE_DIR . 'utf8.mappings.dat');
    };

    if (!isset($this->_utf8_mapping[$char])) { 
      return null; 
    };
    return $this->_utf8_mapping[$char];
  }

  function _loadMapping($mapping_file) {
    if (!is_readable($mapping_file)) {
      $this->_generateMapping($mapping_file);
    } else {
      $this->_utf8_mapping = unserialize(file_get_contents($mapping_file));
    };
  }

  function _generateMapping($mapping_file) {
    global $g_utf8_converters;

    $this->_utf8_mapping = array();
    foreach (array_keys($g_utf8_converters) as $encoding) {
      $flipped = array_flip($g_utf8_converters[$encoding][0]);
      foreach ($flipped as $utf => $code) {
        $this->_utf8_mapping[code_to_utf8($utf)][$encoding] = $code;
      };
    };

    $file = fopen($mapping_file,'w');
    fwrite($file, serialize($this->_utf8_mapping));
    fclose($file);
  }

  function ManagerEncoding() {
    $this->_encodings = array();

    $this->registerCustomEncoding("custom", array(0,1,2,3,4,5,6,7,8,9,10,
                                                  11,12,13,14,15,16,17,18,19,20,
                                                  21,22,23,24,25,26,27,28,29,30,
                                                  31,32));
  }

  function getCanonizedEncodingName($encoding) {
    global $g_encoding_aliases;

    if (isset($g_encoding_aliases[$encoding])) {
      return $g_encoding_aliases[$encoding];
    };

    return $encoding;
  }

  function registerCustomEncoding($name, $vector) {
    $this->registerEncoding($name, $vector);
    $this->_custom_vector_name = $name;
  }

  function getCustomEncodingName() {
    return $this->_custom_vector_name;
  }

  function getCustomEncodingVector() {
    return $this->_encodings[$this->getCustomEncodingName()];
  }

  function registerEncoding($name, $vector) {
    $this->_encodings[$name] = $vector;
  }

  /**
   * @TODO: handle more than 256 custom characters
   */
  function addCustomChar($char) {
    $vector_name = $this->getCustomEncodingName();

    $index = count($this->_encodings[$vector_name]);
    $this->_encodings[$vector_name][$index] = $char;
    $this->_utf8_mapping[code_to_utf8($char)]['custom'] = chr($index);

    return chr($index);
  }

  /**
   * Get  an encoding  vector  (array containing  256 elements;  every
   * element is an ucs-2 encoded character)
   *
   * @param $encoding Encoding name
   *
   * @return Array encoding vector; null if this encoding is not known to the script
   */
  function getEncodingVector($encoding) {
    $encoding = $this->getCanonizedEncodingName($encoding);

    /**
     * @TODO: HACK. Currently custom encoding and "standard" encodings 
     * are handled separately, so we must explicitly check if current 
     * encoding is custom
     */
    if ($encoding == $this->getCustomEncodingName()) {
      $vector = array();
      $custom_vector = $this->getCustomEncodingVector();

      $size = count($custom_vector);
      for ($i=0; $i<$size; $i++) {
        $vector[chr($i)] = $custom_vector[$i];
      };

    } else {
      global $g_utf8_converters;

      if (!isset($g_utf8_converters[$encoding])) {
        return null;
      };

      $vector = $g_utf8_converters[$encoding][0];
    };

    for ($i=0; $i<=255; $i++) {
      if (!isset($vector[chr($i)])) {
        $vector[chr($i)] = 0xFFFF;
      };
    };
    return $vector;
  }

  function &get() {
    global $g_manager_encodings;
    return $g_manager_encodings;
  }

  function get_encoding_glyphs($encoding) {
    $vector = $this->getEncodingVector($encoding);
    if (is_null($vector)) { 
      error_log(sprintf("Cannot get encoding vector for encoding '%s'", $encoding));
      return null; 
    };
    return $this->vector_to_glyphs($vector);
  }

  function get_glyph_to_code_mapping($encoding) {
    $vector = $this->getEncodingVector($encoding);

    $result = array();
    foreach ($vector as $code => $uccode) {
      if (isset($GLOBALS['g_unicode_glyphs'][$uccode])) {
        $result[$GLOBALS['g_unicode_glyphs'][$uccode]][] = $code;
      };
    };

    return $result;
  }

  function vector_to_glyphs($vector) {
    $result = array();

    foreach ($vector as $code => $ucs2) {      
      if (isset($GLOBALS['g_unicode_glyphs'][$ucs2])) {
        $result[$code] = $GLOBALS['g_unicode_glyphs'][$ucs2];
      } elseif ($ucs2 == 0xFFFF) {
        $result[$code] = ".notdef";
      } else {
        // Use "Unicode and Glyph Names" mapping from Adobe
        // http://partners.adobe.com/public/developer/opentype/index_glyph.html
        $result[$code] = sprintf("u%04X", $ucs2);
      };
    };

    return $result;
  }

  function get_ps_encoding_vector($encoding) {
    $vector = $this->getEncodingVector($encoding);

    $result = "/".$encoding." [ \n";
    for ($i=0; $i<256; $i++) {
      if ($i % 10 == 0) { $result .= "\n"; };

      // ! Note the order of array checking; optimizing interpreters may break this
      if (isset($vector[chr($i)]) && isset($GLOBALS['g_unicode_glyphs'][$vector[chr($i)]])) {
        $result .= " /".$GLOBALS['g_unicode_glyphs'][$vector[chr($i)]];
      } else {
        $result .= " /.notdef";
      };
    };
    $result .= " ] readonly def";

    return $result;
  }

  function getNextUTF8Char($raw_content, &$ptr) {
    if ((ord($raw_content{$ptr}) & 0xF0) == 0xF0) {
      $charlen = 4;
    } elseif ((ord($raw_content{$ptr}) & 0xE0) == 0xE0) {
      $charlen = 3;
    } elseif ((ord($raw_content{$ptr}) & 0xC0) == 0xC0) {
      $charlen = 2;
    } else {
      $charlen = 1;
    };
    
    $char = substr($raw_content,$ptr,$charlen);
    $ptr += $charlen;

    return $char;
  }
}

global $g_manager_encodings;
$g_manager_encodings = new ManagerEncoding;
?>