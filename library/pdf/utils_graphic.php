<?php
// $Header: /cvsroot/html2ps/utils_graphic.php,v 1.9 2007/01/24 18:56:10 Konstantin Exp $

function do_image_open($filename) {
  // Gracefully process missing GD extension
  if (!extension_loaded('gd')) {
    return null;
  };

  // Disable interlacing for the generated images, as we do not need progressive images 
  // if PDF files (futhermore, FPDF does not support such images)
  $image = do_image_open_wrapped($filename);
  if (!is_resource($image)) { return null; };

  if (!is_null($image)) {
    imageinterlace($image, 0);
  };

  return $image;
}

function do_image_open_wrapped($filename) {
  // FIXME: it will definitely cause problems;
  global $g_config;
  if (!$g_config['renderimages']) {
    return null;
  };

  // get the information about the image
  if (!$data = @getimagesize($filename)) { return null; };
  switch ($data[2]) {
  case 1: // GIF
    // Handle lack of GIF support in older versions of PHP
    if (function_exists('imagecreatefromgif')) {
      return @imagecreatefromgif($filename);
    } else {
      return null;
    };
  case 2: // JPG
    return @imagecreatefromjpeg($filename);
  case 3: // PNG
    $image = imagecreatefrompng($filename);
//     imagealphablending($image, false);
//     imagesavealpha($image, true);
    return $image;
  case 15: // WBMP
    return @imagecreatefromwbmp($filename);
  };
  return null;
};
?>