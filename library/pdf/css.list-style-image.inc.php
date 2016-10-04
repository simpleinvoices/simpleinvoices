<?php
// $Header: /cvsroot/html2ps/css.list-style-image.inc.php,v 1.6 2006/09/07 18:38:14 Konstantin Exp $

class CSSListStyleImage extends CSSSubFieldProperty {
  /**
   * CSS 2.1: default value for list-style-image is none
   */
  function default_value() { 
    return new ListStyleImage(null, null); 
  }

  function parse($value, &$pipeline) {
    if ($value === 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    global $g_config;
    if (!$g_config['renderimages']) {
      return CSSListStyleImage::default_value();
    };

    if (preg_match('/url\(([^)]+)\)/',$value, $matches)) { 
      $url = $matches[1];

      $full_url = $pipeline->guess_url(css_remove_value_quotes($url));
      return new ListStyleImage($full_url,
                                Image::get($full_url, $pipeline));
    };

    /**
     * 'none' value and all unrecognized values
     */
    return CSSListStyleImage::default_value();
  }

  function getPropertyCode() {
    return CSS_LIST_STYLE_IMAGE;
  }

  function getPropertyName() {
    return 'list-style-image';
  }
}

?>