<?php
// $Header: /cvsroot/html2ps/css.text-align.inc.php,v 1.8 2006/04/16 16:54:57 Konstantin Exp $

define('TA_LEFT',0);
define('TA_RIGHT',1);
define('TA_CENTER',2);
define('TA_JUSTIFY',3);

class CSSTextAlign extends CSSProperty {
  function CSSTextAlign() { $this->CSSProperty(true, true); }
  
  function default_value() { return TA_LEFT; }

  function parse($value) {
    // Convert value to lower case, as html allows values 
    // in both cases to be entered
    $value = strtolower($value);

    if ($value === 'left') { return TA_LEFT; }
    if ($value === 'right') { return TA_RIGHT; }
    if ($value === 'center') { return TA_CENTER; }

    // For compatibility with non-valid HTML
    //
    if ($value === 'middle') { return TA_CENTER; }

    if ($value === 'justify') { return TA_JUSTIFY; }
    return $this->default_value();
  }

  function value2pdf($value) { 
    switch ($value) {
    case TA_LEFT:
      return "ta_left";
    case TA_RIGHT:
      return "ta_right";
    case TA_CENTER:
      return "ta_center";
    case TA_JUSTIFY:
      return "ta_justify";
    default:
      return "ta_left";
    }
  }
}

register_css_property('text-align', new CSSTextAlign);

?>