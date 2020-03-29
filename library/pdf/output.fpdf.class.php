<?php
// $Header: /cvsroot/html2ps/output.fpdf.class.php,v 1.27 2007/05/17 13:55:13 Konstantin Exp $

require_once(HTML2PS_DIR.'pdf.fpdf.php');
require_once(HTML2PS_DIR.'pdf.fpdf.makefont.php');
// require_once(HTML2PS_DIR.'fpdf/font/makefont/makefont.php');

class OutputDriverFPDF extends OutputDriverGenericPDF {
  var $pdf;
  var $locallinks;
  var $cx;
  var $cy;

  function OutputDriverFPDF() {
    $this->OutputDriverGenericPDF();   
  }

  function add_link($x, $y, $w, $h, $target) {
    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_link_external($x, $y, $w, $h, $target);
  }

  function add_local_link($left, $top, $width, $height, $anchor) {
    if (!isset($this->locallinks[$anchor->name])) {
      $x = 0;
      $y = $anchor->y;
      $this->_coords2pdf($x, $y);

      $this->locallinks[$anchor->name] = $this->pdf->AddLink();
      $this->pdf->SetLink($this->locallinks[$anchor->name],
                          $y - 20,
                          $anchor->page);
    };

    $x = $left;
    $y = $top - $this->offset;
    $this->_coords2pdf($x, $y);
    
    $this->pdf->add_link_internal($x, 
                                  $y, 
                                  $width, 
                                  $height, 
                                  $this->locallinks[$anchor->name]);
  }

  // UNfortunately, FPDF do not provide any coordinate-space transformation routines
  // so we need to reverse the Y-axis manually
  function _coords2pdf(&$x, &$y) {
    $y = mm2pt($this->media->height()) - $y;
  }

  // Annotation coordinates are always interpreted in the default (untranslated!) 
  // user space. (See PDF Reference 1.6 Section 8.4 p.575)
  function _coords2pdf_annotation(&$x, &$y) {
    $y = $y - $this->offset;
    $this->_coords2pdf($x, $y);
  }

  function decoration($underline, $overline, $strikeout) {
    // underline
    $this->pdf->SetDecoration($underline, $overline, $strikeout);
  }

  function circle($x, $y, $r) { 
    $this->pdf->circle($x, $y, $r);
  }

  function clip() { 
    $this->pdf->Clip();
  }

  function close() {
    $this->pdf->Output($this->get_filename());
  }

  function closepath() {
    $this->pdf->closepath();
  }

  function dash($x, $y) { 
    $this->pdf->SetDash(ceil($x), ceil($y)); 
  }

  function get_bottom() {
    return $this->bottom + $this->offset;
  }

  function field_multiline_text($x, $y, $w, $h, $value, $field_name) { 
    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_field_multiline_text($x, $y, $w, $h, $value, $field_name);
  }

  function field_text($x, $y, $w, $h, $value, $field_name) {
    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_field_text($x, $y, $w, $h, $value, $field_name);
  }

  function field_password($x, $y, $w, $h, $value, $field_name) {
    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_field_password($x, $y, $w, $h, $value, $field_name);
  }

  function field_pushbutton($x, $y, $w, $h) {
    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_field_pushbutton($x, $y, $w, $h);
  }

  function field_pushbuttonimage($x, $y, $w, $h, $field_name, $value, $actionURL) {
    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_field_pushbuttonimage($x, $y, $w, $h, $field_name, $value,  $actionURL);
  }

  function field_pushbuttonreset($x, $y, $w, $h) {
    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_field_pushbuttonreset($x, $y, $w, $h);
  }

  function field_pushbuttonsubmit($x, $y, $w, $h, $field_name, $value, $actionURL) {
    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_field_pushbuttonsubmit($x, $y, $w, $h, $field_name, $value,  $actionURL);
  }

  function field_checkbox($x, $y, $w, $h, $name, $value, $checked) {
    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_field_checkbox($x, $y, $w, $h, $name, $value, $checked);
  }

