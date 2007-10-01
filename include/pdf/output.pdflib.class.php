<?php
// $Header: /cvsroot/html2ps/output.pdflib.class.php,v 1.9 2006/04/16 16:54:58 Konstantin Exp $

class PDFLIBForm {
  var $_name;
//   var $submit_action;
//   var $reset_action;

  function PDFLIBForm($name /*, $submit_action, $reset_action */) {
    $this->_name          = $name;
//     $this->submit_action = $submit_action;
//     $this->reset_action  = $reset_action;
  }

  function name() {
    return $this->_name;
  }
}

class OutputDriverPdflib extends OutputDriverGenericPDF {
  var $pdf;

  /**
   * @var ? Contains the PDFLIB handle of currently selected PDF font
   * @access private
   */
  var $_currentfont;
  var $_forms;
  var $_field_names;

  var $_radiogroups;
  var $_watermark;

  function add_link($left, $top, $width, $height, $url) {
    pdf_add_weblink($this->pdf, $left, $top-$height, $left+$width, $top, $url);
  }

  function add_local_link($left, $top, $width, $height, $anchor) {
    pdf_add_locallink($this->pdf, 
                      $left, 
                      $top-$height - $this->offset , 
                      $left+$width, 
                      $top - $this->offset, 
                      $anchor->page, 
                      "fitwidth");
  }

  function circle($x, $y, $r) { 
    pdf_circle($this->pdf, $x, $y, $r); 
  }

  function clip() {
    pdf_clip($this->pdf);
  }

  function close() {
    $this->_show_watermark();
    pdf_end_page($this->pdf);
    pdf_close($this->pdf); 
    pdf_delete($this->pdf);
  }

  function closepath() { 
    pdf_closepath($this->pdf); 
  }

  function dash($x, $y) {
    pdf_setdash($this->pdf, $x, $y); 
  }

  function decoration($underline, $overline, $strikeout) {
    // underline
    pdf_set_parameter($this->pdf, "underline", $underline ? "true" : "false");
    // overline
    pdf_set_parameter($this->pdf, "overline",  $overline  ? "true" : "false");
    // line through
    pdf_set_parameter($this->pdf, "strikeout", $strikeout ? "true" : "false");
  }

  // Converts common encoding names to their PDFLIB equivalents 
  // (for example, PDFLIB does not understand iso-8859-1 encoding name,
  // but have its equivalent names winansi..)
  //
  function encoding($encoding) {
    $encoding = trim(strtolower($encoding));

    $translations = array(
                          'iso-8859-1'   => 'winansi',
                          'iso-8859-2'   => 'iso8859-2',
                          'iso-8859-3'   => 'iso8859-3',
                          'iso-8859-4'   => 'iso8859-4',
                          'iso-8859-5'   => 'iso8859-5',
                          'iso-8859-6'   => 'iso8859-6',
                          'iso-8859-7'   => 'iso8859-7',
                          'iso-8859-8'   => 'iso8859-8',
                          'iso-8859-9'   => 'iso8859-9',
                          'iso-8859-10'  => 'iso8859-10',
                          'iso-8859-13'  => 'iso8859-13',
                          'iso-8859-14'  => 'iso8859-14',
                          'iso-8859-15'  => 'iso8859-15',
                          'iso-8859-16'  => 'iso8859-16',
                          'windows-1250' => 'cp1250',
                          'windows-1251' => 'cp1251',
                          'windows-1252' => 'cp1252',
                          'symbol'       => 'symbol'
                          );

    if (isset($translations[$encoding])) { return $translations[$encoding]; };
    return $encoding;
  }

  function field_multiline_text($x, $y, $w, $h, $value, $name) { 
    $font = $this->_control_font();
    pdf_create_field($this->pdf,
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($name),
                     "textfield",
                     sprintf("currentvalue {%s} defaultvalue {%s} font {%s} fontsize {auto} multiline {true}", 
                             $value,
                             $value,
                             $font));    
  }

