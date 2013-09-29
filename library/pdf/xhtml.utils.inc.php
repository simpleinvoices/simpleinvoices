<?php
// $Header: /cvsroot/html2ps/xhtml.utils.inc.php,v 1.35 2007/03/15 18:37:36 Konstantin Exp $

function close_tag($tag, $sample_html) {
  return preg_replace("!(<{$tag}(\s[^>]*[^/>])?)>!si","\\1/>",$sample_html);
};

function make_attr_value($attr, $html) {
  return preg_replace("#(<[^>]*\s){$attr}(\s|>|/>)#si","\\1{$attr}=\"{$attr}\"\\2",$html);
};


function mk_open_tag_regexp($tag) { return "<\s*{$tag}(\s+[^>]*)?>"; };
function mk_close_tag_regexp($tag) { return "<\s*/\s*{$tag}\s*>"; };

function process_html($html) {
  $open  = mk_open_tag_regexp("html");
  $close = mk_close_tag_regexp("html");

  if (!preg_match("#{$open}#is",$html)) {
    $html = "<html>".$html;
  };

  /**
   * Let's check if there's more than one <html> tags inside the page text
   * If there are, remove everything except the first one and content between the first and second <html>
   */
  while (preg_match("#{$open}(.*?){$open}#is", $html)) {
    $html = preg_replace("#{$open}(.*?){$open}#is", "<html>\\2", $html);
  };

  if (!preg_match("#{$close}#is", $html)) {
    $html = $html."</html>";
  };

  // PHP 5.2.0 compatilibty issue
  // preg_replace may accidentally return NULL on large files not matching this 
  $html = preg_replace("#.*({$open})#is","\\1",$html);

  // PHP 5.2.0 compatilibty issue
  // preg_replace may accidentally return NULL on large files not matching this 

  // Cut off all data before and after 'html' tag; unless we'll do it,
  // the XML parser will die violently
  $html = preg_replace("#^.*<html#is","<html",$html);

  $html = preg_replace("#</html\s*>.*$#is","</html>",$html);

  return $html;
}

function process_head($html) {
  $open  = mk_open_tag_regexp("head");
  $close = mk_close_tag_regexp("head");
  $ohtml = mk_open_tag_regexp("html");
  $chtml = mk_close_tag_regexp("html");
  $obody = mk_open_tag_regexp("body");

  if (!preg_match("#{$open}#is",$html)) {
    $html = preg_replace("#({$ohtml})(.*)({$obody})#is","\\1<head>\\3</head>\\4",$html);
  } elseif (!preg_match("#{$close}#is",$html)) {
    if (preg_match("#{$obody}#is",$html)) {
      $html = preg_replace("#({$obody})#is","</head>\\1",$html);
    } else {
      $html = preg_replace("#({$chtml})#is","</head>\\1",$html);
    };
  };
  return $html;
}

function process_body($html) {
  $open  = mk_open_tag_regexp("body");
  $close = mk_close_tag_regexp("body");
  $ohtml = mk_open_tag_regexp("html");
  $chtml = mk_close_tag_regexp("html");
  $chead = mk_close_tag_regexp("head");

  if (!preg_match("#{$open}#is",$html)) {
    if (preg_match("#{$chead}#is",$html)) {
      $html = preg_replace("#({$chead})#is","\\1<body>",$html);
    } else {
      $html = preg_replace("#({$ohtml})#is","\\1<body>",$html);
    };
  };
  if (!preg_match("#{$close}#is",$html)) {
    $html = preg_replace("#({$chtml})#is","</body>\\1",$html);
  };

  // Now check is there any data between </head> and <body>.
  $html = preg_replace("#({$chead})(.+)({$open})#is","\\1\\3\\2",$html);
  // Check if there's any data between </body> and </html>
  $html = preg_replace("#({$close})(.+)({$chtml})#is","\\2\\1\\3",$html);

  return $html;
}

