<?php
// $Header: /cvsroot/html2ps/css.background.image.inc.php,v 1.15 2006/03/19 09:25:35 Konstantin Exp $

class CSSBackgroundImage extends CSSSubProperty {
  function default_value() { 
    return new BackgroundImage(null, null); 
  }

  function parse($value, &$pipeline) {
    global $g_config;
    if (!$g_config['renderimages']) {
      return CSSBackgroundImage::default_value();
    };

    // 'url' value
    if (preg_match("/url\((.*[^\\\\]?)\)/is",$value,$matches)) {
      $url = $matches[1];

      $full_url = $pipeline->guess_url(css_remove_value_quotes($url));
      return new BackgroundImage($full_url,
                                 Image::get($full_url, $pipeline));
    }
    // 'none' and unrecognzed values
    return CSSBackgroundImage::default_value();
  }
}

?>