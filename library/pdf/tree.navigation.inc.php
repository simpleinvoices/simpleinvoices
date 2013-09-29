<?php
// $Header: /cvsroot/html2ps/tree.navigation.inc.php,v 1.13 2007/05/06 18:49:29 Konstantin Exp $

class TreeWalkerDepthFirst {
  var $_callback;

  function TreeWalkerDepthFirst($callback) {
    $this->_callback = $callback;
  }

  function run(&$node) {
    call_user_func($this->_callback, array('node' => &$node));
    $this->walk_element($node);
  }

  function walk_element(&$node) {
    if (!isset($node->content)) {
      return;
    };

    for ($i = 0, $size = count($node->content); $i < $size; $i++) {
      $child =& $node->content[$i];
      $this->run($child);
    };
  }
}

function &traverse_dom_tree_pdf(&$root) {
  switch ($root->node_type()) {
  case XML_DOCUMENT_NODE:
    $child =& $root->first_child();
    while($child) {
      $body =& traverse_dom_tree_pdf($child);
      if ($body) { 
        return $body; 
      }
      $child =& $child->next_sibling();
    };

    $null = null;
    return $null;
  case XML_ELEMENT_NODE:    
    if (strtolower($root->tagname()) == "body") { 
      return $root; 
    }

    $child =& $root->first_child(); 
    while ($child) {
      $body =& traverse_dom_tree_pdf($child);
      if ($body) { 
        return $body; 
      }
      $child =& $child->next_sibling();
    };
    
    $null = null;
    return $null;
  default:
    $null = null;
    return $null;
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

?>