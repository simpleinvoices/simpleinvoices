<?php
// $Header: /cvsroot/html2ps/tree.navigation.inc.php,v 1.10 2006/03/19 09:25:37 Konstantin Exp $

function traverse_dom_tree_pdf(&$root) {
  switch ($root->node_type()) {
  case XML_DOCUMENT_NODE:
    $child = $root->first_child();
    while($child) {
      $body = traverse_dom_tree_pdf($child);
      if ($body) { return $body; }
      $child = $child->next_sibling();
    };
    break;
  case XML_ELEMENT_NODE:    
    if (strtolower($root->tagname()) == "body") { return $root; }

    $child = $root->first_child(); 
    while ($child) {
      $body = traverse_dom_tree_pdf($child);
      if ($body) { return $body; }
      $child = $child->next_sibling();
    };
    
    return null;
  default:
    return null;
  }
};

function dump_tree(&$box, $level) {
  print(str_repeat(" ", $level));
  print(get_class($box).":".$box->uid."\n");

  if (isset($box->content)) {
    for ($i=0; $i<count($box->content); $i++) {
      dump_tree($box->content[$i], $level+1);
    };
  };
};

function scan_styles($root, &$pipeline) {
  switch ($root->node_type()) {
  case XML_ELEMENT_NODE:
    if ($root->tagname() === 'style') {
      // Parse <style ...> ... </style> nodes
      //
      parse_style_node($root, $pipeline);

    } elseif ($root->tagname() === 'link') {
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
        $src = $root->get_attribute("href");
        if ($src) {
          css_import($src, $pipeline);
        };
      };
    };

    // Note that we continue processing here!
  case XML_DOCUMENT_NODE:

    // Scan all child nodes
    $child = $root->first_child();
    while ($child) {
      scan_styles($child, $pipeline);
      $child = $child->next_sibling();
    };
    break;
  };
};

?>