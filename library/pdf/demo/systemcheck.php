<?php

require_once('../config.inc.php');
require_once(HTML2PS_DIR.'stubs.common.inc.php');
require_once(HTML2PS_DIR.'error.php');
require_once(HTML2PS_DIR.'config.parse.php');

parse_config_file('../html2ps.config');

define('CHECK_STATUS_FAILED',  0);
define('CHECK_STATUS_WARNING', 1);
define('CHECK_STATUS_SUCCESS', 2);

error_reporting(E_ALL);
ini_set("display_errors","1");

$__g_registered_checks = array();

function out_header() {
  readfile('systemcheck.header.tpl');
}

function out_footer() {
  readfile('systemcheck.footer.tpl');
}

function status2class($status) {
  $mapping = array(CHECK_STATUS_FAILED  => "failed",
                   CHECK_STATUS_WARNING => "warning",
                   CHECK_STATUS_SUCCESS => "success");
  if (isset($mapping[$status])) {
    return $mapping[$status];
  };

  error_log(sprintf("Unknown status code passed to 'status2class': %s", $status));
  return "unknown";
}

function out_check_list() {
  $checks = ManagerChecks::getChecks();
  foreach ($checks as $check) {
    $title   = htmlspecialchars($check->title());
    $message = nl2br($check->getMessage());
    $status_class = status2class($check->getStatus());

    print <<<EOF
<div class="check">
<div class="title ${status_class}">${title}</div>
<div class="message">${message}</div>
</div>
EOF;
  };
}

class ManagerChecks {
  function register($check) {
    global $__g_registered_checks;
    $__g_registered_checks[] = $check;
  }

  function run() {
    global $__g_registered_checks;
    $size = count($__g_registered_checks);
    for ($i=0; $i<$size; $i++) {
      $__g_registered_checks[$i]->run();
    };
  }

  function getChecks() {
    global $__g_registered_checks;
    return $__g_registered_checks;
  }
}

class CheckSimple {
  var $_message;

  /**
   * Invariants
   */
  function title() {
    error_no_method('title', get_class($this));
  }

  function description() {
    error_no_method('description', get_class($this));
  }

  /**
   * Start checking 
   */
  function run() {
    error_no_method('run', get_class($this));
  }

  /**
   * Get check status code; status code should be one of the following 
   * predefined constants:
   * CHECK_STATUS_FAILED  - check failed, script will not work unless this issue is fixed 
   * CHECK_STATUS_WARNING - check succeeded, script may encounter minor issues
   * CHECK_STATUS_SUCCESS - check succeeded without any problems
   * 
   * @return Integer Status code
   */
  function getStatus() {
    error_no_method('status', get_class($this));
  }

  /**
   * Returns a short human-readable  message describing results of the
   * check run. By default, this message is generated in 'run' method
   * (overridden in CheckSimple children) and stored via 'setMessage'
   *
   * @return String description of the test results
   */
  function getMessage() {
    return $this->_message;
  }

  function setMessage($message) {
    $this->_message = $message;
  }
}

/**
 */
class CheckBinary extends CheckSimple {
  var $_success;

  function setSuccess($success) {
    $this->_success = $success;
  }

  function getSuccess() {
    return $this->_success;
  }
}

/**
 */
class CheckBinaryRequired extends CheckBinary {
  function getStatus() {
    if ($this->getSuccess()) {
      return CHECK_STATUS_SUCCESS;
    } else {
      return CHECK_STATUS_FAILED;
    };
  }
}

/**
 */
class CheckBinaryRecommended extends CheckBinary {
  function getStatus() {
    if ($this->getSuccess()) {
      return CHECK_STATUS_SUCCESS;
    } else {
      return CHECK_STATUS_WARNING;
    };
  }
}

/**
 */
class CheckTriState extends CheckSimple {
  var $_status;

  function getStatus() {
    return $this->_status;
  }

  function setStatus($status) {
    $this->_status = $status;
  }
}

/**
 * Actual checks
 */

/**
 * PHP version
 */
class CheckPHPVersion extends CheckTriState {
  function title() {
    return "PHP Version";
  }

  function description() {
    return "";
  }

  function run() {
    //    > "4.3.0";
  }
}
// ManagerChecks::register(new CheckPHPVersion());

/**
 * Required / recommended extensions
 */

/**
 * Presense of DOM/XML extensions
 */
class CheckDOM extends CheckTriState {
  function title() {
    return "XML DOM extension";
  }

  function description() {
    return "HTML files are parsed using XML DOM extensions";
  }

