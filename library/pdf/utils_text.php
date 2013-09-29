<?php
// $Header: /cvsroot/html2ps/utils_text.php,v 1.2 2005/07/01 18:01:58 Konstantin Exp $

function squeeze($string) {
  return preg_replace("![ \n\t]+!"," ",trim($string));
}

?>