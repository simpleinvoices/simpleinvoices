<?php
function push_table_border($border) {
  global $g_table_border;
  array_unshift($g_table_border, $border);
}

function pop_table_border() {
  global $g_table_border;
  array_shift($g_table_border);
}

function get_table_border() {
  global $g_table_border;
  return $g_table_border[0];
}

global $g_table_border;
$g_table_border     = array(default_border());

?>