// Hmmm. May be we'll just write SAX parser on PHP? ;-)
function fix_tags($html) {
  $result = "";
  $tag_stack = array();

  // these corrections can simplify the regexp used to parse tags
  // remove whitespaces before '/' and between '/' and '>' in autoclosing tags
  $html = preg_replace("#\s*/\s*>#is","/>",$html);
  // remove whitespaces between '<', '/' and first tag letter in closing tags
  $html = preg_replace("#<\s*/\s*#is","</",$html);
  // remove whitespaces between '<' and first tag letter 
  $html = preg_replace("#<\s+#is","<",$html);

  while (preg_match("#(.*?)(<([a-z\d]+)[^>]*/>|<([a-z\d]+)[^>]*(?<!/)>|</([a-z\d]+)[^>]*>)#is",$html,$matches)) {
    $result .= $matches[1];
    $html = substr($html, strlen($matches[0]));

    // Closing tag 
    if (isset($matches[5])) { 
      $tag = $matches[5];

      if ($tag == $tag_stack[0]) {
        // Matched the last opening tag (normal state) 
        // Just pop opening tag from the stack
        array_shift($tag_stack);
        $result .= $matches[2];
      } elseif (array_search($tag, $tag_stack)) { 
        // We'll never should close 'table' tag such way, so let's check if any 'tables' found on the stack
        $no_critical_tags = !array_search('table',$tag_stack);
        if (!$no_critical_tags) {
          $no_critical_tags = (array_search('table',$tag_stack) >= array_search($tag, $tag_stack));
        };

        if ($no_critical_tags) {
          // Corresponding opening tag exist on the stack (somewhere deep)
          // Note that we can forget about 0 value returned by array_search, becaus it is handled by previous 'if'
          
          // Insert a set of closing tags for all non-matching tags
          $i = 0;
          while ($tag_stack[$i] != $tag) {
            $result .= "</{$tag_stack[$i]}> ";
            $i++;
          }; 
          
          // close current tag
          $result .= "</{$tag_stack[$i]}> ";
          // remove it from the stack
          array_splice($tag_stack, $i, 1);
          // if this tag is not "critical", reopen "run-off" tags
          $no_reopen_tags = array("tr","td","table","marquee","body","html");
          if (array_search($tag, $no_reopen_tags) === false) {
            while ($i > 0) {
              $i--;
              $result .= "<{$tag_stack[$i]}> ";
            }; 
          } else {
            array_splice($tag_stack, 0, $i);
          };
        };
      } else {
        // No such tag found on the stack, just remove it (do nothing in out case, as we have to explicitly 
        // add things to result
      };
    } elseif (isset($matches[4])) {
      // Opening tag
      $tag = $matches[4];
      array_unshift($tag_stack, $tag);
      $result .= $matches[2];
    } else {
      // Autoclosing tag; do nothing specific
      $result .= $matches[2];
    };
  };

  // Close all tags left
  while (count($tag_stack) > 0) {
    $tag = array_shift($tag_stack);
    $result .= "</".$tag.">";
  }

  return $result;
}

/**
 * This function adds quotes to attribute values; it attribute values already have quotes, no changes are made
 */
function quote_attrs($html) {
  while (preg_match("!(<[^>]*)\s([^=>]+)=([^'\"\r\n >]+)([\r\n >])!si",$html, $matches)) {
    $html = preg_replace("#(<[^>]*)\s([^=>]+)=([^'\"\r\n >]+)([\r\n >])#si","\\1 \\2='\\3'\\4",$html);
  };
  return $html;
};

function escape_attr_value_entities($html) {
  $html = str_replace("<","&lt;",$html);
  $html = str_replace(">","&gt;",$html);

  // Replace all character references by their decimal codes
  process_character_references($html);
  $html = escape_amp($html);
  return $html;
}

/**
 * Updates attribute values: if there's any unescaped <, > or & symbols inside an attribute value,
 * replaces them with corresponding entity. Also note that & should not be escaped if it is already the part
 * of entity reference
 * 
 * @param String $html source HTML code
 * @return String updated HTML code
 */
function escape_attrs_entities($html) {
  $result = "";

  // Regular expression may be described as follows:
  // (<[^>]*) - something starting with < (i.e. tag name and, probably, some attribute name/values pairs
  // \s([^\s=>]+)= - space after "something", followed by attribute name (which may contain anything except spaces, = and > signs
  // (['\"])([^\3]*?)\3 - quoted attribute value; (@todo won't work with escaped quotes inside value, by the way).
  while (preg_match("#^(.*)(<[^>]*)\s([^\s=>]+)=(['\"])([^\\4]*?)\\4(.*)$#si", $html, $matches)) {
    $new_value = escape_attr_value_entities($matches[5]);

    $result .= $matches[1].$matches[2]." ".$matches[3]."=".$matches[4].$new_value.$matches[4];
    $html = $matches[6];
  };

  return $result.$html;
};

