<?php

class AutofixUrl {
  function AutofixUrl() {
  }

  function apply($url) {
    $parts = @parse_url($url);
    if ($parts === FALSE) {
      return null;
    };

    /*
     * Check if path contains only RFC1738 compliant symbols and fix it
     * No graphic: 00-1F, 7F, 80-FF
     * Unsafe: 'space',<>"#%{}|\^~[]`
     * Reserved: ;/?:@=&
     *
     * Normally, slash is allowed in path part, and % may be a part of encoded character
     */
    $no_graphic_found = preg_match('/[\x00-\x1F\x7F\x80-\xFF]/', $parts['path']);
    $unsafe_found = preg_match('/[ <>\"#{}\|\^~\[\]`]/', $parts['path']);
    $unsafe_percent_found = preg_match('/%[^\dA-F]|%\d[^\dA-F]/i', $parts['path']);
    $reserved_found = preg_match('/;\?:@=&/', $parts['path']);

    if ($no_graphic_found || 
        $unsafe_found || 
        $unsafe_percent_found || 
        $reserved_found) {
      $parts['path'] = join('/', array_map('rawurlencode', explode('/',$parts['path'])));
    };

    // Build updated URL
    $url_fixed = '';

    if (isset($parts['scheme'])) {
      $url_fixed .= $parts['scheme'];
      $url_fixed .= '://';

      if (isset($parts['user'])) {
        $url_fixed .= $parts['user'];
        if (isset($parts['pass'])) {
          $url_fixed .= ':';
          $url_fixed .= $parts['pass'];
        };
        $url_fixed .= '@';
      };
      
      if (isset($parts['host'])) {
        $url_fixed .= $parts['host'];
      };
      
      if (isset($parts['port'])) {
        $url_fixed .= ':';
        $url_fixed .= $parts['port'];
      };
    };
    
    if (isset($parts['path'])) {
      $url_fixed .= $parts['path'];
    };
   
    if (isset($parts['query'])) {
      $url_fixed .= '?';
      $url_fixed .= $parts['query'];
    };

    if (isset($parts['fragment'])) {
      $url_fixed .= '#';
      $url_fixed .= $parts['fragment'];
    };

    return $url_fixed;
  }
}

?>