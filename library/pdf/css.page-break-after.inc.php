<?php
// $Header: /cvsroot/html2ps/css.page-break-after.inc.php,v 1.3 2007/01/09 20:13:48 Konstantin Exp $

class CSSPageBreakAfter extends CSSPageBreak {
  function getPropertyCode() {
    return CSS_PAGE_BREAK_AFTER;
  }

  function getPropertyName() {
    return 'page-break-after';
  }
}

$css_page_break_after_inc_reg1 = new CSSPageBreakAfter();
CSS::register_css_property($css_page_break_after_inc_reg1);
