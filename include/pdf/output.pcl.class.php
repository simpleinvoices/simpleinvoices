<?php
// $Header: /cvsroot/html2ps/output.pcl.class.php,v 1.2 2006/06/25 13:55:39 Konstantin Exp $

define("ASCII_ESCAPE", chr(27));

class StreamString {
  var $_content;

  function StreamString() {
    $this->_content = "";
  }

  function write($string) {
    $this->_content .= $string;
  }
}

/**
 * There are  two forms of PCL escape  sequences: two-character escape
 * sequences and parameterized escape sequences.
 */
class PCLEscapeSequence {
  function output(&$stream) {
    $stream->write(ASCII_ESCAPE.$this->getSequenceString());
  }
}

/**
 * Two-character escape sequences have the following form:
 * 
 * <Escape> X
 * 
 * where  “X”  is  a  character  that  defines  the  operation  to  be
 * performed. “X” may be any character from the ASCII table within the
 * range 48-126 decimal (“0” through “~” - see Appendix A).
 */
class PCLEscapeGenericSimple {
  function getSequenceString() {
    return $this->_getEscapedCharacter();
  }

}

/**
 * Parameterized escape sequences have the following form:
 * 
 * <Escape> X y z1 # z2 # z3 ... # Zn[data]
 * 
 * where  y,  #,  zi (z1,  z2,  z3...)  and  [data] may  be  optional,
 * depending on the command.
 */
class PCLEscapeGenericParametric {
  function getSequenceString() {
    $result = 
      $this->_getEscapedCharacter().
      $this->_getGroupCharacter();
    $groups = $this->_getGroups();
    $size = count($groups);
    for ($i=0; $i<$size-1; $i++) {
      $result .= $groups[$i]->getString();
    };
    $result .= $groups[$size-1]->getStringTerminate();
    return $result;
  }
}

class PCLEscapeGroup {
  var $_value;
  var $_character;

  function PCLEscapeGroup($char, $value) {
    $this->_character = $char;
    $this->_value     = $value;
  }

  function getString() {
    return $this->_value.$this->_character;
  }

  function getStringTerminate() {
    return $this->_value.strtoupper($this->_character);
  }
}

/**
 * Simple escape sequences
 */

/**
 * Printer Reset command
 *
 * Receipt  of the  Printer Reset  command restores  the  User Default
 * Environment, deletes  temporary fonts, macros,  user-defined symbol
 * sets and patterns.  It also prints any partial  pages of data which
 * may have been received.
 * 
 * <Escape> E
 */
class PCLEscapeReset extends PCLEscapeGenericSimple {
  function _getEscapedCharacter() { return "E"; }
}

/**
 * The Universal  Exit Language (UEL)  command causes the  PCL printer
 * language to  shut down  and exit. Control  is then returned  to the
 * Printer Job Language  (PJL). Both PCL 5 and  HP-GL/2 recognize this
 * command.
 *
 * <Escape> % – 1 2 3 4 5 X
 *
 * Default = N/A
 * Range = –12345
 * This command performs the following actions:
 * .. Prints all data received before the Exit Language command.
 * .. Performs a printer reset (same effect as ? E).
 * .. Shuts down the PCL 5 printer language processor.
 * .. Turns control over to PJL.
 */
class PCLEscapeUEL extends PCLEscapeGenericParametric {
  function _getEscapedCharacter() { return "%"; }
  function _getGroupCharacter() { return ""; }
  function _getGroups() {
    return array(new PCLEscapeGroup('x',-12345));
  }
}

/**
 * The Number of Copies command designates the number of printed copies of each page.
 * 
 * <Escape> & l # X
 *
 * # = Number of copies (1 to 32767 maximum)
 * Default = 1 (Configurable from control panel)
 * Range = 1-32767
 * (Values 32767 execute as 32767 values 1 are ignored.
 * Maximum number of copies=99 for LaserJet II, IIP, III, IIID, IIIP and earlier LaserJet printers.)
 *
 * This command can be received anywhere within a page and affects
 * the current page as well as subsequent pages.
 */
class PCLEscapeNumberOfCopies extends PCLEscapeGenericParametric {
  var $_number;

  function PCLEscapeNumberOfCopies($number) {
    $this->_number = $number;
  }

  function _getEscapedCharacter() { return "&"; }
  function _getGroupCharacter() { return "l"; }
  function _getGroups() {
    return array(new PCLEscapeGroup('x',$this->_number));
  }
}

