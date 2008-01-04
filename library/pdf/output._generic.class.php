<?php
// $Header: /cvsroot/html2ps/output._generic.class.php,v 1.5 2006/05/27 15:33:27 Konstantin Exp $

class OutputDriverGeneric extends OutputDriver {
  var $media;
  var $bottom;
  var $left;
  var $width;
  var $height;

  var $_watermark;

  // Offset (in device points) of the current page from the first page.
  // Can be treated as coordinate of the bottom page edge (as first page 
  // will have zero Y value at its bottom).
  // Note that ir is PAGE edge coordinate, NOT PRINTABLE AREA! If you want to get
  // the position of the lowest pixel on the page which won't be cut-off, use
  // $offset+$bottom expression, as $bottom contains bottom white margin size
  var $offset;

  var $expected_pages;
  var $current_page;

  var $filename;

  // Properties

  var $debug_boxes;
  var $show_page_border;

  var $error_message;

  function error_message() { 
    return $this->error_message;
  }

  /**
   * Checks if a given box should be drawn on the current page.
   * Basically, box should be drawn if its top or bottom edge is "inside" the page "viewport"
   * 
   * @param GenericBox $box Box we're using for check
   * @return boolean flag indicating of any part of this box should be placed on the current page
   */
  function contains(&$box) {
    /**
     * These two types of boxes are not visual and 
     * may have incorrect position
     */
    if (is_a($box, "TableSectionBox")) { return true; };
    if (is_a($box, "TableRowBox")) { return true; };

    $top    = round($box->get_top(),2);
    $bottom = round($box->get_bottom(),2);

    /**
     * Note: 
     *
     * Y-axis is directed to the top
     *
     * Y-coordinate of bottom page edge = $offset
     * Y-coordinate of bottom edge of "viewport" = $offset + mm2pt($this->media->margins['bottom'])
     *
     * Y-coordinate of top page edge = $offset + mm2pt($this->media->height())
     * Y-coordinate of top page edge = $offset + mm2pt($this->media->height()) - mm2pt($this->media->margins['top'])
     */

    $vp_top    = round($this->offset + mm2pt($this->media->height() - $this->media->margins['top']),2);
    $vp_bottom = round($this->offset + mm2pt($this->media->margins['bottom']),2);
            
    return ($top > $vp_bottom && 
            $bottom <= $vp_top); 
  }

  function default_encoding() {
    return $this->encoding('iso-8859-1');
  }

  function draw_page_border() {
    $this->setlinewidth(1);
    $this->setrgbcolor(0,0,0);

    $this->moveto($this->left, $this->bottom + $this->offset);
    $this->lineto($this->left, $this->bottom + $this->height + $this->offset);
    $this->lineto($this->left + $this->width, $this->bottom + $this->height + $this->offset);
    $this->lineto($this->left + $this->width, $this->bottom + $this->offset);
    $this->closepath();
    $this->stroke();
  }

  function get_expected_pages() {
    return $this->expected_pages;
  }

  function mk_filename() {
    // Check if we can use tempnam to create files (so, we have PHP version
    // with fixed bug it this function behaviour and open_basedir/environment
    // variables are not maliciously set to move temporary files out of open_basedir
    // In general, we'll try to create these files in ./temp subdir of current 
    // directory, but it can be overridden by environment vars both on Windows and
    // Linux
    $filename   = tempnam(WRITER_TEMPDIR,WRITER_FILE_PREFIX);
    $filehandle = @fopen($filename, "wb");
    // Now, if we have had any troubles, $filehandle will be 
    if ($filehandle === false) {
      // Note: that we definitely need to unlink($filename); - tempnam just created it for us! 
      // but we can't ;) because of open_basedir (or whatelse prevents us from opening it)

      // Fallback to some stupid algorithm of filename generation
      $tries = 0;
      do {
        $filename   = WRITER_TEMPDIR.WRITER_FILE_PREFIX.md5(uniqid(rand(), true));
        // Note: "x"-mode prevents us from re-using existing files
        // But it require PHP 4.3.2 or later
        $filehandle = @fopen($filename, "xb");
        $tries++;
      } while (!$filehandle && $tries < WRITER_RETRIES);
    };

    if (!$filehandle) {
      die(WRITER_CANNOT_CREATE_FILE);
    };
    // Release this filehandle - we'll reopen it using some gzip wrappers 
    // (if they are available)
    fclose($filehandle);

    // Remove temporary file we've just created during testing
    unlink($filename);

    return $filename;
  }

  function get_filename() { return $this->filename; }

  function &get_font_resolver() {
    global $g_font_resolver_pdf;
    return $g_font_resolver_pdf;
  }

  function is_debug_boxes() { 
    return $this->debug_boxes;
  }

  function is_show_page_border() {
    return $this->show_page_border;
  }

  function rect($x, $y, $w, $h) { 
    $this->moveto($x, $y);
    $this->lineto($x + $w, $y);
    $this->lineto($x + $w, $y + $h);
    $this->lineto($x, $y + $h);
    $this->closepath();
  }

  function set_debug_boxes($debug) {
    $this->debug_boxes = $debug;
  }

  function set_expected_pages($num) {
    $this->expected_pages = $num;
  }

  function set_filename($filename) { 
    $this->filename = $filename; 
  }

  function set_show_page_border($show) {
    $this->show_page_border = $show;
  }

  function setup_clip() {
    $this->moveto($this->left, $this->bottom + $this->offset);
    $this->lineto($this->left, $this->bottom + $this->height + $this->offset);
    $this->lineto($this->left + $this->width, $this->bottom + $this->height + $this->offset);
    $this->lineto($this->left + $this->width, $this->bottom + $this->offset);
    $this->clip();
  }

  function OutputDriverGeneric() {
    // Properties setup
    $this->set_debug_boxes(false);
    $this->set_filename($this->mk_filename());
    $this->set_show_page_border(false);
  }

  function reset(&$media) {
    $this->media  = $media;
    $this->width  = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']);
    $this->height = mm2pt($media->height() - $media->margins['top'] - $media->margins['bottom']);
    $this->left   = mm2pt($media->margins['left']);
    $this->bottom = mm2pt($media->margins['bottom']);
    $this->offset = 0;
    $this->offset_delta = 0;
    $this->expected_pages = 0;
    $this->current_page = 1;
  }

  function set_watermark($watermark) {
    $this->_watermark = $watermark;
  }

}
?>