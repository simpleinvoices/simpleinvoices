<?php
// $Header: /cvsroot/html2ps/xhtml.comments.inc.php,v 1.2 2005/04/27 16:27:46 Konstantin Exp $

function remove_comments(&$html) {
  $html = preg_replace("#<!--.*?-->#is","",$html);
  $html = preg_replace("#<!.*?>#is","",$html);
}

?>