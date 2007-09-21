<?php

/**
 * "Singleton"
 */
class CSSCache {
  function get() {
    global $__g_css_manager;

    if (!isset($__g_css_manager)) {
      $__g_css_manager = new CSSCache();
    };

    return $__g_css_manager;
  }

  function _getCacheFilename($url) {
    return CACHE_DIR.md5($url).'.css.compiled';
  }

  function _isCached($url) {
    $cache_filename = $this->_getCacheFilename($url);
    return is_readable($cache_filename);
  }

  function _readCached($url) {
    $cache_filename = $this->_getCacheFilename($url);
    return unserialize(file_get_contents($cache_filename));
  }

  function _putCached($url, $css) {
    file_put_contents($this->_getCacheFilename($url), serialize($css));
  }

  function compile($url, $css) {
    if ($this->_isCached($url)) {
      return $this->_readCached($url);
    } else {
      $cssruleset = new CSSRuleset();
      $cssruleset->parse_css($css, new Pipeline());
      $this->_putCached($url, $cssruleset);
      return $cssruleset;
    };
  }
}

?>