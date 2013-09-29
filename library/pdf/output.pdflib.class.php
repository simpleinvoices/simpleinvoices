<?php
// $Header: /cvsroot/html2ps/output.pdflib.class.php,v 1.18 2007/05/17 13:55:13 Konstantin Exp $

define('PDFLIB_STATUS_INITIALIZED', 0);
define('PDFLIB_STATUS_DOCUMENT_STARTED', 1);
define('PDFLIB_STATUS_PAGE_STARTED', 2);

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

  var $_status;

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

  function fill() { 
    pdf_fill($this->pdf); 
  }

  function findfont($name, $encoding) { 
    // PDFLIB is limited by 'builtin' encoding for "Symbol" font
    if ($name == 'Symbol') { 
      $encoding = 'builtin'; 
    };

    global $g_font_resolver_pdf;
    $embed = $g_font_resolver_pdf->embed[$name];
    return pdf_findfont($this->pdf, $name, $this->encoding($encoding), $embed); 
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

  // OutputDriver interface functions
  function next_page($height) {
    if ($this->_status == PDFLIB_STATUS_PAGE_STARTED) {
      pdf_end_page($this->pdf);
    };
    pdf_begin_page($this->pdf, mm2pt($this->media->width()), mm2pt($this->media->height()));
    
    // Calculate coordinate of the next page bottom edge
    $this->offset -= $height - $this->offset_delta;

    // Reset the "correction" offset to it normal value
    // Note: "correction" offset is an offset value required to avoid page breaking 
    // in the middle of text boxes 
    $this->offset_delta = 0;

    pdf_translate($this->pdf, 0, -$this->offset);

    parent::next_page($height);

    $this->_status = PDFLIB_STATUS_PAGE_STARTED;
  }

  function OutputDriverPdflib($version) {
    $this->OutputDriverGenericPDF();
    $this->set_pdf_version($version);

    $this->_currentfont = null;
    $this->_radiogroups = array();
    $this->_field_names = array();

    $this->_status = PDFLIB_STATUS_INITIALIZED;
  }

  function prepare() {
    parent::prepare();

    $filename = $this->generate_cpg('custom', true);
    pdf_set_parameter($this->pdf, 'Encoding', sprintf('custom=%s', $filename));
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
        die("HTML2PS Error");
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

    // Setup encodings not bundled with PDFLIB
    $filename = $this->generate_cpg('koi8-r');
    pdf_set_parameter($this->pdf, 'Encoding', sprintf('koi8-r=%s', $filename));
    
    // Setup font outlines
    global $g_font_resolver_pdf;
    $g_font_resolver_pdf->setup_ttf_mappings($this->pdf);

    $pdf = $this->pdf;
    pdf_set_info($pdf, "Creator", "html2ps (PHP version)");

    // No borders around links in the generated PDF
    pdf_set_border_style($this->pdf, "solid", 0);

    $this->_status = PDFLIB_STATUS_DOCUMENT_STARTED;
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

  /* private routines */

  function _show_watermark($watermark) {
    $font = $this->findfont('Helvetica', 'iso-8859-1');
    pdf_setfont($this->pdf, $font, 100);
    
    $x = $this->left + $this->width / 2;
    $y = $this->bottom + $this->height / 2 + $this->offset;

    pdf_set_value($this->pdf, "textrendering", 1);
    pdf_translate($this->pdf, $x, $y);
    pdf_rotate($this->pdf, 60);
    pdf_show_xy($this->pdf, $watermark, -pdf_stringwidth($this->pdf, $watermark, $font, 100)/2, -50);
  }

  function generate_cpg($encoding, $force = false) {
    $filename = CACHE_DIR.$encoding.'.cpg';
    if (file_exists($filename) && !$force) {
      return $filename;
    };

    $output = fopen($filename, 'w');
    $manager_encoding =& ManagerEncoding::get();
    $vector = $manager_encoding->getEncodingVector($encoding);
    foreach ($vector as $code => $utf) {
      fwrite($output, sprintf("0x%04X 0x%02X\n", $utf, ord($code)));
    };
    fclose($output);

    return $filename;
  }
}
?>