function fix_attrs_spaces(&$html) {
  while (preg_match("#(<[^>]*)\s([^\s=>]+)=\"([^\"]*?)\"([^\s])#si", $html)) {
    $html = preg_replace("#(<[^>]*)\s([^\s=>]+)=\"([^\"]*?)\"([^\s])#si","\\1 \\2=\"\\3\" \\4",$html);
  };

  while (preg_match("#(<[^>]*)\s([^\s=>]+)='([^']*?)'([^\s])#si", $html)) {
    $html = preg_replace("#(<[^>]*)\s([^\s=>]+)='([^']*?)'([^\s])#si","\\1 \\2='\\3' \\4",$html);
  };
}

function fix_attrs_tag($tag) {
  if (preg_match("#(<)(.*?)(/\s*>)#is",$tag, $matches)) {
    $prefix  = $matches[1];
    $suffix  = $matches[3];
    $content = $matches[2];
  } elseif (preg_match("#(<)(.*?)(>)#is",$tag, $matches)) {
    $prefix  = $matches[1];
    $suffix  = $matches[3];
    $content = $matches[2];
  } else {
    return;
  };

  if (preg_match("#^\s*(\w+)\s*(.*)\s*/\s*\$#is", $content, $matches)) {
    $tagname   = $matches[1];
    $raw_attrs = isset($matches[2]) ? $matches[2] : "";
  } elseif (preg_match("#^\s*(\w+)\s*(.*)\$#is", $content, $matches)) {
    $tagname   = $matches[1];
    $raw_attrs = isset($matches[2]) ? $matches[2] : "";
  } else {
    // A strange tag occurred; just remove everything
    $tagname   = "";
    $raw_attrs = "";
  };

  $attrs = array();
  while (!empty($raw_attrs)) {
    if (preg_match("#^\s*(\w+?)\s*=\s*\"(.*?)\"(.*)$#is",$raw_attrs,$matches)) {
      $attr  = strtolower($matches[1]);
      $value = $matches[2];

      if (!isset($attrs[$attr])) {
        $attrs[$attr] = $value;
      };

      $raw_attrs = $matches[3];
    } elseif (preg_match("#^\s*(\w+?)\s*=\s*'(.*?)'(.*)$#is",$raw_attrs,$matches)) {
      $attr  = strtolower($matches[1]);
      $value = $matches[2];

      if (!isset($attrs[$attr])) {
        $attrs[$attr] = $value;
      };

      $raw_attrs = $matches[3];
    } elseif (preg_match("#^\s*(\w+?)=(\w+)(.*)$#is",$raw_attrs,$matches)) {
      $attr  = strtolower($matches[1]);
      $value = $matches[2];

      if (!isset($attrs[$attr])) {
        $attrs[$attr] = $value;
      };

      $raw_attrs = $matches[3];
    } elseif (preg_match("#^\s*\S+\s+(.*)$#is",$raw_attrs,$matches)) {
      // Just a junk at the beginning; skip till the first space
      $raw_attrs = $matches[1];
    } else {
      $raw_attrs = "";
    };
  };

  $str = "";
  foreach ($attrs as $key => $value) {
    // In theory, if the garbage have been found inside the attrs section, we could get
    // and invalid attribute name here; just ignore them in this case
    if (HTML2PS_XMLUtils::valid_attribute_name($key)) {     
      if (strpos($value,'"') !== false) {
        $str .= " ".$key."='".$value."'";
      } else {
        $str .= " ".$key."=\"".$value."\"";
      };
    };
  };

  return $prefix.$tagname.$str.$suffix;
}

function fix_attrs($html) {
  $result = "";

  while (preg_match("#^(.*?)(<[^/].*?>)#is",$html,$matches)) {
    $result .= $matches[1].fix_attrs_tag($matches[2]);
    $html = substr($html, strlen($matches[0]));
  };

  return $result.$html;
}

function fix_closing_tags($html) {
  return preg_replace("#</\s*(\w+).*?>#","</\\1>",$html);
}

function process_pagebreak_commands(&$html) {
  $html = preg_replace("#<\?page-break>|<!--NewPage-->#","<pagebreak/>",$html);
}

