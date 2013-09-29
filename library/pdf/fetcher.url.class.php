<?php

require_once(HTML2PS_DIR.'fetcher._interface.class.php');

define('HTTP_OK',200);

/**
 * @TODO send authorization headers only if they have been required by the server;
 */
class FetcherUrl extends Fetcher {
  var $_connections;

  var $protocol;
  var $host;
  var $port;
  var $path;

  var $url;

  var $headers;
  var $content;
  var $code;

  var $redirects;

  // Authorization

  var $user;
  var $pass;

  // ---------------------------------------------
  // FetcherURL - PUBLIC methods
  // ---------------------------------------------

  // "Fetcher" interface implementation

  function get_base_url() {
    return $this->url;
  }

  function get_data($data_id) {
    $this->redirects = 0;

    if ($this->fetch($data_id)) {
      if ($this->code != HTTP_OK) {

        $_server_response = $this->headers;
        $_http_error = $this->code;
        $_url = htmlspecialchars($data_id);

        ob_start();
        include('templates/error._http.tpl');
        $this->error_message .= ob_get_contents();
        ob_end_clean();

        error_log("Cannot open $data_id, HTTP result code is: ".$this->code);

        return null;
      };

      return new FetchedDataURL($this->content,
                                explode("\r\n",$this->headers),
                                $this->url);
    } elseif ($this->redirects > MAX_REDIRECTS) {
      $_server_response    = $this->headers;
      $_url = htmlspecialchars($data_id);

      ob_start();
      include('templates/error._redirects.tpl');
      $this->error_message .= ob_get_contents();
      ob_end_clean();

      error_log(sprintf("Cannot open %s, too many redirects",
                        $data_id));

      return null;
    } else {
      $_server_response = $this->headers;
      $_url = htmlspecialchars($data_id);

      ob_start();
      include('templates/error._connection.tpl');
      $this->error_message .= ob_get_contents();
      ob_end_clean();

      error_log(sprintf("Cannot open %s",
                        $data_id));

      return null;
    }
  }

  function error_message() {
    return $this->error_message;
  }

  // FetcherURL - constructor

  function FetcherURL() {
    $this->_connections = array();

    $this->error_message = "";

    $this->redirects = 0;
    $this->port = 80;

    // Default encoding
    //    $this->encoding = "iso-8859-1";

    $this->user_agent = DEFAULT_USER_AGENT;
  }

  // ---------------------------------------------
  // FetcherURL - PRIVATE methods
  // ---------------------------------------------

  /**
   * Connects to the target host using either HTTP or HTTPS protocol;
   * returns handle to connection socked or 'null' in case connection failed.
   *
   * @access private
   * @final
   * @return resource
   */
  function _connect() {
    // Connect to the target host
    if ($this->protocol == "https") {
      return $this->_connect_ssl();
    };

    $fp = @fsockopen($this->host,$this->port,$errno,$errstr,HTML2PS_CONNECTION_TIMEOUT);

    if (!$fp) {
      $message = sprintf("Cannot connect to %s:%d - (%d) %s", 
                         $this->host, 
                         $this->port,
                         $errno,
                         $errstr);
      error_log($message);
      $this->error_message = $message;
      return null;
    };

    return $fp;
  }

  function _connect_ssl() {
    /**
     * Check if there's SSL support library loaded 
     * 
     * Note that in certain situations (e.g. Windows + PHP 4.4.0 + Apache 2 on my development box)
     * openssl extension IS present, but fsockopen still complains "No SSL support in this build".
     * (probably PHP bug?) 
     */
    if (!extension_loaded('openssl')) {
      $message = sprintf("Cannot connect to %s:%d. SSL Extension missing", 
                         $this->host, 
                         $this->port);
      error_log($message);
      $this->error_message .= $message;
      return null;
    };

    $fp = @fsockopen("ssl://$this->host", $this->port, $errno, $errstr, 5);

    if (!$fp) {
      $message = sprintf("Cannot connect to %s:%d - (%d) %s<br/>Missing SSL support?", 
                         $this->host, 
                         $this->port,
                         $errno,
                         $errstr);
      error_log($message);
      $this->error_message = $message;
      return null;
    };

    return $fp;
  }