  function field_text($x, $y, $w, $h, $value, $name) {
    $font = $this->_control_font();
    pdf_create_field($this->pdf,
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($name),
                     "textfield",
                     sprintf("currentvalue {%s} defaultvalue {%s} font {%s} fontsize {auto}", 
                             $value, 
                             $value,
                             $font));
  }

  function field_password($x, $y, $w, $h, $value, $name) {
    $font = $this->_control_font();
    pdf_create_field($this->pdf,
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($name),
                     "textfield",
                     sprintf("currentvalue {%s} font {%s} fontsize {auto} password {true}", $value, $font));
  }

  function field_pushbutton($x, $y, $w, $h) {
    $font = $this->_control_font();
   
    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn(sprintf("___Button%s",md5(time().rand()))),
                     "pushbutton",
                     sprintf("font {%s} fontsize {auto} caption {%s}", 
                             $font, 
                             " "));
  }

  function field_pushbuttonimage($x, $y, $w, $h, $field_name, $value, $actionURL) {
    $font = $this->_control_font();

    $action = pdf_create_action($this->pdf,
                                "SubmitForm",
                                sprintf("exportmethod {html} url=%s", $actionURL));
    
    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($field_name),
                     "pushbutton",
                     sprintf("action {activate %s} font {%s} fontsize {auto} caption {%s}", 
                             $action, 
                             $font, 
                             " "));
  }

  function field_pushbuttonreset($x, $y, $w, $h) {
    $font = $this->_control_font();

    $action = pdf_create_action($this->pdf,
                                "ResetForm",
                                sprintf(""));
    
    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn(sprintf("___ResetButton%d",$action)),
                     "pushbutton",
                     sprintf("action {activate %s} font {%s} fontsize {auto} caption {%s}", 
                             $action, 
                             $font, 
                             " "));
  }

  function field_pushbuttonsubmit($x, $y, $w, $h, $field_name, $value, $actionURL) {
    $font = $this->_control_font();

    $action = pdf_create_action($this->pdf,
                                "SubmitForm",
                                sprintf("exportmethod {html} url=%s", $actionURL));
    
    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($field_name),
                     "pushbutton",
                     sprintf("action {activate %s} font {%s} fontsize {auto} caption {%s}", 
                             $action, 
                             $font, 
                             " "));
  }

  function field_checkbox($x, $y, $w, $h, $name, $value, $checked) {
    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($name),
                     "checkbox",
                     sprintf("buttonstyle {cross} currentvalue {%s} defaultvalue {%s} itemname {%s}", 
                             $checked ? $value : "Off",
                             $checked ? $value : "Off",
                             $value));    
  }

  function field_radio($x, $y, $w, $h, $groupname, $value, $checked) {
    $fqgn = $this->_fqn($groupname, true);

    if (!isset($this->_radiogroups[$fqgn])) {
      $this->_radiogroups[$fqgn] = pdf_create_fieldgroup($this->pdf, $fqgn, "fieldtype=radiobutton");
    };

    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     sprintf("%s.%s",$fqgn,$value),
                     "radiobutton",
                     sprintf("buttonstyle {circle} currentvalue {%s} defaultvalue {%s} itemname {%s}", 
                             $checked ? $value : "Off",
                             $checked ? $value : "Off",
                             $value));    
  }

  function field_select($x, $y, $w, $h, $name, $value, $options) { 
    $items_str = "";
    $text_str  = "";
    foreach ($options as $option) {
      $items_str .= sprintf("%s ",$option[0]);
      $text_str  .= sprintf("%s ",$option[1]);
    };

    $font = $this->_control_font();
    pdf_create_field($this->pdf,
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($name),
                     "combobox",
                     sprintf("currentvalue {%s} defaultvalue {%s} font {%s} fontsize {auto} itemnamelist {%s} itemtextlist {%s}", 
                             $value,
                             $value,
                             $font,
                             $items_str, 
                             $text_str));
  }

  function fill() { 
    pdf_fill($this->pdf); 
  }

  function findfont($name, $encoding) { 
    // PDFLIB is limited by 'builtin' encoding for "Symbol" font
    if ($name == 'Symbol') { $encoding = 'builtin'; };

    global $g_font_resolver_pdf;
    $embed = $g_font_resolver_pdf->embed[$name];
    return pdf_findfont($this->pdf, $name, $encoding, $embed); 
  }

  function font_ascender($name, $encoding) { 
    return pdf_get_value($this->pdf, "ascender", $this->findfont($name, $encoding));
  }

  function font_descender($name, $encoding) { 
    return -pdf_get_value($this->pdf, "descender", $this->findfont($name, $encoding));
  }

  function get_bottom() {
    return $this->bottom + $this->offset;
  }

  function image($image, $x, $y, $scale) {
    $tmpname = tempnam(WRITER_TEMPDIR,WRITER_FILE_PREFIX);
    imagepng($image, $tmpname);
    $pim = pdf_open_image_file($this->pdf, "png", $tmpname, "", 0);
    pdf_place_image($this->pdf, $pim, $x, $y, $scale);
    pdf_close_image($this->pdf, $pim);
    unlink($tmpname);
  }

  function image_scaled($image, $x, $y, $scale_x, $scale_y) {
    $tmpname = tempnam(WRITER_TEMPDIR,WRITER_FILE_PREFIX);
    imagepng($image, $tmpname);

    $pim = pdf_open_image_file($this->pdf, "png", $tmpname, "", 0);

    $this->save();
    pdf_translate($this->pdf, $x, $y);
    pdf_scale($this->pdf, $scale_x, $scale_y);
    pdf_place_image($this->pdf, $pim, 0, 0, 1);
    $this->restore();

    pdf_close_image($this->pdf, $pim);
    unlink($tmpname);
  }

  function image_ry($image, $x, $y, $height, $bottom, $ox, $oy, $scale) {
    $tmpname = tempnam(WRITER_TEMPDIR,WRITER_FILE_PREFIX);
    imagepng($image, $tmpname);
    $pim = pdf_open_image_file($this->pdf, "png", $tmpname, "", 0);

    // Fill part to the bottom
    $cy = $y;
    while ($cy+$height > $bottom) {
      pdf_place_image($this->pdf, $pim, $x, $cy, $scale);
      $cy -= $height;
    };

    // Fill part to the top
    $cy = $y;
    while ($cy-$height < $y + $oy) {
      pdf_place_image($this->pdf, $pim, $x, $cy, $scale);
      $cy += $height;
    };

    pdf_close_image($this->pdf, $pim);
    unlink($tmpname);
  }

  function image_rx($image, $x, $y, $width, $right, $ox, $oy, $scale) {
    $tmpname = tempnam(WRITER_TEMPDIR,WRITER_FILE_PREFIX);
    imagepng($image, $tmpname);
    $pim = pdf_open_image_file($this->pdf, "png", $tmpname, "", 0);

    // Fill part to the right 
    $cx = $x;
    while ($cx < $right) {
      pdf_place_image($this->pdf, $pim, $cx, $y, $scale);
      $cx += $width;
    };

    // Fill part to the left
    $cx = $x;
    while ($cx+$width >= $x - $ox) {
      pdf_place_image($this->pdf, $pim, $cx-$width, $y, $scale);
      $cx -= $width;
    };

    pdf_close_image($this->pdf, $pim);
    unlink($tmpname);
  }

  function image_rx_ry($image, $x, $y, $width, $height, $right, $bottom, $ox, $oy, $scale) {
    $tmpname = tempnam(WRITER_TEMPDIR,WRITER_FILE_PREFIX);
    imagepng($image, $tmpname);
    $pim = pdf_open_image_file($this->pdf, "png", $tmpname, "", 0);

    // Fill bottom-right quadrant
    $cy = $y;
    while ($cy+$height > $bottom) {
      $cx = $x;
      while ($cx < $right) {
        pdf_place_image($this->pdf, $pim, $cx, $cy, $scale);
        $cx += $width;
      };
      $cy -= $height;
    }

    // Fill bottom-left quadrant
    $cy = $y;
    while ($cy+$height > $bottom) {
      $cx = $x;
      while ($cx+$width > $x - $ox) {
        pdf_place_image($this->pdf, $pim, $cx, $cy, $scale);
        $cx -= $width;
      };
      $cy -= $height;
    }

    // Fill top-right quadrant
    $cy = $y;
    while ($cy < $y + $oy) {
      $cx = $x;
      while ($cx < $right) {
        pdf_place_image($this->pdf, $pim, $cx, $cy, $scale);
        $cx += $width;
      };
      $cy += $height;
    }

    // Fill top-left quadrant
    $cy = $y;
    while ($cy < $y + $oy) {
      $cx = $x;
      while ($cx+$width > $x - $ox) {
        pdf_place_image($this->pdf, $pim, $cx, $cy, $scale);
        $cx -= $width;
      };
      $cy += $height;
    }

    pdf_close_image($this->pdf, $pim);
    unlink($tmpname);
  }

  function lineto($x, $y) { 
    pdf_lineto($this->pdf, $x, $y); 
  }

  function moveto($x, $y) { 
    pdf_moveto($this->pdf, $x, $y); 
  }

  function new_form($name) {
//     $submit_image_action = pdf_create_action($this->pdf,
//                                              "SubmitForm",
//                                              "export-method=html,coordinate");
    
//     $reset_action = pdf_create_action($this->pdf,
//                                       "ResetForm");

    $this->_forms[] = new PDFLIBForm($name);

    pdf_create_fieldgroup($this->pdf, $name, "fieldtype=mixed");
  }

  // OutputDriver interface functions
  function next_page() {
    $this->_show_watermark();

    $this->current_page ++;

    pdf_end_page($this->pdf);
    pdf_begin_page($this->pdf, mm2pt($this->media->width()), mm2pt($this->media->height()));
    
    // Calculate coordinate of the next page bottom edge
    $this->offset -= $this->height - $this->offset_delta;

    // Reset the "correction" offset to it normal value
    // Note: "correction" offset is an offset value required to avoid page breaking 
    // in the middle of text boxes 
    $this->offset_delta = 0;

    pdf_translate($this->pdf, 0, -$this->offset);
  }

  function OutputDriverPdflib($version) {
    $this->OutputDriverGenericPDF();
    $this->set_pdf_version($version);

    $this->_currentfont = null;
    $this->_radiogroups = array();
    $this->_field_names = array();
  }

  function reset(&$media) {
    OutputDriverGenericPDF::reset($media);

    // Check if PDFLIB is available
    if (!extension_loaded('pdf')) {

      // Try to use "dl" to dynamically load PDFLIB
      $result = dl(PDFLIB_DL_PATH);

      if (!$result) {
        readfile(HTML2PS_DIR.'/templates/missing_pdflib.html');
        error_log("No PDFLIB extension found");
        die();
      }
    }

    $this->pdf = pdf_new();

    // Set PDF compatibility level
    pdf_set_parameter($this->pdf, "compatibility", $this->get_pdf_version());

    /**
     * Use PDF license key, if present
     *
     * PDFLIB_LICENSE constant is defined in 'config.inc.php' file in "PDFLIB-specific" section.
     */
    if (defined("PDFLIB_LICENSE")) {
      pdf_set_parameter($this->pdf, "license", PDFLIB_LICENSE);
    };

    pdf_open_file($this->pdf, $this->get_filename());

    // @TODO: compression level, debug
    pdf_set_value($this->pdf, "compress", 0);

    // Set path to the PDFLIB UPR file containig information about fonts and encodings
    if (defined("PDFLIB_UPR_PATH")) {
      pdf_set_parameter($this->pdf, "resourcefile", PDFLIB_UPR_PATH); 
    };
    
    // Setup font outlines
    global $g_font_resolver_pdf;
    $g_font_resolver_pdf->setup_ttf_mappings($this->pdf);

    $pdf = $this->pdf;
    pdf_set_info($pdf, "Creator", "html2ps (PHP version)");

    // No borders around links in the generated PDF
    pdf_set_border_style($this->pdf, "solid", 0);

    pdf_begin_page($this->pdf, mm2pt($this->media->width()), mm2pt($this->media->height()));
  }

  function rect($x, $y, $w, $h) { 
    pdf_rect($this->pdf, $x, $y, $w, $h); 
  }

  function restore() { 
    pdf_restore($this->pdf); 
  }

  function save() { 
    pdf_save($this->pdf); 
  }

  function setfont($name, $encoding, $size) {
    $this->_currentfont = $this->findfont($name, $encoding);

    pdf_setfont($this->pdf, $this->_currentfont, $size);

    return true;
  }

