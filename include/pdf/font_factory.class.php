<?php
class FontFactory {
  var $fonts;
  var $error_message;

  function error_message() { 
    return $this->error_message;
  }

  function FontFactory() {
    $this->fonts = array();
  }

  function &get_type1($name, $encoding) {
    if (!isset($this->fonts[$name][$encoding])) {
      global $g_font_resolver;

      $font =& Font::create($name, $encoding, $g_font_resolver, $this->error_message);
      if (is_null($font)) { 
        $dummy = null;
        return $dummy; 
      };

      $this->fonts[$name][$encoding] = $font;
    };

    return $this->fonts[$name][$encoding];
  }
}

?>