<?php

class CSSFontStyle extends CSSSubFieldProperty {
  function default_value() {
    return FS_NORMAL;
  }

  function parse($value) {
    $value = trim(strtolower($value));
    switch ($value) {
    case 'inherit':
      return CSS_PROPERTY_INHERIT;
    case 'normal':
      return FS_NORMAL;
    case 'italic':
      return FS_ITALIC;
    case 'oblique':
      return FS_OBLIQUE;
    };
  }

  function getPropertyCode() {
    return CSS_FONT_STYLE;
  }

  function getPropertyName() {
    return 'font-style';
  }

}

?>