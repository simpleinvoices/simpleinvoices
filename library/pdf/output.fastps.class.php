<?php
// $Header: /cvsroot/html2ps/output.fastps.class.php,v 1.18 2007/05/17 13:55:13 Konstantin Exp $

define('FASTPS_STATUS_DOCUMENT_INITIALIZED',0);
define('FASTPS_STATUS_OUTPUT_STARTED',1);
define('FASTPS_STATUS_OUTPUT_TERMINATED',2);

class OutputDriverFastPS extends OutputDriverGenericPS {
  var $found_fonts;
  var $used_encodings;
  var $font_factory;
  var $status;

  var $overline;
  var $underline;
  var $linethrough;

  function OutputDriverFastPS(&$image_encoder) { 
    $this->OutputDriverGenericPS($image_encoder);
  }

  function add_link($x, $y, $w, $h, $target) { 
    $this->write(sprintf("[ /Rect [ %.2f %.2f %.2f %.2f ] /Action << /Subtype /URI /URI (%s) >> /Border [0 0 0] /Subtype /Link /ANN pdfmark\n",
                         $x, $y, $x+$w, $y-$h, $this->_string($target)));
  }

  function add_local_link($left, $top, $width, $height, $anchor) { 
    $this->write(sprintf("[ /Rect [ %.2f %.2f %.2f %.2f ] /Page %d /View [ /XYZ null %.2f null ] /Border [0 0 0] /Subtype /Link /ANN pdfmark\n",
                         $left, $top, $left + $width, $top - $height, $anchor->page, $anchor->y));
  }

  function circle($x, $y, $r) { 
    $this->moveto($x, $y);
    $this->write(sprintf("%.2f %.2f %.2f 0 360 arc\n", $x, $y, $r));
  }

  function clip() {
    $this->write("clip newpath\n");
  }

  function close() {
    if ($this->status != FASTPS_STATUS_OUTPUT_STARTED) { 
      return; 
    }
    $this->_terminate_output();

    fclose($this->data);
  }

  function closepath() {
    $this->write("closepath\n");
  }

  function dash($x, $y) { 
    $this->write(sprintf("[%.2f %.2f] 0 setdash\n", $x, $y));
  }
  
  function decoration($underline, $overline, $linethrough) {
    $this->underline   = $underline;
    $this->overline    = $overline;
    $this->linethrough = $linethrough;
  }
  
  function fill() { 
    $this->write("fill\n");
  }
  
  function _findfont($name, $encoding) {
    $font =& $this->font_factory->get_type1($name, $encoding);
    if (is_null($font)) {
      $this->error_message .= $this->font_factory->error_message();
      $dummy = null;
      return $dummy;
    };

    if (!isset($this->used_encodings[$encoding])) {
      $this->used_encodings[$encoding] = true;
      
      $manager = ManagerEncoding::get();
      $this->_write_document_prolog($manager->get_ps_encoding_vector($encoding));
      $this->_write_document_prolog("\n");
    };

    $fontname = $font->name();
    if (!isset($this->found_fonts[$fontname])) {
      $this->found_fonts[$fontname] = true;

      $this->_write_document_prolog("/$fontname /$name $encoding findfont-enc def\n");
    };

    return $font;
  }

  // @return 'null' in case of error or ascender fraction of font-size
  //
  function font_ascender($name, $encoding) {
    $font = $this->_findfont($name, $encoding);
    if (is_null($font)) { return null; };

    return $font->ascender()/1000;
  }

  // @return 'null' in case of error or ascender fraction of font-size
  //
  function font_descender($name, $encoding) {
    $font = $this->_findfont($name, $encoding);
    if (is_null($font)) { return null; };

    return -$font->descender()/1000;
  }

  function get_bottom() {
    return $this->bottom + $this->offset;
  }

  function &get_font_resolver() {
    global $g_font_resolver;
    return $g_font_resolver;
  }

  function image($image, $x, $y, $scale) {
    $image_encoder = $this->get_image_encoder();
    $id = $image_encoder->auto($this, $image, $size_x, $size_y, $tcolor, $image, $mask);
    $init = "image-".$id."-init";

    $this->moveto($x, $y);
    $this->write(sprintf("%.2f %.2f %s %s {%s} %d %d image-create image-show\n",
                         $size_x * $scale,
                         $size_y * $scale,
                         ($mask !== "" ? $mask : "/null"),
                         $image,
                         $init, 
                         $size_y, 
                         $size_x));
  }

