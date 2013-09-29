<?php
// $Header: /cvsroot/html2ps/media.layout.inc.php,v 1.16 2007/05/07 12:15:53 Konstantin Exp $

$GLOBALS['g_predefined_media'] = array();
$GLOBALS['g_media'] = null;

// TODO: check for validity
function add_predefined_media($name, $height, $width) {
  global $g_predefined_media;
  $g_predefined_media[$name] = array('height' => $height, 'width' => $width);
}

class Media {
  var $margins;
  var $size;
  var $pixels;
  var $is_landscape;

  /**
   * @param Array $size associative array with 'height' and 'width' keys (mm)
   * @param Array $margins associative array with 'top', 'bottom', 'left' and 'right' keys (mm)
   */
  function Media($size, $margins) {
    $this->size    = $size;
    $this->margins = $margins;
    $this->pixels  = 800;
  }

  function &copy() {
    $new_item =& new Media($this->size, $this->margins);
    $new_item->pixels = $this->pixels;
    return $new_item;
  }

  function doInherit() {
  }

  function get_width() {
    return $this->is_landscape ? $this->size['height'] : $this->size['width'] ;
  }

  function width()  { 
    return $this->get_width();
  }

  function get_height() {
    return $this->height();
  }

  function height() { 
    return $this->is_landscape ? $this->size['width']  : $this->size['height']; 
  }

  function real_width() {
    return $this->width() - $this->margins['left'] - $this->margins['right'];
  }
  
  function real_height() { 
    return $this->height() - $this->margins['bottom'] - $this->margins['top'];
  }

  function set_height($height) {
    $this->size['height'] = $height;
  }

  function set_landscape($state) {
    $this->is_landscape = (bool)$state;
  }

  // TODO: validity checking
  function set_margins($margins) {
    $this->margins = $margins;
  }

  function set_pixels($pixels) {
    $this->pixels = $pixels;
  }

  function set_width($width) {
    $this->size['width'] = $width;
  }
 
  // TODO: validity checking
  function &predefined($name) {
    global $g_predefined_media;

    // Let's check if the chosen media defined
    if (isset($g_predefined_media[$name])) {
      $media =& new Media($g_predefined_media[$name], array('top'=>0, 'bottom'=>0, 'left'=>0, 'right'=>0));
    } else {
      $media = null;
    };

    return $media;
  }

  /**
   * Pixels per millimeter
   */
  function PPM() { 
    return $this->pixels / ($this->size['width'] - $this->margins['left'] - $this->margins['right']);
  }

  function to_bbox() {
    return '0 0 '.ceil(mm2pt($this->size['width'])).' '.ceil(mm2pt($this->size['height']));
  }

  function to_ps_landscape() {
    if (!$this->is_landscape) { return "/initpage {} def"; };
    return "/initpage {90 rotate 0 pageheight neg translate} def";
  }

  function to_ps() {
    return 
      // Note that /pagewidth and /pageheight should contain page size on the "client"
      // coordinate system for correct rendering, so the will swap place in landscape mode,
      // while /width and height set in PageSize should have the real media values, because
      // actual coordinate system rotation/offset is done by the /initpage command without
      // actually ratating the media.
      "/pagewidth  {".$this->width()." mm} def\n".
      "/pageheight {".$this->height()." mm} def\n".
      "/lmargin    {{$this->margins['left']} mm} def\n".
      "/rmargin    {{$this->margins['right']} mm} def\n".
      "/tmargin    {{$this->margins['top']} mm} def\n".
      "/bmargin    {{$this->margins['bottom']} mm} def\n".
      "/px {pagewidth lmargin sub rmargin sub {$this->pixels} div mul} def\n".
      "<< /PageSize [".$this->size['width']." mm ".$this->size['height']." mm] >> setpagedevice\n".
      $this->to_ps_landscape();
  }
}

?>