  function run() {
    if (function_exists('domxml_open_mem') || 
        class_exists('DOMDocument')) { 
      $this->setStatus(CHECK_STATUS_SUCCESS);
      $this->setMessage('Native XML DOM extension found');
      return;
    };
    
    if (file_exists(HTML2PS_DIR.'classes/include.php')) { 
      $this->setStatus(CHECK_STATUS_WARNING);
      $this->setMessage('No native XML DOM extension found, falling back to Active-State DOM XML. Note that it is <b>highly recommended to use native PHP XML DOM extension</b>.');
      return;
    };

    $this->setStatus(CHECK_STATUS_FAILED);
    $this->setMessage('No XML DOM extension found');
  }
}

/**
 * Presense of PDFLIB extension
 */
class CheckPDFLIB extends CheckBinaryRecommended {
}

/**
 * Presense of Curl extension
 */
class CheckCurl extends CheckBinaryRecommended {
  function title() {
    return "Curl PHP Extension";
  }

  function description() {
    return "Curl PHP extension is recommended for fetching files via HTTP protocol";
  }

  function run() {
    $this->setSuccess(false);

    if (!extension_loaded('curl')) {
      $this->setMessage('Missing Curl extension. Script will use pure-PHP fallback (allow_url_fopen=On is required!). <b>Proxy support is not available</b>');
      return;
    };

    $version = curl_version();
    // PHP 5.0.1 and greater return array instead of string
    if (is_array($version)) {
      $version = $version['version'];
    };
    $this->setMessage(sprintf('Found Curl extension version %s.', $version['version']));
    $this->setSuccess(true);
  }
}

/**
 * Presense of GD extension
 */
class CheckGD extends CheckBinaryRequired {
  function title() {
    return "GD PHP Extension";
  }

  function description() {
    return "GD PHP extension is required for graphic file processing";
  }

  function run() {
    $this->setSuccess(false);

    if (!extension_loaded('gd')) {
      $this->setMessage('Missing GD extension. Please refer to <a href="http://php.net/gd">PHP.net instructions</a> on installing/enabling this extension.');
      return;
    };

    $gd_info = gd_info();
    $gd_version_string = $gd_info['GD Version'];
    
    /**
     * Extract version number if it is a bundled version; otherwise we assume that
     * version string should contain verions number only
     */
    if (preg_match("/bundled \(([\d\.]+) compatible\)/", $gd_version_string, $matches)) {
      $gd_version = $matches[1];
    } else {
      $gd_version = $gd_version_string;
    };

    if ($gd_version < "2.0.1") {
      $this->setMessage("GD version 2.0.1+ required for 'imagecreatetruecolor' function to work");
      return;
    };

    $this->setMessage("Found GD version $gd_version.");
    $this->setSuccess(true);
  }
}

/**
 * Presense of ZLIB extension (compressed files)
 */
class CheckZLIB extends CheckBinaryRecommended {
}

/**
 * System limits & settings
 */

/**
 * Execution time limit
 */
class CheckMaxExecutionTime extends CheckTriState {
}

/**
 * Memory limit
 */
class CheckMemoryLimit extends CheckTriState {
}

/**
 * Allow_url_fopen setting
 */
class CheckAllowURLFopen extends CheckBinaryRecommended {
  function title() {
    return "allow_url_fopen ini setting";
  }

  function description() {
    return "allow_url_fopen should be enabled when CURL extension is not available";
  }

  function run() {
    $this->setSuccess(false);

    $setting = ini_get('allow_url_fopen');
    if (!$setting) {
      $this->setMessage('<a href="http://php.net/filesystem">allow_url_fopen</a> is disabled. You will not be able to fetch files via HTTP without CURL extension.');
      return;
    }

    $this->setMessage('allow_url_fopen is enabled');
    $this->setSuccess(true);
  }
}


/**
 * pcre.backtrack_limit setting (PHP 5.2)
 */
class CheckPCREBacktrack extends CheckBinaryRecommended {
  function title() {
    return "pcre.backtrack_limit ini setting";
  }

  function description() {
    return "It is recommended to increase pcre.backtrack_limit value to 1,000,000";
  }