  function field_radio($x, $y, $w, $h, $groupname, $value, $checked) {
    static $generated_group_index = 0;
    if (is_null($groupname)) {
      $generated_group_index ++;
      $groupname = "__generated_group_".$generated_group_index;
    };

    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_field_radio($x, $y, $w, $h, $groupname, $value, $checked);
  }

  function field_select($x, $y, $w, $h, $name, $value, $options) { 
    $this->_coords2pdf_annotation($x, $y);
    $this->pdf->add_field_select($x, $y, $w, $h, $name, $value, $options);
  }

  function fill() { 
    $this->pdf->Fill();
  }

  function findfont($name, $encoding) { 
    // Todo: encodings handling
    return $name;
  }

  function font_ascender($name, $encoding) { 
    return $this->pdf->GetFontAscender($name, $encoding);
  }

  function font_descender($name, $encoding) { 
    return $this->pdf->GetFontDescender($name, $encoding);
  }

  function image($image, $x, $y, $scale) {
    $tmpname = $this->_mktempimage($image);

    $this->_coords2pdf($x, $y);
    $this->pdf->Image($tmpname, 
                      $x, 
                      $y - imagesy($image) * $scale, 
                      imagesx($image) * $scale, 
                      imagesy($image) * $scale);
    unlink($tmpname);
  }

  function image_rx($image, $x, $y, $width, $right, $ox, $oy, $scale) {
    $tmpname = $this->_mktempimage($image);

    // Fill part to the right 
    $cx = $x;
    while ($cx < $right) {
      $tx = $cx;
      $ty = $y + px2pt(imagesy($image)); 
      $this->_coords2pdf($tx, $ty);
      $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
      $cx += $width;
    };

    // Fill part to the left
    $cx = $x;
    while ($cx+$width >= $x - $ox) {
      $tx = $cx-$width;
      $ty = $y + px2pt(imagesy($image)); 
      $this->_coords2pdf($tx, $ty);
      $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
      $cx -= $width;
    };

    unlink($tmpname);
  }

  function image_rx_ry($image, $x, $y, $width, $height, $right, $bottom, $ox, $oy, $scale) {
    $tmpname = $this->_mktempimage($image);

    // Fill bottom-right quadrant
    $cy = $y;
    while ($cy+$height > $bottom) {
      $cx = $x;
      while ($cx < $right) {
        $tx = $cx;
        $ty = $cy+$height;
        $this->_coords2pdf($tx, $ty);

        $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
        $cx += $width;
      };
      $cy -= $height;
    }

    // Fill bottom-left quadrant
    $cy = $y;
    while ($cy+$height > $bottom) {
      $cx = $x;
      while ($cx+$width > $x - $ox) {
        $tx = $cx;
        $ty = $cy;
        $this->_coords2pdf($tx, $ty);
        $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
        $cx -= $width;
      };
      $cy -= $height;
    }

    // Fill top-right quadrant
    $cy = $y;
    while ($cy < $y + $oy) {
      $cx = $x;
      while ($cx < $right) {
        $tx = $cx;
        $ty = $cy;
        $this->_coords2pdf($tx, $ty);
        $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
        $cx += $width;
      };
      $cy += $height;
    }

    // Fill top-left quadrant
    $cy = $y;
    while ($cy < $y + $oy) {
      $cx = $x;
      while ($cx+$width > $x - $ox) {
        $tx = $cx;
        $ty = $cy;
        $this->_coords2pdf($tx, $ty);
        $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
        $cx -= $width;
      };
      $cy += $height;
    }

    unlink($tmpname);
  }


  function image_ry($image, $x, $y, $height, $bottom, $ox, $oy, $scale) {
    $tmpname = $this->_mktempimage($image);

    // Fill part to the bottom
    $cy = $y;
    while ($cy+$height > $bottom) {
      $tx = $x;
      $ty = $cy + px2pt(imagesy($image)); 
      $this->_coords2pdf($tx, $ty);
      $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
      $cy -= $height;
    };

    // Fill part to the top
    $cy = $y;
    while ($cy-$height < $y + $oy) {
      $tx = $x;
      $ty = $cy + px2pt(imagesy($image)); 
      $this->_coords2pdf($tx, $ty);
      $this->pdf->Image($tmpname, $tx, $ty, imagesx($image) * $scale, imagesy($image) * $scale, "png");
      $cy += $height;
    };

    unlink($tmpname);
  }

