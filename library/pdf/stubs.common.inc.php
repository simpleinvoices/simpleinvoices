<?php
// $Header: /cvsroot/html2ps/stubs.common.inc.php,v 1.5 2006/11/11 13:43:53 Konstantin Exp $

if (!function_exists('file_get_contents')) {
  require_once(HTML2PS_DIR.'stubs.file_get_contents.inc.php');
}

if (!function_exists('file_put_contents')) {
  require_once(HTML2PS_DIR.'stubs.file_put_contents.inc.php');
}

if (!function_exists('is_executable')) {
  require_once(HTML2PS_DIR.'stubs.is_executable.inc.php');
}

if (!function_exists('memory_get_usage')) {
  require_once(HTML2PS_DIR.'stubs.memory_get_usage.inc.php');
}

if (!function_exists('_')) {
  require_once(HTML2PS_DIR.'stubs._.inc.php');
}

?>