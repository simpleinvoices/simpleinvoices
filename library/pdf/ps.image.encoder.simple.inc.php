<?php

require_once(HTML2PS_DIR.'ps.image.encoder.stream.inc.php');

/**
 * Deprecated. Big. Slow. Causes /limitcheck Ghostcript error on big images. Use
 * another encoder.
 * @author Konstantin Bournayev
 * @version 1.0
 * @updated 24-џэт-2006 21:18:30
 */
class PSImageEncoderSimple extends PSImageEncoderStream {
  function PSImageEncoderSimple() {
  }

  function auto($psdata, $src_img, &$size_x, &$size_y, &$tcolor, &$image, &$mask) {
    if (imagecolortransparent($src_img) == -1) {
      $id = $this->solid($psdata, $src_img, $size_x, $size_y, $image, $mask);
      $tcolor = 0;
      return $id;
    } else {
      $id = $this->transparent($psdata, $src_img, $size_x, $size_y, $image, $mask);
      $tcolor = 1;
      return $id;
    };
  }
  
  function solid($psdata, $src_img, &$size_x, &$size_y, &$image, &$mask) {
    $id = $this->generate_id();

    $size_x      = imagesx($src_img); 
    $size_y      = imagesy($src_img);
    $dest_img    = imagecreatetruecolor($size_x, $size_y);
    
    imagecopymerge($dest_img, $src_img, 0, 0, 0, 0, $size_x, $size_y, 100);
    
    $ps_image_data = "";
    $ctr = 1; $row = 1;
    
    for ($y = 0; $y < $size_y; $y++) {
      for ($x = 0; $x < $size_x; $x++) {
        // Image pixel
        $rgb = ImageColorAt($dest_img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF; 
        $ps_image_data .= sprintf("\\%03o\\%03o\\%03o",$r,$g,$b);

        // Write image rows
        $ctr++;
        if ($ctr > MAX_IMAGE_ROW_LEN || ($x + 1 == $size_x)) {
          $row_next = ($size_x - $x - 1 + $size_x * ($size_y - $y - 1) == 0) ? 1 : $row+1;
          $psdata->write("/row-{$id}-{$row} { /image-{$id}-data { row-{$id}-{$row_next} } def ({$ps_image_data}) } def\n");

          $ps_image_data = "";
          $ctr = 1; $row += 1;        
        };
      };
    };

    if ($ps_image_data) {
      $psdata->write("/row-{$id}-{$row}  { /image-{$id}-data { row-{$id}-1 } def ({$ps_image_data}) } def\n");
    };

    $psdata->write("/image-{$id}-data { row-{$id}-1 } def\n");
    $psdata->write("/image-{$id}-init { } def\n");

    // return image and mask data references
    $image = "{image-{$id}-data}";
    $mask  = "";

    return $id;
  }

  function transparent($psdata, $src_img, &$size_x, &$size_y, &$image, &$mask) {
    $id = $this->generate_id();

    $size_x      = imagesx($src_img); 
    $size_y      = imagesy($src_img);
    $transparent = imagecolortransparent($src_img);
    $dest_img    = imagecreatetruecolor($size_x, $size_y);

    imagecopymerge($dest_img, $src_img, 0, 0, 0, 0, $size_x, $size_y, 100);

    $ps_image_data = "";
    $ps_mask_data  = 0xff;
    $ctr = 1; $row = 1;

    $handler =& CSS::get_handler(CSS_BACKGROUND_COLOR);
    $background_color = $handler->get_visible_background_color();

    for ($y = 0; $y < $size_y; $y++) {
      for ($x = 0; $x < $size_x; $x++) {
        // Image pixel
        $rgb = ImageColorAt($dest_img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF; 

        // Mask pixel
        if (ImageColorAt($src_img, $x, $y) == $transparent) { 
          $ps_mask_data = ($ps_mask_data << 1) | 0x1;
          // Also, reset the image colors to the visible background to work correctly 
          // while using 'transparency hack'
          $r = $background_color[0];
          $g = $background_color[1];
          $b = $background_color[2];
        } else {
          $ps_mask_data = ($ps_mask_data << 1) | 0;
        };

        $ps_image_data .= sprintf("\\%03o\\%03o\\%03o",$r,$g,$b);

        // Write mask and image rows
        $ctr++;
        if ($ctr > MAX_TRANSPARENT_IMAGE_ROW_LEN || ($x + 1 == $size_x)) {
          while ($ctr <= 8) {
            $ps_mask_data <<= 1;
            $ps_mask_data |= 1;
            $ctr ++;
          };

          $ps_mask_data_str = sprintf("\\%03o",$ps_mask_data & 0xff); 

          $row_next = ($size_x - $x - 1 + $size_x * ($size_y - $y - 1) == 0) ? 1 : $row+1;

          $psdata->write("/row-{$id}-{$row} { /image-{$id}-data { row-{$id}-{$row_next} } def ({$ps_image_data}) } def\n");
          $psdata->write("/mrow-{$id}-{$row} { /mask-{$id}-data { mrow-{$id}-{$row_next} } def ({$ps_mask_data_str}) } def\n");

          $ps_image_data = "";
          $ps_mask_data  = 0xff;
          $ctr = 1; $row += 1;        
        };
      };
    };

    if ($ps_image_data) {
      while ($ctr <= 8) {
        $ps_mask_data <<= 1;
        $ps_mask_data |= 1;
        $ctr ++;
      };
      $ps_mask_data_str = sprintf("\\%03o",$ps_mask_data & 0xFF);

      $psdata->write("/row-{$id}-{$row} { /image-{$id}-data { row-{$id}-{$row_next} } def ({$ps_image_data}) } def\n");
      $psdata->write("/mrow-{$id}-{$row} { /mask-{$id}-data { mrow-{$id}-{$row_next} } def ({$ps_mask_data_str}) } def\n");
    };

    $psdata->write("/image-{$id}-data { row-{$id}-1 } def\n");
    $psdata->write("/mask-{$id}-data  { mrow-{$id}-1 } def\n");
    $psdata->write("/image-{$id}-init { } def\n");

    // return image and mask data references
    $image = "{image-{$id}-data}";
    $mask  = "{mask-{$id}-data}";

    return $id;
  }

  function alpha($psdata, $src_img, &$size_x, &$size_y, &$image, &$mask) {
    $id = $this->generate_id();

    $size_x      = imagesx($src_img); 
    $size_y      = imagesy($src_img);

    $ps_image_data = "";
    $ps_mask_data  = 0xff;
    $ctr = 1; $row = 1;

    for ($y = 0; $y < $size_y; $y++) {
      for ($x = 0; $x < $size_x; $x++) {
        // Mask pixel
        $colors = imagecolorsforindex($src_img, imagecolorat($src_img, $x, $y));

        $a = $colors['alpha']; 
        $r = $colors['red'];
        $g = $colors['green'];
        $b = $colors['blue'];

        $handler =& CSS::get_handler(CSS_BACKGROUND_COLOR);
        $bg = $handler->get_visible_background_color();
        $r = (int)($r + ($bg[0] - $r)*$a/127);
        $g = (int)($g + ($bg[1] - $g)*$a/127);
        $b = (int)($b + ($bg[2] - $b)*$a/127);

        $ps_image_data .= sprintf("\\%03o\\%03o\\%03o",$r,$g,$b);

        // Write mask and image rows
        $ctr++;
        if ($ctr > MAX_IMAGE_ROW_LEN || ($x + 1 == $size_x)) {
          $row_next = ($size_x - $x - 1 + $size_x * ($size_y - $y - 1) == 0) ? 1 : $row+1;

          $psdata->write("/row-{$id}-{$row} { /image-{$id}-data { row-{$id}-{$row_next} } def ({$ps_image_data}) } def\n");

          $ps_image_data = "";
          $ctr = 1; $row += 1;        
        };
      };
    };

    if ($ps_image_data) {
      $psdata->write("/row-{$id}-{$row} { /image-{$id}-data { row-{$id}-{$row_next} } def ({$ps_image_data}) } def\n");
    };

    $psdata->write("/image-{$id}-data { row-{$id}-1 } def\n");
    $psdata->write("/image-{$id}-init { } def\n");

    // return image and mask data references
    $image = "{image-{$id}-data}";
    $mask  = "";

    return $id;
  }

}
?>