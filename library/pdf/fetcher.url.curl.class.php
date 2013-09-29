<?php

require_once(HTML2PS_DIR.'fetcher._interface.class.php');

/**
 * This class handles fetching HTTP code using CURL extension
 */
class FetcherUrlCurl extends Fetcher {
  /**
   * @var String URL being fetched
   * @access private
   */
  var $url;
  var $_proxy;

  function FetcherUrlCurl() {
    $this->url = "";
    $this->set_proxy(null);
  }

  function _fix_url($url) {
    // If only host name was specified, add trailing slash 
    // (e.g. replace http://www.google.com with http://www.google.com/
    if (preg_match('#^.*://[^/]+$#', $url)) {
      $url .= '/';
    };

    return $url;
  }

  function get_base_url() {
    return $this->url;
  }

  function get_data($url) {
    $this->url = $url;

    // URL to be fetched
    $curl = curl_init();

    $fixed_url = $this->_fix_url($url);

    curl_setopt($curl, CURLOPT_URL, $fixed_url);
    curl_setopt($curl, CURLOPT_USERAGENT, DEFAULT_USER_AGENT);

    if (!@curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1)) {
      error_log('CURLOPT_FOLLOWLOCATION will not work in safe_mode; pages with redirects may be rendered incorrectly');
    };

    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $proxy = $this->get_proxy();
    if (!is_null($proxy)) {
      curl_setopt($curl, CURLOPT_PROXY, $proxy);
    };
    
    /**
     * Fetch headers and page content to the $response variable
     * and close CURL session
     */
    $response = curl_exec($curl);
    
    if ($response === FALSE) {
      error_log(sprintf('Cannot open %s, CURL error is: %s',
                        $url,
                        curl_error($curl)));
      curl_close($curl);
      return null;
    }

    curl_close($curl);
  
    /**
     * According to HTTP standard, headers block separated from 
     * body block with empty line - '\r\n\r\n' sequence. As body
     * might contain this sequence too, we should use 'non-greedy' 
     * modifier on the first group in the regular expression. 
     * Of course, we should process the response as a whole using 
     * 's' modifier.
     */
    preg_match('/^(.*?)\r\n\r\n(.*)$/s', $response, $matches);

    /**
     * Usually there's more than one line in a header block,
     * separated with '\r\n' sequence.
     *
     * The very first line contains HTTP response code (e.g. HTTP/1.1 200 OK),
     * so we may safely ignore it. 
     */
    $headers = array_slice(explode("\r\n", $matches[1]),1);
    $content = $matches[2];

    return new FetchedDataURL($content, $headers, $this->url);
  }

  function get_proxy() {
    return $this->_proxy;
  }

  function set_proxy($proxy) {
    $this->_proxy = $proxy;
  }
}
?>