  function run() {
    $this->setSuccess(false);

    $version = explode('.', PHP_VERSION);
    if ($version[0] < 5 || 
        ($version[0] == 5 && $version[1] < 2)) {
      $this->setMessage('pcre.backtrack_limit is not available in PHP prior to 5.2.0');
      $this->setSuccess(true);
      return;
    };

    $setting = ini_get('pcre.backtrack_limit');
    if ($setting < 1000000) {
      $this->setMessage(sprintf('<a href="http://php.net/filesystem">pcre.backtrack_limit</a> is set to %s (less than 1,000,000). You could experience issues converting large pages.',
                                $setting));
      return;
    }

    $this->setMessage('pcre.backtrack_limit is greater than 1,000,000');
    $this->setSuccess(true);
  }
}


/**
 * Access/permissions
 */

/**
 * permissions on cache directory
 */
class CheckPermissionsCache extends CheckBinaryRequired {
}

/**
 * Permissions on 'out' directory
 */
class CheckPermissionsOut extends CheckBinaryRecommended {
}

/**
 * Permissions on 'temp' directory (system-dependent)
 */
class CheckPermissionsTemp extends CheckBinaryRequired {
  function title() {
    return "Permissions on 'temp' subdirectory";
  }

  function description() {
    return "Script should have full access to 'temp' subdirectory to keep temporary files there";
  }

  function run() {
    if (!file_exists(HTML2PS_DIR.'/temp/')) {
      $this->setMessage("'temp' subdirectory is missing");
      $this->setSuccess(false);
      return;
    };

    if (!is_readable(HTML2PS_DIR.'/temp/')) {
      $this->setMessage("'temp' subdirectory is not readable");
      $this->setSuccess(false);
      return;
    };

    if (!is_writable(HTML2PS_DIR.'/temp/')) {
      $this->setMessage("'temp' subdirectory is not writable");
      $this->setSuccess(false);
      return;
    };

    if (!is_executable(HTML2PS_DIR.'/temp/') && PHP_OS != "WINNT") {
      $this->setMessage("'temp' subdirectory is not executable");
      $this->setSuccess(false);
      return;
    };

    $this->setMessage("'temp' subdirectory is fully accessible to the script");
    $this->setSuccess(true);
  }
}

/**
 * Permissions/availability of GS executable
 */

/**
 * Permissions of fonts directory
 */
class CheckPermissionsFonts extends CheckBinaryRequired {
  function title() {
    return "Permissions on 'fonts' subdirectory";
  }

  function description() {
    return "Script should be able to read 'fonts' subdirectory to access installed fonts";
  }

  function run() {
    if (!file_exists(HTML2PS_DIR.'/fonts/')) {
      $this->setMessage("'fonts' subdirectory is missing");
      $this->setSuccess(false);
      return;
    };

    if (!is_readable(HTML2PS_DIR.'/fonts/')) {
      $this->setMessage("'fonts' subdirectory is not readable");
      $this->setSuccess(false);
      return;
    };

    if (!is_executable(HTML2PS_DIR.'/fonts/') && PHP_OS != "WINNT") {
      $this->setMessage("'fonts' subdirectory is not executable");
      $this->setSuccess(false);
      return;
    };

    $this->setMessage("'fonts' subdirectory is readable and executable by the script");
    $this->setSuccess(true);
  }
}

/**
 * Permissions/presence of Type1 fonts repository
 */
class CheckPermissionsType1 extends CheckBinaryRecommended {
  function title() {
    return "Permissions on Type1 fonts directory";
  }

  function description() {
    return "Script should be able to access Type1 fonts directory containing font metrics in order to generate Postscript files";
  }

  function run() {
    if (!file_exists(TYPE1_FONTS_REPOSITORY)) {
      $this->setMessage("Type1 fonts directory (".TYPE1_FONTS_REPOSITORY.") is missing. You will not be able to generate postscript files.");
      $this->setSuccess(false);
      return;
    };

    if (!is_readable(TYPE1_FONTS_REPOSITORY)) {
      $this->setMessage("Type1 fonts directory (".TYPE1_FONTS_REPOSITORY.") is not readable. You will not be able to generate postscript files.");
      $this->setSuccess(false);
      return;
    };

    if (!is_executable(HTML2PS_DIR.'/fonts/') && PHP_OS != "WINNT") {
      $this->setMessage("Type1 fonts directory (".TYPE1_FONTS_REPOSITORY.") is not executable. You will not be able to generate postscript files.");
      $this->setSuccess(false);
      return;
    };

    $this->setMessage("Type1 fonts directory is readable and executable by the script");
    $this->setSuccess(true);
  }
}

/**
 * Fonts
 */

/**
 * Permissions/presense of TTF files
 */
class CheckPresenceTTF extends CheckBinaryRecommended {
  function title() {
    return "Presense of registered TTF files";
  }