  function _extract_code($res) {
    // Check return code
    // Note the return code will always be contained in the response, so
    // the we may not check the result of 'preg_match' - it matches always.
    //
    // A month later: nope, not always.
    //
    if (preg_match('/\s(\d+)\s/',$res,$matches)) {
      $result = $matches[1];
    } else {
      $result = "200";
    };

    return $result;
  }

  function _fix_location($location) {
    if (substr($location, 0, 7) == "http://") { return $location; };
    if (substr($location, 0, 8) == "https://") { return $location; };

    if ($location{0} == "/") {
      return $this->protocol."://".$this->host.$location;
    };

    return $this->protocol."://".$this->host.$this->path.$location;
  }

  function fetch($url) {
    /**
     * Handle empty $url value; unfortunaltely, parse_url will treat empty value as valid
     * URL, so fetcher will attempt to fetch something from the localhost instead of 
     * passing control to subsequent user-defined fetchers (which probably will know
     * how to handle this).
     */
    if ($url === "") {
      return null;
    }

    $this->url = $url;

    $parts = @parse_url($this->url);

    /**
     * If an malformed URL have been specified, add a message to the log file and 
     * continue processing (as such URLs may be found in otherwise good HTML file - 
     * for example, invalid image or CSS reference)
     */
    if ($parts == false) {
      error_log(sprintf("The URL '%s' could not be parsed", $this->url));

      $this->content = "";
      $this->code = HTTP_OK;
      return true;
    };
    
    /**
     * Setup default values
     */
    $this->protocol = 'http';
    $this->host = 'localhost';
    $this->user = "";
    $this->pass = "";
    $this->port = 80;
    $this->path = "/";
    $this->query = "";

    if (isset($parts['scheme']))   { $this->protocol  = $parts['scheme'];    };
    if (isset($parts['host']))     { $this->host      = $parts['host'];      };
    if (isset($parts['user']))     { $this->user      = $parts['user'];      };
    if (isset($parts['pass']))     { $this->pass      = $parts['pass'];      };
    if (isset($parts['port']))     { $this->port      = $parts['port'];      };
    if (isset($parts['path']))     { $this->path      = $parts['path'];      } else { $this->path = "/"; };
    if (isset($parts['query']))    { $this->path     .= '?'.$parts['query']; };

    switch ($this->protocol) {
    case 'http':
      return $this->fetch_http();
    case 'https':
      return $this->fetch_https();
    case 'file':
      $this->host = "";
      return $this->fetch_file();
    default:
      $message = sprintf("Unsupported protocol: %s", $this->protocol);
      error_log($message);
      $this->error_message .= $message;
      return null;
    }
  }

  function fetch_http() {
    $res = $this->_head();

    if (is_null($res)) { return null; };
    $this->code = $this->_extract_code($res);

    return $this->_process_code($res);
  }

  function fetch_https() {
    /**
     * SSL works via port 443
     */
    if ($this->protocol == "https" && !isset($parts['port'])) {
       $this->port = 443;
    }

    $res = $this->_head();

    if (is_null($res)) { return null; };
    $this->code = $this->_extract_code($res);

    return $this->_process_code($res);
  }

  function fetch_file() {
    if (PHP_OS == "WINNT") {
      $path = substr($this->url, 7);
      if ($path{0} == "/") { $path = substr($path, 1); };
    } else {
      $path = substr($this->url, 7);
    };

    $normalized_path = realpath(urldecode($path));

    if (substr($normalized_path, 0, strlen(FILE_PROTOCOL_RESTRICT)) !== FILE_PROTOCOL_RESTRICT) {
      error_log(sprintf("Access denied to file '%s'", $normalized_path));

      $this->content = "";
      $this->code = HTTP_OK;
      return true;
    }

    $this->content = @file_get_contents($normalized_path);
    $this->code = HTTP_OK;

    return true;
  }

