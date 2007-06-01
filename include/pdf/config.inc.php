<?php
// $Header: /cvsroot/html2ps/config.inc.php,v 1.26 2006/05/27 15:33:26 Konstantin Exp $

/**
 * Common configuration options
 */

// Directory containing HTML2PS script files (without traling slash)
define('HTML2PS_DIR', dirname(__FILE__));

// User-Agent HTTP header to sent when requesting a file
define('DEFAULT_USER_AGENT',"Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7) Gecko/20040803 Firefox/0.9.3");

// Default PDF or PS file name to use 
define('OUTPUT_DEFAULT_NAME','unnamed');

// Default text encoding to use when no encoding information is available
define('DEFAULT_ENCODING', 'utf-8');
//define('DEFAULT_ENCODING', 'iso-8859-1');

/**
 * Postscript-specific configuration options
 */

// Path to Ghostscript executable
define('GS_PATH','c:\gs\gs8.51\bin\gswin32c.exe');

// Path to font metric files (AFM files). 
// NOTE: Trailing backslash required
define('TYPE1_FONTS_REPOSITORY',"c:\\gs\\fonts\\");

/**
 * PDFLIB-specific configuration options
 */

// Path to PDFLIB dynamically loaded library (if not configured in php.ini to load automatically)
define('PDFLIB_DL_PATH','pdflib.so');

// Uncomment this if you have PDFLIB license key and want to use it
// define('PDFLIB_LICENSE', 'YOUR LICENSE KEY');

// This variable defines the path to PDFLIB configuration file; in particular, it contains
// information about the supported encodings. 
//
// define('PDFLIB_UPR_PATH',"c:/php/php4.4.0/pdf-related/pdflib.upr");
// define('PDFLIB_UPR_PATH',"c:/php/pdf-related/pdflib.upr");

// Path to directory containing fonts used by PDFLIB 
// Trailing backslash required
define('PDFLIB_TTF_FONTS_REPOSITORY',HTML2PS_DIR."/fonts/");

/**
 * Some constants you better not change.
 *
 * They're created in order to avoid using too much "magic numbers" in the script source,
 * but it is very unlikely you'll need to change them
 */

define('EPSILON',0.001);
define('DEFAULT_SUBMIT_TEXT','Submit');
define('DEFAULT_RESET_TEXT' ,'Reset');
define('DEFAULT_BUTTON_TEXT','Send request');
define('CHECKBOX_SIZE','15px');
define('RADIOBUTTON_SIZE','15px');
define('SELECT_BUTTON_TRIANGLE_PADDING',1.5);
define('BROKEN_IMAGE_DEFAULT_SIZE_PX',24);
define('BROKEN_IMAGE_ALT_SIZE_PT',10);
define('BASE_FONT_SIZE_PT',11);
define('DEFAULT_TEXT_SIZE',20);
define('EM_KOEFF',1);
define('EX_KOEFF',0.60);
define('DEFAULT_CHAR_WIDTH', 600);
define('WHITESPACE_FONT_SIZE_FRACTION', 0.25);
define('SIZE_SPACE_KOEFF',1.5);
define('LEGEND_HORIZONTAL_OFFSET','5pt');
define('BULLET_SIZE_KOEFF',0.15);
define('HEIGHT_KOEFF',0.7);
define('MAX_FRAME_NESTING_LEVEL',4);
define('MAX_JUSTIFY_FRACTION',0.33);
define('HILIGHT_COLOR_ALPHA',0.6);
define('MAX_REDIRECTS',5);

// Maximal length of line inside the stream data 
// (we need to limit this, as most postscript interpreters will complain 
// on long strings) 
//
// Note it is measured in BYTES! Each byte will be represented by TWO characters
// in the hexadecimal form
//
define("MAX_LINE_LENGTH", 100);
define('MAX_IMAGE_ROW_LEN',16);
define('MAX_TRANSPARENT_IMAGE_ROW_LEN',16);

define('CACHE_DIR', '../../cache/');
define('OUTPUT_FILE_DIRECTORY', '../../cache');
//define('OUTPUT_FILE_DIRECTORY', HTML2PS_DIR.'/cache');
define('FPDF_PATH', HTML2PS_DIR.'/fpdf/');

// Note that WRITER_TEMPDIR !REQUIRES! slash (or backslash) on the end (unless you want to get 
// some files like tempPS_jvckxlvjl in your working directory).
//define('WRITER_TEMPDIR', HTML2PS_DIR.'/cache/');
define('WRITER_TEMPDIR', CACHE_DIR);
define('WRITER_FILE_PREFIX','PS_');

// number of retries to generate unique filename in case we have had troubles with
// tempnam function
define('WRITER_RETRIES',10);
define('WRITER_CANNOT_CREATE_FILE',"Cannot create unique temporary filename, sorry");

define('HTML2PS_CONNECTION_TIMEOUT', 10);

// directory to restrict 'file://' access to
// empty string for no restrictions
define('FILE_PROTOCOL_RESTRICT', HTML2PS_DIR);

?>
