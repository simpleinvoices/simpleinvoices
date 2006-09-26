<?php
// $Header: /cvsroot/html2ps/css.list-style-image.inc.php,v 1.4 2006/03/19 09:25:36 Konstantin Exp $

class CSSListStyleImage extends CSSSubProperty {
  // CSS 2.1: default value for list-style-image is none
  function default_value() { return new ListStyleImage(null, null); }

  function parse($value, &$pipeline) {
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

    return CSSListStyleImage::default_value();
  }
}

?>