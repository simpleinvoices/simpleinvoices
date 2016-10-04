<?php

class CSSRuleset {
  var $rules;
  var $tag_filtered;
  var $_lastId;

  function CSSRuleset() {
    $this->rules        = array();
    $this->tag_filtered = array();
    $this->_lastId      = 0;
  }

  function parse_style_node($root, &$pipeline) {
    // Check if this style node have 'media' attribute 
    // and if we're using this media;
    //
    // Note that, according to the HTML 4.01 p.14.2.3 
    // This attribute specifies the intended destination medium for style information. 
    // It may be a single media descriptor or a comma-separated list.
    // The default value for this attribute is "screen".
    //
    $media_list = array("screen");
    if ($root->has_attribute("media")) {
      // Note that there may be whitespace symbols around commas, so we should not just use 'explode' function
      $media_list = preg_split("/\s*,\s*/",trim($root->get_attribute("media")));
    };

    if (!is_allowed_media($media_list)) { 
      if (defined('DEBUG_MODE')) {
        error_log(sprintf('No allowed (%s) media types found in CSS stylesheet media types (%s). Stylesheet ignored.',
                          join(',', config_get_allowed_media()),
                          join(',', $media_list)));
      };
      return; 
    };

    if (!isset($GLOBALS['g_stylesheet_title']) || 
        $GLOBALS['g_stylesheet_title'] === "") {
      $GLOBALS['g_stylesheet_title'] = $root->get_attribute("title");
    };

    if (!$root->has_attribute("title") || $root->get_attribute("title") === $GLOBALS['g_stylesheet_title']) {
      /**
       * Check if current node is empty (then, we don't need to parse its contents)
       */
      $content = trim($root->get_content());
      if ($content != "") {
        $this->parse_css($content, $pipeline);
      };
    };
  }

  function scan_styles($root, &$pipeline) {
    switch ($root->node_type()) {
    case XML_ELEMENT_NODE:
      $tagname = strtolower($root->tagname());

      if ($tagname === 'style') {
        // Parse <style ...> ... </style> nodes
        //
        $this->parse_style_node($root, $pipeline);

      } elseif ($tagname === 'link') {
        // Parse <link rel="stylesheet" ...> nodes
        //
        $rel   = strtolower($root->get_attribute("rel"));
      
        $type  = strtolower($root->get_attribute("type"));
        if ($root->has_attribute("media")) {
          $media = explode(",",$root->get_attribute("media"));
        } else {
          $media = array();
        };
      
        if ($rel == "stylesheet" && 
            ($type == "text/css" || $type == "") &&
            (count($media) == 0 || is_allowed_media($media)))  {
          // Attempt to escape URL automaticaly
          $url_autofix = new AutofixUrl();
          $src = $url_autofix->apply(trim($root->get_attribute('href')));

          if ($src) {
            $this->css_import($src, $pipeline);
          };
        };
      };

      // Note that we continue processing here!
    case XML_DOCUMENT_NODE:

      // Scan all child nodes
      $child = $root->first_child();
      while ($child) {
        $this->scan_styles($child, $pipeline);
        $child = $child->next_sibling();
      };
      break;
    };
  }

  function parse_css($css, &$pipeline, $baseindex = 0) {
    $allowed_media = implode("|",config_get_allowed_media());

    // remove the UTF8 byte-order mark from the beginning of the file (several high-order symbols at the beginning)
    $pos = 0;
    $len = strlen($css);
    while (ord($css{$pos}) > 127 && $pos < $len) { $pos ++; };
    $css = substr($css, $pos);

    // Process @media rules; 
    // basic syntax is:
    // @media <media>(,<media>)* { <rules> }
    //

    while (preg_match("/^(.*?)@media([^{]+){(.*)$/s",$css,$matches)) {
      $head  = $matches[1];
      $media = $matches[2];
      $rest  = $matches[3];

      // Process CSS rules placed before the first @media declaration - they should be applied to 
      // all media types
      //
      $this->parse_css_media($head, $pipeline, $baseindex);

      // Extract the media content
      if (!preg_match("/^((?:[^{}]*{[^{}]*})*)[^{}]*\s*}(.*)$/s", $rest, $matches)) {
        die("CSS media syntax error\n");
      } else {
        $content = $matches[1];
        $tail    = $matches[2];
      };

      // Check if this media is to be processed
      if (preg_match("/".$allowed_media."/i", $media)) {
        $this->parse_css_media($content, $pipeline, $baseindex);
      };

      // Process the rest of CSS file
      $css = $tail;
    };

    // The rest of CSS file belogs to common media, process it too
    $this->parse_css_media($css, $pipeline, $baseindex);
  }

  function css_import($src, &$pipeline) {
    // Update the base url; 
    // all urls will be resolved relatively to the current stylesheet url
    $url = $pipeline->guess_url($src);
    $data = $pipeline->fetch($url);

    /**
     * If referred file could not be fetched return immediately
     */
    if (is_null($data)) { return; };

    $css = $data->get_content();
    if (!empty($css)) { 
      /**
       * Sometimes, external stylesheets contain <!-- and --> at the beginning and 
       * at the end; we should remove these characters, as they may break parsing of 
       * first and last rules
       */
      $css = preg_replace('/^\s*<!--/', '', $css);
      $css = preg_replace('/-->\s*$/', '', $css);

      $this->parse_css($css, $pipeline); 
    };
  
    $pipeline->pop_base_url();
  }