  function image_scaled($image, $x, $y, $scale_x, $scale_y) { 
    $image_encoder = $this->get_image_encoder();
    $id = $image_encoder->auto($this, $image, $size_x, $size_y, $tcolor, $image, $mask);
    $init = "image-".$id."-init";

    $this->moveto($x, $y);
    $this->write(sprintf("%.2f %.2f %s %s {%s} %d %d image-create image-show\n",
                         $size_x * $scale_x ,
                         $size_y * $scale_y, 
                         ($mask !== "" ? $mask : "/null"),
                         $image,
                         $init, 
                         $size_y, 
                         $size_x));
  }

  function image_ry($image, $x, $y, $height, $bottom, $ox, $oy, $scale) { 
    $image_encoder = $this->get_image_encoder();
    $id = $image_encoder->auto($this, $image, $size_x, $size_y, $tcolor, $image, $mask);
    $init = "image-".$id."-init";

    $this->write(sprintf("%.2f %.2f %.2f %.2f %.2f %.2f %.2f %s %s {%s} %d %d image-create image-show-repeat-y\n",
                         $scale, $oy, $ox, $bottom, $height, $y, $x,
                         ($mask !== "" ? $mask : "/null"),
                         $image,
                         $init, 
                         $size_y, 
                         $size_x));
  }
  
  function image_rx($image, $x, $y, $width, $right, $ox, $oy, $scale) { 
    $image_encoder = $this->get_image_encoder();
    $id = $image_encoder->auto($this, $image, $size_x, $size_y, $tcolor, $image, $mask);
    $init = "image-".$id."-init";

    $this->write(sprintf("%.2f %.2f %.2f %.2f %.2f %.2f %.2f %s %s {%s} %d %d image-create image-show-repeat-x\n",
                         $scale, $oy, $ox, $right, $width, $y, $x,
                         ($mask !== "" ? $mask : "/null"),
                         $image,
                         $init, 
                         $size_y, 
                         $size_x));
  }

  function image_rx_ry($image, $x, $y, $width, $height, $right, $bottom, $ox, $oy, $scale) { 
    $image_encoder = $this->get_image_encoder();
    $id = $image_encoder->auto($this, $image, $size_x, $size_y, $tcolor, $image, $mask);
    $init = "image-".$id."-init";

    $this->write(sprintf("%.2f %.2f %.2f %.2f %.2f %.2f %.2f  %.2f %.2f %s %s {%s} %d %d image-create image-show-repeat-xy\n",
                         $scale, $oy, $ox, $bottom, $right, $height, $width, $y, $x,
                         ($mask !== "" ? $mask : "/null"),
                         $image,
                         $init, 
                         $size_y, 
                         $size_x));
  }

  function lineto($x, $y) { 
    $data = sprintf("%.2f %.2f lineto\n", $x, $y);
    $this->write($data);
  }

  function moveto($x, $y) { 
    $data = sprintf("%.2f %.2f moveto\n", $x, $y);
    $this->write($data);
  }

  function next_page($height) {
    if ($this->current_page > 0) {
      $this->write("showpage\n");
    };

    $this->offset -= $height - $this->offset_delta;

    // Reset the "correction" offset to it normal value
    // Note: "correction" offset is an offset value required to avoid page breaking 
    // in the middle of text boxes 
    $this->offset_delta = 0;

    $this->write(sprintf("%%%%Page: %d %d\n", $this->current_page + 1, $this->current_page + 1));
    $this->write("%%BeginPageSetup\n");
    $this->write(sprintf("initpage\n"));
    $this->write(sprintf("0 %.2f translate\n", -$this->offset));
    $this->write("0 0 0 setrgbcolor\n");
    $this->write("%%EndPageSetup\n");

    parent::next_page($height);
  }

  function reset(&$media) { 
    OutputDriverGenericPS::reset($media);

    $this->media =& $media;
    $this->data = fopen($this->get_filename(), "wb");

    // List of fonts names which already had generated findfond PS code
    $this->found_fonts = array();

    $this->used_encodings = array();

    $this->overline = false;
    $this->underline = false;
    $this->linethrough = false;

    // A font class factory
    $this->font_factory =& new FontFactory;

    $this->_document_body = '';
    $this->_document_prolog = '';

    $this->status = FASTPS_STATUS_DOCUMENT_INITIALIZED;
  }

  function restore() { 
    $this->write("grestore\n");
  }

  function save() { 
    $this->write("gsave\n");
  }

  // @return true normally or null in case of error
  //
  function setfont($name, $encoding, $size) {
    $this->fontsize    = $size;
    $this->currentfont = $this->_findfont($name, $encoding);

    if (is_null($this->currentfont)) { return null; };

    $this->write(sprintf("%s %.2f scalefont setfont\n", $this->currentfont->name(), $size));

    return true;
  }

  function setlinewidth($x) { 
    $data = sprintf("%.2f setlinewidth\n", $x);
    $this->write($data);
  }

