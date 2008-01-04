<?php
// $Header: /cvsroot/html2ps/converter.class.php,v 1.4 2006/03/22 19:02:36 Konstantin Exp $

class Converter {
  function create() {
    if (function_exists('iconv')) {
      return new IconvConverter;
    } else {
      return new PurePHPConverter;
    }
  }
}

class IconvConverter {
  function to_utf8($html, $encoding) {
    return iconv(strtoupper($encoding), "UTF-8", $html);
  }
}

class PurePHPConverter {
  function apply_aliases($encoding) {
    global $g_encoding_aliases;

    if (isset($g_encoding_aliases[$encoding])) {
      return $g_encoding_aliases[$encoding];
    }

    return $encoding;
  }

  function to_utf8($html, $encoding) {
    global $g_utf8_converters;

    $encoding = $this->apply_aliases($encoding);

    if ($encoding === 'iso-8859-1') {
      return utf8_encode($html);
    } elseif ($encoding === 'utf-8') {
      return $html;
    } elseif(isset($g_utf8_converters[$encoding])) {
      return something_to_utf8($html, $g_utf8_converters[$encoding][0]);
    } else {
      die("Unsupported encoding detected: '$encoding'");
    };
  }
}
?>