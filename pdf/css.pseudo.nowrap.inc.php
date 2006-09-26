<?php
// $Header: /cvsroot/html2ps/css.pseudo.nowrap.inc.php,v 1.4 2006/04/16 16:54:57 Konstantin Exp $

define('NOWRAP_NORMAL',0);
define('NOWRAP_NOWRAP',1);

class CSSPseudoNoWrap extends CSSProperty {
  function CSSPseudoNoWrap() { $this->CSSProperty(false, false); }
  function default_value() { return NOWRAP_NORMAL; }

  function pdf(){ return $this->get(); }
}

register_css_property('-nowrap', new CSSPseudoNoWrap);
  
?>