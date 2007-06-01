<?php
// $Header: /cvsroot/html2ps/stubs.common.inc.php,v 1.2 2006/01/31 18:08:14 Konstantin Exp $

if (!function_exists('file_get_contents')) {
  require_once('stubs.file_get_contents.inc.php');
}

if (!function_exists('is_executable')) {
  require_once('stubs.is_executable.inc.php');
}

?>