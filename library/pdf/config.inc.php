<?php
// $Header: /cvsroot/html2ps/config.inc.php,v 1.50 2007/05/17 13:55:13 Konstantin Exp $

/**
 * Common configuration options
 */

// Directory containing HTML2PS script files (with traling slash)
if (!defined('HTML2PS_DIR')) {
  define('HTML2PS_DIR', dirname(__FILE__).'/');
};

// User-Agent HTTP header to send when requesting a file
define('DEFAULT_USER_AGENT',"Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7) Gecko/20040803 Firefox/0.9.3");

// Default PDF or PS file name to use 
define('OUTPUT_DEFAULT_NAME','unnamed');

// Default text encoding to use when no encoding information is available
//JK Simple Invoices mod
//define('DEFAULT_ENCODING', 'iso-8859-1');
define('DEFAULT_ENCODING', 'utf-8');

/**
 * Postscript-specific configuration options
 */

// Path to Ghostscript executable
define('GS_PATH','c:\gs\gs8.51\bin\gswin32c.exe');

// Path to font metric files (AFM files). 
// NOTE: Trailing backslash required
define('TYPE1_FONTS_REPOSITORY',"c:\\gs\\fonts\\");
// define('TYPE1_FONTS_REPOSITORY',"/usr/share/ghostscript/fonts/");

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

// Path to directory containing fonts used by PDFLIB / FPDF
// Trailing backslash required
define('TTF_FONTS_REPOSITORY',HTML2PS_DIR."fonts/");

// Determines how font files are embedded. May be:
// 'all' - embed all fonts
// 'none' - do not embed any fonts
// 'config' - whether font is embedded is determined by html2ps.config 'embed' attribute value for this font
define('FONT_EMBEDDING_MODE', 'config');

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
define('BASE_FONT_SIZE_PT',12);
define('DEFAULT_TEXT_SIZE',20);
define('EM_KOEFF',1);

// Note that Firefox calculated ex for each font separately, while
// IE uses fixed value of 'ex' unit. We behave like IE here.
define('EX_KOEFF',0.50); 

define('DEFAULT_CHAR_WIDTH', 600);
define('WHITESPACE_FONT_SIZE_FRACTION', 0.25);
define('SIZE_SPACE_KOEFF',1.2);
define('INPUT_SIZE_EM_KOEFF',0.48);
define('INPUT_SIZE_BASE_EM',2.2);
define('SELECT_SPACE_PADDING', 5);
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

define('CACHE_DIR', HTML2PS_DIR.'../../tmp/cache/');
define('OUTPUT_FILE_DIRECTORY', HTML2PS_DIR.'../../tmp/cache/');
define('FPDF_PATH', HTML2PS_DIR.'fpdf/');

// Trailing directory separator ('/' or '\', depending on your system)
// SHOULD BE OMITTED
define('WRITER_TEMPDIR', HTML2PS_DIR.'../../tmp/cache');
define('WRITER_FILE_PREFIX','PS_');

// number of retries to generate unique filename in case we have had troubles with
// tempnam function
define('WRITER_RETRIES',10);
define('WRITER_CANNOT_CREATE_FILE',"Cannot create unique temporary filename, sorry");

define('HTML2PS_CONNECTION_TIMEOUT', 10);

// directory to restrict 'file://' access to
// empty string for no restrictions
define('FILE_PROTOCOL_RESTRICT', HTML2PS_DIR);

define('FOOTNOTE_LINE_PERCENT', 30);
define('FOOTNOTE_LINE_TOP_GAP', 1); // Content points
define('FOOTNOTE_LINE_BOTTOM_GAP', 3); // Content points
define('FOOTNOTE_MARKER_MARGIN', 1); // Content points
define('FOOTNOTE_GAP', 2); // Space between footnotes

if (!defined('DEBUG_MODE')) {
  //  define('DEBUG_MODE',1);
};

define('HTML2PS_VERSION_MAJOR', 2);
define('HTML2PS_VERSION_MINOR', 0);
define('HTML2PS_SUBVERSON', 35);

define('MAX_UNPENALIZED_FREE_FRACTION', 0.25);
define('MAX_FREE_FRACTION',             0.5);
define('MAX_PAGE_BREAK_HEIGHT_PENALTY',  10000);
define('MAX_PAGE_BREAK_PENALTY',       1000000);
define('FORCED_PAGE_BREAK_BONUS',     -1000000);
define('PAGE_BREAK_INSIDE_AVOID_PENALTY',  300);
define('PAGE_BREAK_AFTER_AVOID_PENALTY',  5100);
define('PAGE_BREAK_BEFORE_AVOID_PENALTY', 1100);
define('PAGE_BREAK_ORPHANS_PENALTY',      1000);
define('PAGE_BREAK_WIDOWS_PENALTY',       1000);
define('PAGE_BREAK_LINE_PENALTY',            0);
define('PAGE_BREAK_BORDER_PENALTY',        150);

define('OVERLINE_POSITION', 1);
define('UNDERLINE_POSITION', 0.1);
define('LINE_THROUGH_POSITION', 0.4);

?>
