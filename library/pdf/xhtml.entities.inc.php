<?php
// $Header: /cvsroot/html2ps/xhtml.entities.inc.php,v 1.11 2006/12/24 14:42:44 Konstantin Exp $

function process_character_references(&$html) {
  // Process symbolic character references
  global $g_html_entities;
  foreach ($g_html_entities as $entity => $code) {
    $html = str_replace("&{$entity};","&#{$code};",$html);

    // Some ill-brained webmasters write HTML symbolic references without 
    // terminating semicolor (especially at www.whitehouse.gov. The following 
    // replacemenet is required to fix these damaged inteties, converting them 
    // to the numerical character reference.
    //
    // We use [\s<] as entity name terminator to avoid breaking up longer entity
    // names by filtering in only space or HTML-tag terminated ones.
    // 
    $html = preg_replace("/&{$entity}([\s<])/","&#{$code};\\1",$html);
  };

  // Process hecadecimal character references
  while (preg_match("/&#x([[:xdigit:]]{2,4});/i", $html, $matches)) {
    // We cannot use plain str_replace, because 'x' symbol can be in both cases;
    // str_ireplace have appeared in PHP 5 only, so we cannot use it due the 
    // compatibility problems

    $html = preg_replace("/&#x".$matches[1].";/i","&#".hexdec($matches[1]).";",$html);
  };
}

function escape_amp($html) {
  // Escape all ampersants not followed by a # sharp sign
  // Note that symbolic references were replaced by numeric before this!
  $html = preg_replace("/&(?!#)/si","&#38;\\1",$html);

  // Complete all numeric character references unterminated with ';'
  $html = preg_replace("/&#(\d+)(?![\d;])/si","&#\\1;",$html);

  // Escape all ampersants followed by # sharp and NON-DIGIT symbol
  // They we're not covered by above conversions and are not a
  // symbol reference.
  // Also, don't forget that we've used &amp;! They should not be converted too...
  //
  $html = preg_replace("/&(?!#\d)/si","&#38;\\1",$html);

  return $html;
};

function escape_lt($html) {
  // Why this loop is needed here? 
  // The cause is that, for example, <<<a> sequence will not be replaced by
  // &lt;&lt<a>, as it should be. The regular expression matches TWO symbols
  // << (actually, first < symbold, and one following it, so, the second < 
  // will not be matched when script attempt to find and replace next occurrence using 'g' regexp
  // modifier. So, we will need to check for such situations agint and, possibly, restart the 
  // search and replace process.
  //
  while (preg_match("#<(\s*[^!/a-zA-Z])#",$html)) {
    $html = preg_replace("#<(\s*[^!/a-zA-Z])#si","&#60;\\1",$html);
  };
    
  while (preg_match("#(<[^>]*?)<#si",$html)) {
    $html = preg_replace("#(<[^>]*?)<#si","\\1&#60;",$html);
  };

  return $html;
};

function escape_gt($html) {
  $html = preg_replace("#([^\s\da-zA-Z'\"/=-])\s*>#si","\\1&#62;",$html);

  while (preg_match("#(>[^<]*?)>#si",$html)) {
    $html = preg_replace("#(>[^<]*?)>#si","\\1&#62;",$html);
  };

  return $html;
};

?>