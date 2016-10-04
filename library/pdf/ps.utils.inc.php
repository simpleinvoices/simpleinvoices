<?php
// $Header: /cvsroot/html2ps/ps.utils.inc.php,v 1.10 2005/11/12 06:29:23 Konstantin Exp $

function trim_ps_comments($data) {
  $data = preg_replace("/(?<!\\\\)%.*/","",$data);
  return preg_replace("/ +$/","",$data);
}

function format_ps_color($color) {
  return sprintf("%.3f %.3f %.3f",$color[0]/255,$color[1]/255,$color[2]/255);
}
?>