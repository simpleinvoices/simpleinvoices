<?php

define('BACKGROUND_ATTACHMENT_SCROLL', 1);
define('BACKGROUND_ATTACHMENT_FIXED', 2);

class CSSBackgroundAttachment extends CSSSubFieldProperty {
  function getPropertyCode() {
    return CSS_BACKGROUND_ATTACHMENT;
  }

  function getPropertyName() {
    return 'background-attachment';
  }

  function default_value() {
    return BACKGROUND_ATTACHMENT_SCROLL;
  }

  function &parse($value_string) {
    if ($value_string === 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    if (preg_match('/\bscroll\b/', $value_string)) {
      $value = BACKGROUND_ATTACHMENT_SCROLL;
    } elseif (preg_match('/\bfixed\b/', $value_string)) {
      $value = BACKGROUND_ATTACHMENT_FIXED;
    } else {
      $value = BACKGROUND_ATTACHMENT_SCROLL;
    };

    return $value;
  }
}
?>