  function setrgbcolor($r, $g, $b)  { 
    $data = sprintf("%.2f %.2f %.2f setrgbcolor\n", $r, $g, $b);
    $this->write($data);
  }

  function show_xy($text, $x, $y) {
    if (trim($text) !== '') { 
      $this->moveto($x, $y);
      $this->write("(".$this->_string($text).") show\n");
    };
      
    $width = Font::points($this->fontsize, $this->currentfont->stringwidth($text));
    if ($this->overline)    { $this->_show_overline($x, $y, $width, $this->fontsize);  };
    if ($this->underline)   { $this->_show_underline($x, $y, $width, $this->fontsize); };
    if ($this->linethrough) { $this->_show_linethrough($x, $y, $width, $this->fontsize); };
  }

  function stringwidth($string, $name, $encoding, $size) { 
    $font =& $this->font_factory->get_type1($name, $encoding);

    if (is_null($font)) {
      $this->error_message .= $this->font_factory->error_message();
      $dummy = null;
      return $dummy;
    };

    return Font::points($size, $font->stringwidth($string));
  }
  
  function stroke() { 
    $this->write("stroke\n");
  }

  function write($string) {
    if ($this->status == FASTPS_STATUS_DOCUMENT_INITIALIZED) { 
      $this->_start_output(); 
    };

    $this->_document_body .= $string;
  }

  function _write_document_prolog($string) {
    $this->_document_prolog .= $string;
  }

  function _show_line($x, $y, $width, $height, $up, $ut) {
    $this->setlinewidth($ut);
    $this->moveto($x, $y + $up);
    $this->lineto($x+$width, $y + $up);
    $this->stroke();
  }

  function _show_underline($x, $y, $width, $height) {
    $up = Font::points($this->fontsize, $this->currentfont->underline_position());
    $ut = Font::points($this->fontsize, $this->currentfont->underline_thickness());
    
    $this->_show_line($x, $y, $width, $height, $up, $ut);
  }

  function _show_overline($x, $y, $width, $height) {
    $up = Font::points($this->fontsize, $this->currentfont->overline_position());
    $ut = Font::points($this->fontsize, $this->currentfont->underline_thickness());
    
    $this->_show_line($x, $y, $width, $height, $up, $ut);
  }

  function _show_linethrough($x, $y, $width, $height) {
    $up = Font::points($this->fontsize, $this->currentfont->linethrough_position());
    $ut = Font::points($this->fontsize, $this->currentfont->underline_thickness());
    
    $this->_show_line($x, $y, $width, $height, $up, $ut);
  }

  function _start_output() {
    $this->status = FASTPS_STATUS_OUTPUT_STARTED;
  }

  function _terminate_output() {
    /**
     * Prepare the PS file header
     * Note that %PS-Adobe-3.0 refers to DSC version, NOT language level
     */
    $header = file_get_contents(HTML2PS_DIR.'/postscript/fastps.header.ps');

    global $g_config;
    $header = preg_replace("/##PS2PDF##/",
                           ($g_config['ps2pdf'] && $g_config['transparency_workaround']) ? "/ps2pdf-transparency-hack true def" : "/ps2pdf-transparency-hack false def",$header);
    $header = preg_replace("/##TRANSPARENCY##/",($g_config['transparency_workaround']) ? "/no-transparency-output true def" : "/no-transparency-output false def",$header);
    $header = preg_replace("/##PAGES##/", $this->expected_pages, $header);

    $header = preg_replace("/##BBOX##/", $this->media->to_bbox(), $header);
    $header = preg_replace("/##MEDIA##/", $this->media->to_ps(), $header);

    $header = preg_replace("/##PROLOG##/", $this->_document_prolog, $header);

    fwrite($this->data, $header);   
    fwrite($this->data, "\n");
    fwrite($this->data, $this->_document_body);

    $footer = file_get_contents(HTML2PS_DIR.'/postscript/fastps.footer.ps');
    fwrite($this->data, $footer);
  }

  function _show_watermark() {
  }

  /**
   * Protected output-specific methods
   */

  /**
   * Escapes special Postscript symbols '(',')' and '%' inside a text string 
   */
  function _string($str) {
    $str = str_replace("\\", "\\\\", $str);
    $str = str_replace(array("(",")","%"), array("\\(","\\)","\\%"), $str);
    
    // Replace characters having 8-bit set with their octal representation
    for ($i=0; $i<strlen($str); $i++) {
      if (ord($str{$i}) > 127) {
        $str = substr_replace($str, sprintf("\\%o", ord($str{$i})), $i, 1);
        $i += 3;
      };
    };
    
    return $str;
  }
}

?>