<?php
// $Header: /cvsroot/html2ps/css.text-transform.inc.php,v 1.2 2006/07/09 09:07:46 Konstantin Exp $

define('CSS_TEXT_TRANSFORM_NONE'      ,0);
define('CSS_TEXT_TRANSFORM_CAPITALIZE',1);
define('CSS_TEXT_TRANSFORM_UPPERCASE' ,2);
define('CSS_TEXT_TRANSFORM_LOWERCASE' ,3);

class CSSTextTransform extends CSSPropertyStringSet {
  function CSSTextTransform() { 
    $this->CSSPropertyStringSet(false, 
                                true,
                                array('inherit'    => CSS_PROPERTY_INHERIT,
                                      'none'       => CSS_TEXT_TRANSFORM_NONE,
                                      'capitalize' => CSS_TEXT_TRANSFORM_CAPITALIZE,
                                      'uppercase'  => CSS_TEXT_TRANSFORM_UPPERCASE,
                                      'lowercase'  => CSS_TEXT_TRANSFORM_LOWERCASE)); 
  }

  function default_value() { 
    return CSS_TEXT_TRANSFORM_NONE; 
  }

  function getPropertyCode() {
    return CSS_TEXT_TRANSFORM;
  }

  function getPropertyName() {
    return 'text-transform';
  }
}

CSS::register_css_property(new CSSTextTransform);

?>
