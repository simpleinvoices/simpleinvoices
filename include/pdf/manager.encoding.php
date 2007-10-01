<?php

// TODO: this works for PS encoding names only
class ManagerEncoding {
  function get_glyph_to_code_mapping($encoding) {
    global $g_unicode_glyphs;

    $vector =& find_vector_by_ps_name($encoding);
   
    $result = array();
    foreach ($vector as $code => $uccode) {
      if (isset($g_unicode_glyphs[$uccode])) {
        $result[$g_unicode_glyphs[$uccode]][] = $code;
      };
    };
    return $result;
  }

  function get_ps_encoding_vector($encoding) {
    global $g_unicode_glyphs;
    $vector =& find_vector_by_ps_name($encoding);

    $result = "/".$encoding." [ \n";
    for ($i=0; $i<256; $i++) {
      if ($i % 10 == 0) { $result .= "\n"; };

      // ! Note the order of array checking; optimizing interpreters may break this
      if (isset($vector[chr($i)]) && isset($g_unicode_glyphs[$vector[chr($i)]])) {
        $result .= " /".$g_unicode_glyphs[$vector[chr($i)]];
      } else {
        $result .= " /.notdef";
      };
    };
    $result .= " ] readonly def";

    return $result;
  }
}

$g_manager_encodings = new ManagerEncoding;
?>