  function image_scaled($image, $x, $y, $scale_x, $scale_y) {
    $tmpname = $this->_mktempimage($image);

    $this->_coords2pdf($x, $y);
    $this->pdf->Image($tmpname, $x, $y - imagesy($image) * $scale_y, imagesx($image) * $scale_x, imagesy($image) * $scale_y, "png");
    unlink($tmpname);
  }

  function lineto($x, $y) { 
    $this->_coords2pdf($x, $y);
    $this->pdf->lineto($x, $y);
  }

  function moveto($x, $y) {  
    $this->_coords2pdf($x, $y);
    $this->pdf->moveto($x, $y);
  }

  function new_form($name) {
    $this->pdf->add_form($name);
  }

  function next_page($height) {
    $this->pdf->AddPage(mm2pt($this->media->width()), mm2pt($this->media->height()));
    
    // Calculate coordinate of the next page bottom edge
    $this->offset -= $height - $this->offset_delta;

    // Reset the "correction" offset to it normal value
    // Note: "correction" offset is an offset value required to avoid page breaking 
    // in the middle of text boxes 
    $this->offset_delta = 0;

    $this->pdf->Translate(0, -$this->offset);

    parent::next_page($height);
  }

  function reset(&$media) {
    parent::reset($media);   

    $this->pdf =& new FPDF('P','pt',array(mm2pt($media->width()), mm2pt($media->height())));

    if (defined('DEBUG_MODE')) {
      $this->pdf->SetCompression(false);
    } else {
      $this->pdf->SetCompression(true);
    };

    $this->cx = 0;
    $this->cy = 0;

    $this->locallinks = array();
  }

  function restore() { 
    $this->pdf->Restore();
  }

  function save() { 
    $this->pdf->Save();
  }

  function setfont($name, $encoding, $size) {
    $this->pdf->SetFont($this->findfont($name, $encoding), $encoding, $size);

    return true;
  }

  function setlinewidth($x) { 
    $this->pdf->SetLineWidth($x); 
  }

  // PDFLIB wrapper functions
  function setrgbcolor($r, $g, $b)  { 
    $this->pdf->SetDrawColor($r*255, $g*255, $b*255);
    $this->pdf->SetFillColor($r*255, $g*255, $b*255);
    $this->pdf->SetTextColor($r*255, $g*255, $b*255);
  }

  function show_xy($text, $x, $y) {
    $this->_coords2pdf($x, $y);

    $this->pdf->Text($x, $y, $text);
  }

  function stroke() { 
    $this->pdf->stroke();
  }

  function stringwidth($string, $name, $encoding, $size) { 
    $this->setfont($name, $encoding, $size);
    $width = $this->pdf->GetStringWidth($string);
    return $width;
  }

  function _show_watermark($watermark) {
    $this->pdf->SetFont("Helvetica", "iso-8859-1", 100);

    $x = $this->left + $this->width / 2;
    $y = $this->bottom  + $this->height / 2 - $this->offset;
    $this->_coords2pdf($x, $y);

    $this->pdf->SetTextRendering(1);
    $this->pdf->SetDecoration(false, false, false);
    $this->pdf->Translate($x, $y);
    $this->pdf->Rotate(60);

    $tx = -$this->pdf->GetStringWidth($watermark)/2;
    $ty = -50;
    $this->_coords2pdf($tx, $ty);

    // By default, "watermark" is rendered in black color
    $this->setrgbcolor(0,0,0);

    $this->pdf->Text($tx, 
                     $ty, 
                     $watermark);
  }

  function _mktempimage($image) {   
    $filename = tempnam(WRITER_TEMPDIR,WRITER_FILE_PREFIX);
    imagepng($image, $filename);
    return $filename;
  }
}
?>