//   function setfontcore($name, $size) {
//     $this->_currentfont = pdf_findfont($this->pdf, $name, 'host', 1 /* embed */); 

//     pdf_setfont($this->pdf, $this->_currentfont, $size);

//     return true;
//   }

  function setlinewidth($x) { 
    pdf_setlinewidth($this->pdf, $x); 
  }

  // PDFLIB wrapper functions
  function setrgbcolor($r, $g, $b)  { 
    pdf_setcolor($this->pdf, "both", "rgb", $r, $g, $b, 0); 
  }

  function show_xy($text, $x, $y) {
    pdf_show_xy($this->pdf, $text, $x, $y);
  }

  function stroke() { 
    pdf_stroke($this->pdf); 
  }

  function stringwidth($string, $name, $encoding, $size) { 
    return pdf_stringwidth($this->pdf, $string, $this->findfont($name, $encoding), $size); 
  }

  function set_watermark($watermark) {
    $this->_watermark = trim($watermark);
  }

  /* private routines */

  function _control_font() {
    return pdf_load_font($this->pdf, "Helvetica", "winansi", "embedding=true subsetting=false");
  }

  function _lastform() {
    if (count($this->_forms) == 0) {
      /**
       * Handle invalid HTML; if we've met an input control outside the form, 
       * generate a new form with random name
       */
      
      $name = sprintf("AnonymousFormObject_%u", md5(rand().time()));

      $this->_forms[] = new PDFLIBForm($name);
      pdf_create_fieldgroup($this->pdf, $name, "fieldtype=mixed");
      
      error_log(sprintf("Anonymous form generated with name %s; check your HTML for validity", 
                        $name));
    };

    return $this->_forms[count($this->_forms)-1];
  }

  function _valid_name($name) {
    if (empty($name)) { return false; };

    return true;
  }

  function _fqn($name, $allowexisting=false) {
    if (!$this->_valid_name($name)) {
      $name = uniqid("AnonymousFormFieldObject_");
      error_log(sprintf("Anonymous field generated with name %s; check your HTML for validity", 
                        $name));
    };

    $lastform = $this->_lastform();
    $fqn = sprintf("%s.%s",
                   $lastform->name(),
                   $name);

    if (array_search($fqn, $this->_field_names) === FALSE) {
      $this->_field_names[] = $fqn;
    } elseif (!$allowexisting) {
      error_log(sprintf("Interactive form '%s' already contains field named '%s'",
                        $lastform->name(),
                        $name));
      $fqn .= md5(rand().time());
    };

    return $fqn;
  }

  function _show_watermark() {
    if (is_null($this->_watermark) || $this->_watermark == "") { return; };

    $font = $this->_control_font();
    pdf_setfont($this->pdf, $font, 100);
    
    $x = $this->left + $this->width / 2;
    $y = $this->bottom + $this->height / 2 + $this->offset;

    pdf_set_value($this->pdf, "textrendering", 1);
    pdf_translate($this->pdf, $x, $y);
    pdf_rotate($this->pdf, 60);
    pdf_show_xy($this->pdf, $this->_watermark, -pdf_stringwidth($this->pdf, $this->_watermark, $font, 100)/2, -50);
  }
}
?>