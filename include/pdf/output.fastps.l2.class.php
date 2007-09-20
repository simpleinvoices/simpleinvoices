<?php

class OutputDriverFastPSLevel2 extends OutputDriverFastPS {
  function OutputDriverFastPSLevel2(&$image_encoder) {
    $this->OutputDriverFastPS($image_encoder);
  }

  function image($image, $x, $y, $scale) {
    $this->image_scaled($image, $x, $y, $scale, $scale);
  }

  function image_scaled($image, $x, $y, $scale_x, $scale_y) { 
    $image_encoder = $this->get_image_encoder();
    $lines = $image_encoder->by_lines($image, $size_x, $size_y);

    $offset = 0;
    foreach ($lines as $line) {
      $this->moveto($x,$y-$offset*$scale_y);
      $this->write(sprintf("gsave\n"));
      $this->write(sprintf(" << /ImageType 1 /Width %d /Height 1 /BitsPerComponent 8 /Decode [0 1 0 1 0 1] /ImageMatrix %s /DataSource %s >> image\n",
                           $size_x,
                           sprintf("matrix currentpoint translate %.2f %.2f scale 0 %.2f translate",
                                   $scale_x, $scale_y,
                                   $size_y
                                   ),
                           "currentfile /ASCIIHexDecode filter"));
      $this->write(sprintf("%s\n", $line));
      $this->write(sprintf("grestore\n"));

      $offset ++;
    };
  }

  function image_ry($image, $x, $y, $height, $bottom, $ox, $oy, $scale) { 
    // Fill part to the bottom
    $cy = $y;
    while ($cy+$height > $bottom) {
      $this->image($image, $x, $cy, $scale);
      $cy -= $height;
    };

    // Fill part to the top
    $cy = $y;
    while ($cy-$height < $y + $oy) {
      $this->image($image, $x, $cy, $scale);
      $cy += $height;
    };
  }
  
  function image_rx($image, $x, $y, $width, $right, $ox, $oy, $scale) { 
    // Fill part to the right 
    $cx = $x;
    while ($cx < $right) {
      $this->image($image, $cx, $y, $scale);
      $cx += $width;
    };

    // Fill part to the left
    $cx = $x;
    while ($cx+$width >= $x - $ox) {
      $this->image($image, $cx-$width, $y, $scale);
      $cx -= $width;
    };
  }

  function image_rx_ry($image, $x, $y, $width, $height, $right, $bottom, $ox, $oy, $scale) { 
    // Fill bottom-right quadrant
    $cy = $y;
    while ($cy+$height > $bottom) {
      $cx = $x;
      while ($cx < $right) {
        $this->image($image, $cx, $cy, $scale);
        $cx += $width;
      };
      $cy -= $height;
    }

    // Fill bottom-left quadrant
    $cy = $y;
    while ($cy+$height > $bottom) {
      $cx = $x;
      while ($cx+$width > $x - $ox) {
        $this->image($image, $cx, $cy, $scale);
        $cx -= $width;
      };
      $cy -= $height;
    }

    // Fill top-right quadrant
    $cy = $y;
    while ($cy < $y + $oy) {
      $cx = $x;
      while ($cx < $right) {
        $this->image($image, $cx, $cy, $scale);
        $cx += $width;
      };
      $cy += $height;
    }

    // Fill top-left quadrant
    $cy = $y;
    while ($cy < $y + $oy) {
      $cx = $x;
      while ($cx+$width > $x - $ox) {
        $this->image($image, $cx, $cy, $scale);
        $cx -= $width;
      };
      $cy += $height;
    }
  }
}

?>