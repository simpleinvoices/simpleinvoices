<?php
// $Header: /cvsroot/html2ps/css.page-break-inside.inc.php,v 1.1.2.1 2006/11/16 03:19:36 Konstantin Exp $

class CSSPageBreakInside extends CSSPageBreak {
  function getPropertyCode() {
    return CSS_PAGE_BREAK_INSIDE;
  }

  function getPropertyName() {
    return 'page-break-inside';
  }
}

CSS::register_css_property(new CSSPageBreakInside);

?>