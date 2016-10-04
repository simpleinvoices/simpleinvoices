<?php
// $Header: /cvsroot/html2ps/xhtml.autoclose.inc.php,v 1.5 2005/07/28 17:04:33 Konstantin Exp $

function autoclose_tag(&$sample_html, $offset, $tags, $nested, $close) {
  $tags = mk_open_tag_regexp($tags);

  while (preg_match("#^(.*?)({$tags})#is", substr($sample_html, $offset),$matches)) {
    // convert tag name found to lower case
    $tag = strtolower($matches[3]);
    // calculate position of the tag found
    $tag_start = $offset + strlen($matches[1]);
    $tag_end   = $tag_start + strlen($matches[2]);

    if ($tag == $close) { return $tag_end; };
    
    // REQ: PHP 4.0.5
    if (isset($nested[$tag])) {
      $offset = $nested[$tag]($sample_html, $tag_end);
    } else {
      $to_be_inserted = "<".$close.">";

      $sample_html = substr_replace($sample_html, $to_be_inserted, $tag_start ,0);
      return $tag_start + strlen($to_be_inserted);
    };
  };
  
  return $offset;
}

// removes from current html string a piece from the current $offset to 
// the beginning of next $tag; $tag should contain a '|'-separated list
// of opening or closing tags. This function is useful for cleaning up
// messy code containing trash between TD, TR and TABLE tags.
function skip_to(&$html, $offset, $tag) {
  $prefix = substr($html,0,$offset);
  $suffix = substr($html,$offset);

  if (preg_match("#^(.*?)<\s*({$tag})#is", $suffix, $matches)) {
    $suffix = substr($suffix, strlen($matches[1]));
  };

  $html = $prefix . $suffix;
}

function autoclose_tag_cleanup(&$sample_html, $offset, $tags_raw, $nested, $close) {
  $tags = mk_open_tag_regexp($tags_raw);
  skip_to($sample_html, $offset, $tags_raw);

  while (preg_match("#^(.*?)({$tags})#is", substr($sample_html, $offset),$matches)) {
    // convert tag name found to lower case
    $tag = strtolower($matches[3]);
    // calculate position of the tag found
    $tag_start = $offset + strlen($matches[1]);
    $tag_end   = $tag_start + strlen($matches[2]);

    if ($tag == $close) { return $tag_end; };
    
    // REQ: PHP 4.0.5
    if (isset($nested[$tag])) {
      $offset = $nested[$tag]($sample_html, $tag_end);
    } else {
      $to_be_inserted = "<".$close.">";

      $sample_html = substr_replace($sample_html, $to_be_inserted, $tag_start ,0);
      return $tag_start + strlen($to_be_inserted);
    };

    skip_to($sample_html, $offset, $tags_raw);
  };
  
  return $offset;
}

?>