function xhtml2xhtml($html) {
  process_pagebreak_commands($html);

  // Remove STYLE tags for the same reason and store them in the temporary variable
  // later they will be added back to HEAD section
  $styles = process_style($html);

  // Do HTML -> XML (XHTML) conversion
  // Convert HTML character references to their Unicode analogues
  process_character_references($html);
 
  remove_comments($html);

  // Convert all tags to lower case
  $html = lowercase_tags($html);
  $html = lowercase_closing_tags($html);

  // Remove SCRIPT tags
  $html = process_script($html);

  $html = insert_styles($html, $styles);

  return $html;
}

function html2xhtml($html) {
  process_pagebreak_commands($html);

  // Remove SCRIPT tags from the page being processed, as script content may 
  // mess the firther html-parsing utilities 
  $html = process_script($html);

  // Remove STYLE tags for the same reason and store them in the temporary variable
  // later they will be added back to HEAD section
  $styles = process_style($html);

  // Convert HTML character references to their Unicode analogues
  process_character_references($html);

  remove_comments($html);

  fix_attrs_spaces($html);
  $html = quote_attrs($html);
  $html = escape_attrs_entities($html);

  $html = lowercase_tags($html);
  $html = lowercase_closing_tags($html);

  $html = fix_closing_tags($html);

  $html = close_tag("area",$html);
  $html = close_tag("base",$html);
  $html = close_tag("basefont",$html);
  $html = close_tag("br",$html);
  $html = close_tag("col",$html);
  $html = close_tag("embed",$html);
  $html = close_tag("frame",$html);
  $html = close_tag("hr",$html);
  $html = close_tag("img",$html);
  $html = close_tag("input",$html);
  $html = close_tag("isindex",$html);
  $html = close_tag("link",$html);
  $html = close_tag("meta",$html);
  $html = close_tag("param",$html);

  $html = make_attr_value("checked",$html);
  $html = make_attr_value("compact",$html);
  $html = make_attr_value("declare",$html);
  $html = make_attr_value("defer",$html);
  $html = make_attr_value("disabled",$html);
  $html = make_attr_value("ismap",$html);
  $html = make_attr_value("multiple",$html);
  $html = make_attr_value("nohref",$html);
  $html = make_attr_value("noresize",$html);
  $html = make_attr_value("noshade",$html);
  $html = make_attr_value("nowrap",$html);
  $html = make_attr_value("readonly",$html);
  $html = make_attr_value("selected",$html);

  $html = process_html($html);
  $html = process_body($html);

  $html = process_head($html);
  $html = process_p($html);

  $html = escape_amp($html);
  $html = escape_lt($html);
  $html = escape_gt($html);

  $html = escape_textarea_content($html);

  process_tables($html,0);

  process_lists($html,0);
  process_deflists($html,0);
  process_selects($html,0);

  $html = fix_tags($html);
  $html = fix_attrs($html);

  $html = insert_styles($html, $styles);

  return $html;
}

function escape_textarea_content($html) {
  preg_match_all('#<textarea(.*)>(.*)<\s*/\s*textarea\s*>#Uis', $html, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

  // Why cycle from the last to first match? 
  // It will keep unprocessed matches offsets valid, 
  // as escaped content may differ from original content in length, 
  for ($i = count($matches)-1; $i>=0; $i--) {
    $match = $matches[$i];
    $match_offset = $match[2][1];
    $match_content = $match[2][0];
    $match_length = strlen($match_content);
    $escaped_content = preg_replace('/&([^#])/', '&#38;\1',
                                    str_replace('>', '&#62;',
                                                str_replace('<', '&#60;', $match_content)));
    $html = substr_replace($html, $escaped_content, $match_offset, $match_length);
  };

  return $html;
}

function lowercase_tags($html) {
  $result = "";

  while (preg_match("#^(.*?)(</?)([a-zA-z0-9]+)([\s>])#is",$html,$matches)) {
    // Drop extracted part
    $html = substr($html,strlen($matches[0]));
    // Move extracted part to the result
    $result .= $matches[1].$matches[2].strtolower($matches[3]).$matches[4];
  };

  return $result.$html;
};

function lowercase_closing_tags($html) {
  $result = "";

  while (preg_match("#^(.*?)(<)([a-zA-z0-9]+)(\s*/\s*>)#is",$html,$matches)) {
    // Drop extracted part
    $html = substr($html,strlen($matches[0]));
    // Move extracted part to the result
    $result .= $matches[1].$matches[2].strtolower($matches[3]).$matches[4];
  };

  return $result.$html;
};

?>