  function parse_css_import($import, &$pipeline) {
    if (preg_match("/@import\s+[\"'](.*)[\"'];/",$import, $matches)) {
      // @import "<url>"
      $this->css_import(trim($matches[1]), $pipeline);
    } elseif (preg_match("/@import\s+url\((.*)\);/",$import, $matches)) {
      // @import url()
      $this->css_import(trim(css_remove_value_quotes($matches[1])), $pipeline);
    } elseif (preg_match("/@import\s+(.*);/",$import, $matches)) {
      // @import <url>
      $this->css_import(trim(css_remove_value_quotes($matches[1])), $pipeline);
    };
  }

  function parse_css_media($css, &$pipeline, $baseindex = 0) {
    // Remove comments
    $css = preg_replace("#/\*.*?\*/#is","",$css);

    // Extract @page rules
    $css = parse_css_atpage_rules($css, $pipeline);

    // Extract @import rules
    if ($num = preg_match_all("/@import[^;]+;/",$css, $matches, PREG_PATTERN_ORDER)) {
      for ($i=0; $i<$num; $i++) {
        $this->parse_css_import($matches[0][$i], $pipeline);
      }
    };

    // Remove @import rules so they will not break further processing
    $css = preg_replace("/@import[^;]+;/","", $css);

    while (preg_match("/([^{}]*){(.*?)}(.*)/is", $css, $matches)) {
      // Drop extracted part
      $css = $matches[3];

      // Save extracted part
      $raw_selectors  = $matches[1];
      $raw_properties = $matches[2];

      $selectors  = parse_css_selectors($raw_selectors);

      $properties = parse_css_properties($raw_properties, $pipeline);

      foreach ($selectors as $selector) {
        $this->_lastId ++;
        $rule = array($selector, 
                      $properties, 
                      $pipeline->get_base_url(),
                      $this->_lastId + $baseindex);
        $this->add_rule($rule,
                        $pipeline);
      };
    };
  }
  
  function add_rule(&$rule, &$pipeline) {
    $rule_obj      = new CSSRule($rule, $pipeline);
    $this->rules[] = $rule_obj;

    $tag = $this->detect_applicable_tag($rule_obj->get_selector());
    if (is_null($tag)) { 
      $tag = "*"; 
    }
    $this->tag_filtered[$tag][] = $rule_obj;
  }

  function apply(&$root, &$state, &$pipeline) {
    $local_css = array();

    if (isset($this->tag_filtered[strtolower($root->tagname())])) {
      $local_css = $this->tag_filtered[strtolower($root->tagname())];
    };

    if (isset($this->tag_filtered["*"])) {
      $local_css = array_merge($local_css, $this->tag_filtered["*"]);
    };

    $applicable = array();

    foreach ($local_css as $rule) {
      if ($rule->match($root)) {
        $applicable[] = $rule;
      };
    };

    usort($applicable, "cmp_rule_objs");

    foreach ($applicable as $rule) {
      switch ($rule->get_pseudoelement()) {
      case SELECTOR_PSEUDOELEMENT_BEFORE:
        $handler =& CSS::get_handler(CSS_HTML2PS_PSEUDOELEMENTS);
        $handler->replace($handler->get($state->getState()) | CSS_HTML2PS_PSEUDOELEMENTS_BEFORE, $state);
        break;
      case SELECTOR_PSEUDOELEMENT_AFTER:
        $handler =& CSS::get_handler(CSS_HTML2PS_PSEUDOELEMENTS);
        $handler->replace($handler->get($state->getState()) | CSS_HTML2PS_PSEUDOELEMENTS_AFTER, $state);
        break;
      default:
        $rule->apply($root, $state, $pipeline);
        break;
      };
    };
  }

  function apply_pseudoelement($element_type, &$root, &$state, &$pipeline) {
    $local_css = array();

    if (isset($this->tag_filtered[strtolower($root->tagname())])) {
      $local_css = $this->tag_filtered[strtolower($root->tagname())];
    };

    if (isset($this->tag_filtered["*"])) {
      $local_css = array_merge($local_css, $this->tag_filtered["*"]);
    };

    $applicable = array();

    for ($i=0; $i<count($local_css); $i++) {
      $rule =& $local_css[$i];
      if ($rule->get_pseudoelement() == $element_type) {
        if ($rule->match($root)) {
          $applicable[] =& $rule;
        };
      };
    };

    usort($applicable, "cmp_rule_objs");

    // Note that filtered rules already have pseudoelement mathing (see condition above)

    foreach ($applicable as $rule) {
      $rule->apply($root, $state, $pipeline);
    };
  }
  
  // Check if only tag with a specific name can match this selector
  //
  function detect_applicable_tag($selector) {
    switch (selector_get_type($selector)) {
    case SELECTOR_TAG:
      return $selector[1];
    case SELECTOR_TAG_CLASS:
      return $selector[1];
    case SELECTOR_SEQUENCE:
      foreach ($selector[1] as $subselector) {
        $tag = $this->detect_applicable_tag($subselector);
        if ($tag) { return $tag; };
      };
      return null;
    default: 
      return null;
    }
  }
}

?>