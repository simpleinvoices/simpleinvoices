<?php
// $Header: /cvsroot/html2ps/output.pdflib.old.class.php,v 1.2 2006/11/11 13:43:53 Konstantin Exp $

require_once(HTML2PS_DIR.'output.pdflib.class.php');

class OutputDriverPdflibOld extends OutputDriverPdflib {
  function field_multiline_text($x, $y, $w, $h, $value, $name) { 
  }

  function field_text($x, $y, $w, $h, $value, $name) {
  }

  function field_password($x, $y, $w, $h, $value, $name) {
  }

  function field_pushbutton($x, $y, $w, $h) {
  }

  function field_pushbuttonimage($x, $y, $w, $h, $field_name, $value, $actionURL) {
  }

  function field_pushbuttonreset($x, $y, $w, $h) {
  }

  function field_pushbuttonsubmit($x, $y, $w, $h, $field_name, $value, $actionURL) {
  }

  function field_checkbox($x, $y, $w, $h, $name, $value, $checked) {
  }

  function field_radio($x, $y, $w, $h, $groupname, $value, $checked) {
  }

  function field_select($x, $y, $w, $h, $name, $value, $options) { 
  }

  function new_form($name) {
  }
}
?>