  function _get() {
    $socket = $this->_connect();
    if (is_null($socket)) { return null; };

    // Build the HEAD request header (we're saying we're just a browser as some pages don't like non-standard user-agents)
    $header  = "GET ".$this->path." HTTP/1.1\r\n";
    $header .= "Host: ".$this->host."\r\n";
    $header .= "Accept: */*\r\n";
    $header .= "User-Agent: ".$this->user_agent."\r\n";
    $header .= "Connection: keep-alive\r\n";
    $header .= "Referer: ".$this->protocol."://".$this->host.$this->path."\r\n";   
    $header .= $this->_header_basic_authorization();
    $header .= "\r\n";

    fputs ($socket, $header);
    // Get the responce
    $res = "";

    // The PHP-recommended construction
    //    while (!feof($fp)) { $res .= fread($fp, 4096); };
    // hangs indefinitely on www.searchscout.com, for example.
    // seems that they do not close conection on their side or somewhat similar;

    // let's assume that there will be no HTML pages greater than 1 Mb

    $res = fread($socket, 1024*1024);

    // Close connection handle, we do not need it anymore
    fclose($socket);

    return $res;
  }

  function _head() {
    $socket = $this->_connect();

    if (is_null($socket)) { return null; };

    // Build the HEAD request header (we're saying we're just a browser as some pages don't like non-standard user-agents)
    $header  = "HEAD ".$this->path." HTTP/1.1\r\n";
    $header .= "Host: ".$this->host."\r\n";
    $header .= "Accept: */*\r\n";
    $header .= "User-Agent: ".$this->user_agent."\r\n";
    $header .= "Connection: keep-alive\r\n";
    $header .= "Accept: text/html\r\n";
    $header .= "Referer: ".$this->protocol."://".$this->host.$this->path."\r\n";

    $header .= $this->_header_basic_authorization();

    $header .= "\r\n";

    // Send the header
    fputs ($socket, $header);
    // Get the responce
    $res = "";

    // The PHP-recommended construction
    //    while (!feof($fp)) { $res .= fread($fp, 4096); };
    // hangs indefinitely on www.searchscout.com, for example.
    // seems that they do not close conection on their side or somewhat similar;

    // let's assume that there will be no HTML pages greater than 1 Mb

    $res = fread($socket, 4096);

    // Close connection handle, we do not need it anymore
    fclose($socket);

    return $res;
  }

  function _process_code($res, $used_get = false) {
    switch ($this->code) {
    case '200': // OK
      if (preg_match('/(.*?)\r\n\r\n(.*)/s',$res,$matches)) {
        $this->headers = $matches[1];
      };

      /**
       * @todo add error processing here
       * 
       * Note: file_get_contents is smart enough to use basic authorization headers provided 
       * user name / password are given in the URL.
       */
      $this->content = @file_get_contents($this->url);

      return true;
      break;
    case '301': // Moved Permanently
      $this->redirects++;
      if ($this->redirects > MAX_REDIRECTS) { return false; };
      preg_match('/Location: ([\S]+)/i',$res,$matches);
      return $this->fetch($this->_fix_location($matches[1]));
    case '302': // Found
      $this->redirects++;
      if ($this->redirects > MAX_REDIRECTS) { return false; };
      preg_match('/Location: ([\S]+)/i',$res,$matches);
      error_log('Redirected to:'.$matches[1]);

      return $this->fetch($this->_fix_location($matches[1]));
    case '400': // Bad request
    case '401': // Unauthorized
    case '402': // Payment required
    case '403': // Forbidden
    case '404': // Not found - but should return some html content - error page
    case '406': // Not acceptable
      if (!preg_match('/(.*?)\r\n\r\n(.*)/s',$res,$matches)) {
        error_log("Unrecognized HTTP response");
        return false;
      };
      $this->headers = $matches[1];
      $this->content = @file_get_contents($this->url);
      return true;
    case '405': // Method not allowed; some sites (like MSN.COM) do not like "HEAD" HTTP requests
      // Try to get URL information using GET request (if we didn't tried it before)
      if (!$used_get) {
        $res = $this->_get();
        if (is_null($res)) { return null; };
        $this->code = $this->_extract_code($res);
        return $this->_process_code($res, true);
      } else {
        if (!preg_match('/(.*?)\r\n\r\n(.*)/s',$res,$matches)) {
          error_log("Unrecognized HTTP response");
          return false;
        };
        $this->headers = $matches[1];
        $this->content = @file_get_contents($this->url);
        return true;
      };
    default:
      error_log("Unrecognized HTTP result code:".$this->code);
      return false;
    };
  }

  function _header_basic_authorization() {
    if (!is_null($this->user) && $this->user != "") {
      return sprintf("Authorization: Basic %s\r\n", base64_encode($this->user.":".$this->pass));
    };
  }
}
?>