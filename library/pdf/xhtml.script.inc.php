<?php
// $Header: /cvsroot/html2ps/xhtml.script.inc.php,v 1.2 2005/04/27 16:27:46 Konstantin Exp $

function process_script($sample_html) {
  return preg_replace("#<script.*?</script>#is","",$sample_html);
}

?>