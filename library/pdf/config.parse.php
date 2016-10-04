<?php
// $Header: /cvsroot/html2ps/config.parse.php,v 1.7 2007/05/07 13:28:39 Konstantin Exp $

require_once(HTML2PS_DIR.'font.resolver.class.php');
require_once(HTML2PS_DIR.'treebuilder.class.php');
require_once(HTML2PS_DIR.'media.layout.inc.php');

// Get list of media types being used by script; 
// It should be a list of two types: 
// 1. Current CSS media type chose by user (defaults to 'screen')
// 2. 'all' media type
//
function config_get_allowed_media() {
  return array($GLOBALS['g_config']['cssmedia'], 'all');
}

function parse_encoding_override_node_config_file($root, &$resolver) {
  $child = $root->first_child();
  do {
    if ($child->node_type() == XML_ELEMENT_NODE) {
      switch ($child->tagname()) {
      case "normal":
        $names = explode(',',$root->get_attribute('name'));
        foreach ($names as $name) {
          $resolver->add_normal_encoding_override($name,
                                                  $child->get_attribute('normal'), 
                                                  $child->get_attribute('italic'),
                                                  $child->get_attribute('oblique'));
        };
        break;
      case "bold":
        $names = explode(',',$root->get_attribute('name'));
        foreach ($names as $name) {
          $resolver->add_bold_encoding_override($name,
                                                $child->get_attribute('normal'), 
                                                $child->get_attribute('italic'),
                                                $child->get_attribute('oblique'));
        };
        break;
      };      
    };
  } while ($child = $child->next_sibling());
}

function parse_metrics_node_config_file($root, &$resolver) {
  $resolver->add_afm_mapping($root->get_attribute('typeface'),
                             $root->get_attribute('file'));
}

function parse_ttf_node_config_file($root, &$resolver) {
  switch (FONT_EMBEDDING_MODE) {
  case 'all':
    $embed_flag = true;
    break;
  case 'none':
    $embed_flag = false;
    break;
  case 'config':
    $embed_flag = (bool)$root->get_attribute('embed');
    break;
  }

  $resolver->add_ttf_mapping($root->get_attribute('typeface'),
                             $root->get_attribute('file'),
                             $embed_flag);
}

function parse_family_encoding_override_node_config_file($family, $root, &$resolver) {
  $child = $root->first_child();
  do {
    if ($child->node_type() == XML_ELEMENT_NODE) {
      switch ($child->tagname()) {
      case "normal":
        $names = explode(",",$root->get_attribute('name'));
        foreach ($names as $name) {
          $resolver->add_family_normal_encoding_override($family, 
                                                         $name,
                                                         $child->get_attribute('normal'), 
                                                         $child->get_attribute('italic'),
                                                         $child->get_attribute('oblique'));
        };
        break;
      case "bold":
        $names = explode(",",$root->get_attribute('name'));
        foreach ($names as $name) {
          $resolver->add_family_bold_encoding_override($family, 
                                                       $name,
                                                       $child->get_attribute('normal'), 
                                                       $child->get_attribute('italic'),
                                                       $child->get_attribute('oblique'));
        };
        break;
      };      
    };
  } while ($child = $child->next_sibling());
}

function parse_fonts_family_node_config_file($root, &$resolver) {
  // Note: font family names are always converted to lower case to be non-case-sensitive
  $child = $root->first_child();
  do {
    if ($child->node_type() == XML_ELEMENT_NODE) {
      $font_family_name = strtolower($root->get_attribute('name'));
      switch ($child->tagname()) {
      case "normal":
        $resolver->add_normal_family($font_family_name,
                                     $child->get_attribute('normal'), 
                                     $child->get_attribute('italic'),
                                     $child->get_attribute('oblique'));
        break;
      case "bold":
        $resolver->add_bold_family($font_family_name,
                                   $child->get_attribute('normal'), 
                                   $child->get_attribute('italic'),
                                   $child->get_attribute('oblique'));        
        break;
      case "encoding-override":
        parse_family_encoding_override_node_config_file($font_family_name, $child, $resolver);
        break;
      };      
    };
  } while ($child = $child->next_sibling());
}

function parse_fonts_node_config_file($root, &$resolver) {
  $child = $root->first_child();
  do {
    if ($child->node_type() == XML_ELEMENT_NODE) {
      switch ($child->tagname()) {
      case "alias":
        $resolver->add_alias(strtolower($child->get_attribute('alias')), $child->get_attribute('family'));
        break;
      case "family":
        parse_fonts_family_node_config_file($child, $resolver);
        break;
      case "encoding-override":
        parse_encoding_override_node_config_file($child, $resolver);
        break;
      case "ttf":
        parse_ttf_node_config_file($child, $resolver);
        break;
      case "metrics":
        parse_metrics_node_config_file($child, $resolver);
        break;
      };      
    };
  } while ($child = $child->next_sibling());
}

function parse_config_file($filename) {
  // Save old magic_quotes_runtime value and disable it
  $mq_runtime = get_magic_quotes_runtime();
  set_magic_quotes_runtime(0);

  $doc = TreeBuilder::build(file_get_contents($filename));
  $root=$doc->document_element();

  $child = $root->first_child();
  do {
    if ($child->node_type() == XML_ELEMENT_NODE) {
      switch ($child->tagname()) {
      case "fonts":
        global $g_font_resolver;
        parse_fonts_node_config_file($child, $g_font_resolver);
        break;
      case "fonts-pdf":
        global $g_font_resolver_pdf;
        parse_fonts_node_config_file($child, $g_font_resolver_pdf);
        break;
      case "media":
        add_predefined_media($child->get_attribute('name'), 
                             (float)$child->get_attribute('height'),
                             (float)$child->get_attribute('width'));
        break;
      };      
    };
  } while ($child = $child->next_sibling());

  // Restore old magic_quotes_runtime values
  set_magic_quotes_runtime($mq_runtime);
}
?>