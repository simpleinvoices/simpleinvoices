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

$css_page_break_inside_inc_reg1 = new CSSPageBreakInside();
CSS::register_css_property($css_page_break_inside_inc_reg1);