  function description() {
    return "TrueType fonts registered in html2ps.config should be present in order to generate PDF files with these fonts.";
  }

  function run() {
    $message = "";
    $this->setSuccess(true);

    global $g_font_resolver_pdf;
    foreach ($g_font_resolver_pdf->ttf_mappings as $file) {
      $fullname = HTML2PS_DIR.'/fonts/'.$file;

      if (!file_exists($fullname)) {
        $message .= "Font ".$fullname." is missing. You will not be able to generate PDF files with this font.\n";
        $this->setSuccess(false);
      } elseif (!file_exists($fullname)) {
        $message .= "Font ".$fullname." is not readable. You will not be able to generate PDF files with this font.\n";
        $this->setSuccess(false);
      } else {
        $message .= "Font ".$fullname." is present and readable.\n";
      };
    };
        
    $this->setMessage($message);
  }
}

/**
 * Permissions/presense of Type1 fonts
 */

/**
 * Permissions/presense of AFM files for Type1 fonts
 */
class CheckPresenceType1AFM extends CheckBinaryRecommended {
  function title() {
    return "Presense of registered TTF files";
  }

  function description() {
    return "TrueType fonts registered in html2ps.config should be present in order to generate PDF files with these fonts.";
  }

  function run() {
    $message = "";
    $this->setSuccess(true);

    global $g_font_resolver;
    foreach ($g_font_resolver->afm_mappings as $file) {
      $fullname = TYPE1_FONTS_REPOSITORY.$file.'.afm';

      if (!file_exists($fullname)) {
        $message .= "Font ".$fullname." is missing. You will not be able to generate PDF files with this font.\n";
        $this->setSuccess(false);
      } elseif (!file_exists($fullname)) {
        $message .= "Font ".$fullname." is not readable. You will not be able to generate PDF files with this font.\n";
        $this->setSuccess(false);
      } else {
        $message .= "Font ".$fullname." is present and readable.\n";
      };
    };
        
    $this->setMessage($message);
  }
}

/**
 * Graphics
 */

/**
 * Generic
 */
class CheckGDFormat extends CheckBinaryRequired {
  function title() {
    return "GD ".$this->_getFormatName()." Support";
  }

  function description() {
    return "GD ".$this->_getFormatName()." Support is required for reading images in ".$this->_getFormatName()." format";
  }

  function run() {
    $this->setSuccess(false);

    if (!extension_loaded('gd')) {
      $this->setMessage('Missing GD extension. Please refer to <a href="http://php.net/gd">PHP.net instructions</a> on installing/enabling this extension.');
      return;
    };

    $gd_info = gd_info();
    if (!$gd_info[$this->_getInfoKey()]) {
      $this->setMessage("No ".$this->_getFormatName()." support, some images will not be displayed");
      return;
    };

    $this->setMessage($this->_getFormatName()." support enabled");
    $this->setSuccess(true);
  }
}

/**
 * JPEG support
 */
class CheckGDJPEG extends CheckGDFormat {
  function _getFormatName() {
    return "JPEG";
  }

  function _getInfoKey() {
    return "JPG Support";
  }
}

/**
 * GIF support
 */
class CheckGDGIF extends CheckGDFormat {
  function _getFormatName() {
    return "GIF";
  }

  function _getInfoKey() {
    return "GIF Read Support";
  }
}

/**
 * PNG support
 */
class CheckGDPNG extends CheckGDFormat {
  function _getFormatName() {
    return "PNG";
  }

  function _getInfoKey() {
    return "PNG Support";
  }
}

/**
 * Freetype support
 */

/**
 * Miscellanous
 */

/**
 * Check if outgoing connections are allowed
 */
class CheckOutgoingConnections extends CheckBinary {
}

ManagerChecks::register(new CheckDOM());
ManagerChecks::register(new CheckCurl());
ManagerChecks::register(new CheckAllowURLFopen());
ManagerChecks::register(new CheckPCREBacktrack());
ManagerChecks::register(new CheckGD());
ManagerChecks::register(new CheckGDJPEG());
ManagerChecks::register(new CheckGDGIF());
ManagerChecks::register(new CheckGDPNG());
ManagerChecks::register(new CheckPermissionsTemp());
ManagerChecks::register(new CheckPermissionsFonts());
ManagerChecks::register(new CheckPermissionsType1());
ManagerChecks::register(new CheckPresenceTTF());
ManagerChecks::register(new CheckPresenceType1AFM());

ManagerChecks::run();
out_header();
out_check_list();
out_footer();

?>