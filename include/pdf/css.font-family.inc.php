<?php

class CSSFontFamily extends CSSSubFieldProperty {
  function default_value() {
    return 'times';
  }

  function parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    }

    $subvalues = preg_split("/\s*,\s*/",$value);

    foreach ($subvalues as $subvalue) {
      $subvalue = trim(strtolower($subvalue));   
    
      // Check if current subvalue is not empty (say, in case of 'font-family:;' or 'font-family:family1,,family2;')
      if ($subvalue !== "") {

        // Some multi-word font family names can be enclosed in quotes; remove them
        if ($subvalue{0} == "'") {
          $subvalue = substr($subvalue,1,strlen($subvalue)-2);
        } elseif ($subvalue{0} == '"') {
          $subvalue = substr($subvalue,1,strlen($subvalue)-2);
        };
      
        global $g_font_resolver;
        if ($g_font_resolver->have_font_family($subvalue)) { return $subvalue; };

        global $g_font_resolver_pdf;
        if ($g_font_resolver_pdf->have_font_family($subvalue)) { return $subvalue; };
      };
    };

    // Unknown family type
    return "times";
  }

  function getPropertyCode() {
    return CSS_FONT_FAMILY;
  }

  function getPropertyName() {
    return 'font-family';
  }

}

?>