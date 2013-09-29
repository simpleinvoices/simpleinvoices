<?php
// $Header: /cvsroot/html2ps/stubs.file_get_contents.inc.php,v 1.1 2005/04/27 16:27:45 Konstantin Exp $

function file_get_contents($file) {
  $lines = file($file);
  if ($lines) {
    return implode('',$lines);
  } else {
    return "";
  };
}
?>