/**
 * This command designates either  simplex or duplex printing mode for
 * duplex printers. Simplex mode prints an image on only one side of a
 * sheet (page). Duplex mode prints images on both sides of a sheet.
 *
 * ? & l # S
 *
 * # = 0 - Simplex
 * 1 - Duplex, Long-Edge Binding
 * 2 - Duplex, Short-Edge Binding
 * Default = 0
 * Range = 0-2 (Other values ignored)
 *
 * Long-Edge bound  duplexed pages are  bound along the length  of the
 * physical page (see Figure 4-2). Short-edge bound duplexed pages are
 * bound  along the  width  of  the physical  page  (see Figure  4-3).
 * Selecting long-edge binding usually  results in font rotation. This
 * may be a concern if available user memory is critical.
 */
define('PCL_DUPLEX');

class PCLEscapeSimplexDuplex extends PCLEscapeGenericParametric {
  var $_duplex;

  function PCLEscapeSimplexDuplex($duplex) {
    $this->_duplex = $duplex;
  }

  function _getEscapedCharacter() { return "&"; }
  function _getGroupCharacter() { return "l"; }
  function _getGroups() {
    return array(new PCLEscapeGroup('x',$this->_number));
  }
}

/**
 * Print Job
 *
 * Structure of a Typical Job
 * <Escape>%–12345X UEL Command (exit language)
 * <Escape>E Printer Reset Command.
 * Preamble Job Control Commands.
 * Page 1 Page Control Commands.
 * Data
 * Page 2 Page Control Commands.1
 * Data.
 * ...
 * Page n Page Control Commands.
 * Data.
 * <Escape>E Printer Reset Command.
 * <Escape>%–12345X UEL Command (exit language).
 */
class PCLPrintJob {
  function output(&$stream) {
    $uel = new PCLEscapeUEL();
    $reset  = new PCLEscapeReset();

    $uel->output($stream);
    $reset->output($stream);
    $this->_preamble->output($stream);
    foreach ($this->_pages as $page) {
      $page->output($stream);
    };
    $reset->output($stream);
    $uel->output($stream);
  }
}

class PCLPrintJobPreamble {
  function output(&$stream) {
    // TODO
  }
}

class PCLPrintJobPage {
  var $_control
  var $_data;

  function output(&$stream) {
    $this->_control->output($stream);
    $this->_data->output($stream);
  }
}

class OutputDriverPCL extends OutputDriverGeneric {

  /**
   * Standard output driver interface follows
   */

  function add_link($x, $y, $w, $h, $target) { /* N/A */ }
  function add_local_link($left, $top, $width, $height, $anchor) { /* N/A */ }
  function circle($x, $y, $r) { }
  function clip() {}
  function close() { die("Unoverridden 'close' method called in ".get_class($this)); }
  function closepath() {}
  function content_type() { die("Unoverridden 'content_type' method called in ".get_class($this));  }
  function dash($x, $y) { }
  function decoration($underline, $overline, $strikeout) { }
  function error_message() { die("Unoverridden 'error_message' method called in ".get_class($this)); }

  function field_multiline_text($x, $y, $w, $h, $value, $field_name) { /* N/A */ }
  function field_text($x, $y, $w, $h, $value, $field_name) { /* N/A */ }
  function field_password($x, $y, $w, $h, $value, $field_name) { /* N/A */ }
  function field_pushbutton($x, $y, $w, $h) { /* N/A */ }
  function field_pushbuttonimage($x, $y, $w, $h, $field_name, $value, $actionURL) { /* N/A */ }
  function field_pushbuttonreset($x, $y, $w, $h) { /* N/A */ }
  function field_pushbuttonsubmit($x, $y, $w, $h, $field_name, $value, $actionURL) { /* N/A */ }
  function field_checkbox($x, $y, $w, $h, $name, $value) { /* N/A */ }
  function field_radio($x, $y, $w, $h, $groupname, $value, $checked) { /* N/A */ }
  function field_select($x, $y, $w, $h, $name, $value, $options) { /* N/A */ }

  function fill() { }
  function font_ascender($name, $encoding) {}
  function font_descender($name, $encoding) {}
  function get_bottom() {}
  function image($image, $x, $y, $scale) {}
  function image_scaled($image, $x, $y, $scale_x, $scale_y) { }
  function image_ry($image, $x, $y, $height, $bottom, $ox, $oy, $scale) { }
  function image_rx($image, $x, $y, $width, $right, $ox, $oy, $scale) { }
  function image_rx_ry($image, $x, $y, $width, $height, $right, $bottom, $ox, $oy, $scale) { }
  function lineto($x, $y) { }
  function moveto($x, $y) { }
  function new_form($name) { /* N/A */ }
  function next_page() { /* N/A */ }
  function release() { }
  function restore() { }
  function save() { }
  function setfont($name, $encoding, $size) {}
  function setlinewidth($x) { }
  function setrgbcolor($r, $g, $b)  { }
  function set_watermark($text) { }
  function show_xy($text, $x, $y) {}
  function stringwidth($string, $name, $encoding, $size) { }
  function stroke() { }
}
?>