<?php
// $Header: /cvsroot/html2ps/output._interface.class.php,v 1.8 2007/01/09 20:13:48 Konstantin Exp $

class OutputDriver {
  function add_link($x, $y, $w, $h, $target) { }
  function add_local_link($left, $top, $width, $height, $anchor) { }
  function circle($x, $y, $r) { }
  function clip() {}
  function close() { die("Unoverridden 'close' method called in ".get_class($this)); }
  function closepath() {}

  function content_type() { die("Unoverridden 'content_type' method called in ".get_class($this));  }

  function dash($x, $y) { }
  function decoration($underline, $overline, $strikeout) { }

  function error_message() { die("Unoverridden 'error_message' method called in ".get_class($this)); }

  function new_form($name) {}

  function field_multiline_text($x, $y, $w, $h, $value, $field_name) { }
  function field_text($x, $y, $w, $h, $value, $field_name) { }
  function field_password($x, $y, $w, $h, $value, $field_name) { }

  function field_pushbutton($x, $y, $w, $h) { }
  function field_pushbuttonimage($x, $y, $w, $h, $field_name, $value, $actionURL) { }
  function field_pushbuttonreset($x, $y, $w, $h) { }
  function field_pushbuttonsubmit($x, $y, $w, $h, $field_name, $value, $actionURL) { }

  function field_checkbox($x, $y, $w, $h, $name, $value) { }
  function field_radio($x, $y, $w, $h, $groupname, $value, $checked) { }
  function field_select($x, $y, $w, $h, $name, $value, $options) { }

  function fill() { }
  function font_ascender($name, $encoding) {}

  /**
   * Note that positive value returned by this function 
   * means offset to the BOTTOM!
   */
  function font_descender($name, $encoding) {}

  function get_bottom() {}
  function image($image, $x, $y, $scale) {}
  function image_scaled($image, $x, $y, $scale_x, $scale_y) { }
  function image_ry($image, $x, $y, $height, $bottom, $ox, $oy, $scale) { }
  function image_rx($image, $x, $y, $width, $right, $ox, $oy, $scale) { }
  function image_rx_ry($image, $x, $y, $width, $height, $right, $bottom, $ox, $oy, $scale) { }
  function lineto($x, $y) { }
  function moveto($x, $y) { }
  function next_page($height) { }
  function release() { }
  function restore() { }
  function save() { }

  // Note that there's no functions for setting bold/italic style of the fonts;
  // you must keep in mind that roman/bold/italic font variations are, in fact, different 
  // fonts stored in different files and it is the business of the font resolver object to
  // find the appropriate font name. Here we just re-use it.
  //
  function setfont($name, $encoding, $size) {}
//   function setfontcore($name, $size) {}

  function setlinewidth($x) { }
  function setrgbcolor($r, $g, $b)  { }

  function set_watermark($text) { }

  function show_xy($text, $x, $y) {}
  function stringwidth($string, $name, $encoding, $size) { }
  function stroke() { }
}
?>