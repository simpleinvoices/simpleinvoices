<?php
// $Header: /cvsroot/html2ps/utils_url.php,v 1.9 2006/07/09 09:07:46 Konstantin Exp $

function guess_url($path, $baseurl) {
  // Check if path is absolute
  // 'Path' is starting with protocol identifier?
  if (preg_match("!^[a-zA-Z]+://.*!",$path)) {
    return $path;
  };

  $data   = parse_url($baseurl);

  $default_host = array(
                        'http'  => 'localhost',
                        'https' => 'localhost',
                        'file'  => ''
                        );

  $base_scheme = isset($data['scheme']) ? $data['scheme']   : "http";
  $base_port   = isset($data['port'])   ? ":".$data['port'] : "";
  $base_user   = isset($data['user'])   ? $data['user']     : "";
  $base_pass   = isset($data['pass'])   ? $data['pass']     : "";
  $base_host   = isset($data['host'])   ? $data['host']     : (isset($default_host[$base_scheme]) ? $default_host[$base_scheme] : "");
  $base_path   = isset($data['path'])   ? $data['path']     : "/";

  /**
   * Workaround: Some PHP versions do remove the leading slash from the 
   * 'file://' URLs with empty host name, while some do not.
   *
   * An example of such URL is: file:///D:/path/dummy.html
   * The path should be: /D:/path/dummy.html
   * 
   * Here we check if the leading slash is present and
   * add it if it is missing.
   */
  if ($base_scheme == "file" && PHP_OS == "WINNT") {
    if (strlen($base_path) > 0) {
      if ($base_path{0} != "/") {
        $base_path = "/".$base_path;
      };
    };
  };

  $base_user_pass = "";
  if ($base_user || $base_pass) {
    $base_user_pass = sprintf("%s:%s@", $base_user, $base_pass);
  } 

  // 'Path' is starting at scheme?
  if (substr($path,0,2) == "//") {
    $guessed = $base_scheme . ':' . $path;
    return $guessed;
  }

  // 'Path' is starting at root?
  if (substr($path,0,1) == "/") {
    $guessed = $base_scheme . '://' . $base_user_pass . $base_host . $base_port . $path;
    return $guessed;
  };

  // 'Path' is relative from the current position
  if (preg_match("#^(/.*)/[^/]*$#", $base_path, $matches)) {
    $base_path_dir = $matches[1];
  } else {
    $base_path_dir = "";
  };
  $guessed = $base_scheme . '://' . $base_user_pass . $base_host . $base_port . $base_path_dir . '/' . $path;
  return $guessed;
};

?>