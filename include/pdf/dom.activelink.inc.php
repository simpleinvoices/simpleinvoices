<?php

// Wrapper for ActiveLink pure PHP DOM extension
class ActiveLinkDOMTree {
  var $xml;
  var $index;
  var $parent_indices;
  var $parents;
  var $content;

  function from_XML($xml) { return new ActiveLinkDomTree($xml,0, array(), array()); }

  function ActiveLinkDOMTree($xml, $index, $indices, $parents) {
    $this->xml            = $xml;
    $this->index          = $index;
    $this->parent_indices = $indices;
    $this->parents        = $parents;

    if (is_a($this->xml,"XMLLeaf")) {
      $this->content = $xml->value;
    } else {
      $this->content = $xml->getXMLContent();
    };
  }

  function node_type() { return is_a($this->xml,"XMLLeaf") ? XML_TEXT_NODE : XML_ELEMENT_NODE; }
  function tagname()   { return is_a($this->xml,"XMLLeaf") ? "text" : $this->xml->getTagName(); }

  function get_attribute($name) { return $this->xml->getTagAttribute($name); }
  function has_attribute($name) { return $this->xml->getTagAttribute($name) !== false; }

  function get_content() { return $this->xml->getXMLContent(); }

  function document_element() { return $this; }

  function last_child() {
    $child = $this->first_child();

    if ($child) {
      $sibling = $child->next_sibling();
      while ($sibling) {
        $child = $sibling;
        $sibling = $child->next_sibling();
      };
    };

    return $child;
  }

  function parent() {
    if (!(is_a($this->xml,"XMLBranch") || is_a($this->xml,"XMLLeaf"))) { return false; }

    if (count($this->parents) > 0) {
      $parents = $this->parents;
      $parent = array_pop($parents);
      return $parent;
    } else {
      return false;
    };
  }

  function first_child() {
    $children = $this->xml->nodes;
    $indices = $this->parent_indices;
    array_push($indices, $this->index);
    $parents = $this->parents;
    array_push($parents, $this);

    if ($children) {
      $node = new ActiveLinkDOMTree($children[0], 0, $indices, $parents);       
      return $node;
    } else {
      return false;
    };
  }

  function previous_sibling() {
    $parent = $this->parents[count($this->parents)-1];
    $nodes  = $parent->xml->nodes;

    if ($this->index <= 0) { return false; };

    return new ActiveLinkDOMTree($nodes[$this->index-1],$this->index-1, $this->parent_indices, $this->parents);
  }

  function next_sibling() {
    $parent = $this->parents[count($this->parents)-1];
    $nodes  = $parent->xml->nodes;
     
    if ($this->index >= count($nodes)-1) { 
      return false; 
    };

    $node = new ActiveLinkDOMTree($nodes[$this->index+1], $this->index+1, $this->parent_indices, $this->parents);

    return $node;
  }
}

?>