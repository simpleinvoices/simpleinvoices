<?php

require_once(HTML2PS_DIR.'ps.image.encoder.stream.inc.php');

class PSL3ImageEncoderStream extends PSImageEncoderStream {
  function PSL3ImageEncoderStream() {
    $this->last_image_id = 0;
  }

  function auto(&$psdata, $src_img, &$size_x, &$size_y, &$tcolor, &$image, &$mask) {
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

  // Encodes "solid" image without any transparent parts
  // 
  // @param $psdata (in) Postscript file "writer" object
  // @param $src_img (in) PHP image resource
  // @param $size_x (out) size of image in pixels
  // @param $size_y (out) size of image in pixels
  // @returns identifier if encoded image to use in postscript file
  // 
  function solid(&$psdata, $src_img, &$size_x, &$size_y, &$image, &$mask) {
    // Generate an unique image id
    $id = $this->generate_id();

    // Determine image size and create a truecolor copy of this image 
    // (as we don't want to work with palette-based images manually)
    $size_x      = imagesx($src_img); 
    $size_y      = imagesy($src_img);
    $dest_img    = imagecreatetruecolor($size_x, $size_y);
    imagecopymerge($dest_img, $src_img, 0, 0, 0, 0, $size_x, $size_y, 100);
    
    // write stread header to the postscript file
    $psdata->write("/image-{$id}-init { image-{$id}-data 0 setfileposition } def\n");
    $psdata->write("/image-{$id}-data currentfile << /Filter /ASCIIHexDecode >> /ReusableStreamDecode filter\n");

    // initialize line length counter
    $ctr = 0;
    
    for ($y = 0; $y < $size_y; $y++) {
      for ($x = 0; $x < $size_x; $x++) {
        // Save image pixel to the stream data
        $rgb = ImageColorAt($dest_img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF; 
        $psdata->write(sprintf("%02X%02X%02X",min(max($r,0),255),min(max($g,0),255),min(max($b,0),255)));

        // Increate the line length counter; check if stream line needs to be terminated
        $ctr += 6;
        if ($ctr > MAX_LINE_LENGTH) { 
          $psdata->write("\n");
          $ctr = 0;
        }
      };
    };

    // terminate the stream data
    $psdata->write(">\ndef\n");

    // return image and mask data references
    $image = "image-{$id}-data";
    $mask  = "";

    return $id;
  }

  // Encodes image containing 100% transparent color (1-bit alpha channel)
  // 
  // @param $psdata (in) Postscript file "writer" object
  // @param $src_img (in) PHP image resource
  // @param $size_x (out) size of image in pixels
  // @param $size_y (out) size of image in pixels
  // @returns identifier if encoded image to use in postscript file
  // 
  function transparent(&$psdata, $src_img, &$size_x, &$size_y, &$image, &$mask) {
    // Generate an unique image id
    $id = $this->generate_id();

    // Store transparent color for further reference
    $transparent = imagecolortransparent($src_img);

    // Determine image size and create a truecolor copy of this image 
    // (as we don't want to work with palette-based images manually)
    $size_x      = imagesx($src_img); 
    $size_y      = imagesy($src_img);
    $dest_img    = imagecreatetruecolor($size_x, $size_y);
    imagecopymerge($dest_img, $src_img, 0, 0, 0, 0, $size_x, $size_y, 100);
    
    // write stread header to the postscript file
    $psdata->write("/image-{$id}-init { image-{$id}-data 0 setfileposition mask-{$id}-data 0 setfileposition } def\n");

    // Create IMAGE data stream
    $psdata->write("/image-{$id}-data currentfile << /Filter /ASCIIHexDecode >> /ReusableStreamDecode filter\n");

    // initialize line length counter
    $ctr = 0;
    
    for ($y = 0; $y < $size_y; $y++) {
      for ($x = 0; $x < $size_x; $x++) {
        // Save image pixel to the stream data
        $rgb = ImageColorAt($dest_img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF; 

        $psdata->write(sprintf("%02X%02X%02X",$r,$g,$b));

        // Increate the line length counter; check if stream line needs to be terminated
        $ctr += 6;
        if ($ctr > MAX_LINE_LENGTH) { 
          $psdata->write("\n");
          $ctr = 0;
        }
      };
    };

    // terminate the stream data
    $psdata->write(">\ndef\n");

    // Create MASK data stream
    $psdata->write("/mask-{$id}-data currentfile << /Filter /ASCIIHexDecode >> /ReusableStreamDecode filter\n");

    // initialize line length counter
    $ctr = 0;

    // initialize mask bit counter
    $bit_ctr = 0;
    $mask_data = 0xff;
    
    for ($y = 0; $y < $size_y; $y++) {
      for ($x = 0; $x < $size_x; $x++) {
        // Check if this pixel should be transparent
        if (ImageColorAt($src_img, $x, $y) == $transparent) {
          $mask_data = ($mask_data << 1) | 0x1;
        } else {
          $mask_data = ($mask_data << 1);
        };
        $bit_ctr ++;

        // If we've filled the whole byte,  write it into the mask data stream
        if ($bit_ctr >= 8 || $x + 1 == $size_x) { 
          // Pad mask data, in case we have completed the image row
          while ($bit_ctr < 8) {
            $mask_data = ($mask_data << 1) | 0x01;
            $bit_ctr ++;
          };
          
          $psdata->write(sprintf("%02X", $mask_data & 0xff)); 

          // Clear mask data after writing 
          $mask_data = 0xff;
          $bit_ctr = 0;

          // Increate the line length counter; check if stream line needs to be terminated
          $ctr += 1;
          if ($ctr > MAX_LINE_LENGTH) { 
            $psdata->write("\n");
            $ctr = 0;
          }
        };
      };
    };

    // terminate the stream data
    // Write any incomplete mask byte to the mask data stream
    if ($bit_ctr != 0) {
      while ($bit_ctr < 8) {
        $mask_data <<= 1;
        $mask_data |= 1;
        $bit_ctr ++;
      }
      $psdata->write(sprintf("%02X", $mask_data));
    };
    $psdata->write(">\ndef\n");

    // return image and mask data references
    $image = "image-{$id}-data";
    $mask  = "mask-{$id}-data";

    return $id;
  }

  function alpha(&$psdata, $src_img, &$size_x, &$size_y, &$image, &$mask) {
    // Generate an unique image id
    $id = $this->generate_id();

    // Determine image size
    $size_x      = imagesx($src_img); 
    $size_y      = imagesy($src_img);
    
    // write stread header to the postscript file
    $psdata->write("/image-{$id}-init { image-{$id}-data 0 setfileposition } def\n");
    $psdata->write("/image-{$id}-data currentfile << /Filter /ASCIIHexDecode >> /ReusableStreamDecode filter\n");

    // initialize line length counter
    $ctr = 0;

    // Save visible background color
    $handler =& CSS::get_handler(CSS_BACKGROUND_COLOR);
    $bg = $handler->get_visible_background_color();

    for ($y = 0; $y < $size_y; $y++) {
      for ($x = 0; $x < $size_x; $x++) {
        // Check color/alpha of current pixels
        $colors = imagecolorsforindex($src_img, imagecolorat($src_img, $x, $y));

        $a = $colors['alpha']; 
        $r = $colors['red'];
        $g = $colors['green'];
        $b = $colors['blue'];

        // Calculate approximate color 
        $r = (int)($r + ($bg[0] - $r)*$a/127);
        $g = (int)($g + ($bg[1] - $g)*$a/127);
        $b = (int)($b + ($bg[2] - $b)*$a/127);

        // Save image pixel to the stream data
        $psdata->write(sprintf("%02X%02X%02X",$r,$g,$b));

        // Increate the line length counter; check if stream line needs to be terminated
        $ctr += 6;
        if ($ctr > MAX_LINE_LENGTH) { 
          $psdata->write("\n");
          $ctr = 0;
        }
      };
    };

    // terminate the stream data
    $psdata->write(">\ndef\n");

    // return image and mask data references
    $image = "image-{$id}-data";
    $mask  = "";

    return $id;
  }

}
?>