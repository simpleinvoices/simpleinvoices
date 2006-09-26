<?php
// $Header: /cvsroot/html2ps/systemcheck.php,v 1.7 2006/04/16 16:54:58 Konstantin Exp $

// @todo: check if pdf_create_field is available (thus, if interactive forms available for PDFLIB)

// Check the system requirements
//
function check_requirements() {
  // Check if GD is available
  //
  if (!function_exists('imagecreatetruecolor')) { 
    die("No GD2 extension found. Check your PHP configuration");
  };

  // Check if allow_url_fopen is available
  //  
  if (!ini_get('allow_url_fopen')) {
    readfile(HTML2PS_DIR.'/templates/missing_url_fopen.html');
    error_log("'allow_url_fopen' is disabled");
    die();
  }

  // Check if image cache works.
  // if it doesn't, the check_cache_dir will not return, so we may not bother 
  // with checking result value
  //
  Image::check_cache_dir();
}
?>