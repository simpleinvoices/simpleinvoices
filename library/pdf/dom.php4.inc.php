<?php

class PHP4DOMTree {
  var $_element;
  
  function PHP4DOMTree($domelement) {
    $this->_element = $domelement;
    $this->content = $domelement->get_content();
  }

  function &document_element() { 
    $element = $this->_element->document_element();
    return $element;
  }

  function &first_child() {
    $child =& PHP4DOMTree::from_DOMDocument($this->_element->first_child());
    return $child;
  }

  function &from_DOMDocument($domdocument) { 
    if (!$domdocument) {
      $null = null;
      return $null;
    };

    $tree =& new PHP4DOMTree($domdocument); 
    return $tree;
  }

  function get_attribute($name) { 
    return $this->_element->get_attribute($name); 
  }

  function get_content() { 
    return $this->_element->get_content(); 
  }

  function has_attribute($name) { 
    return $this->_element->has_attribute($name);
  }

  function &last_child() {
    $child =& PHP4DOMTree::from_DOMDocument($this->_element->last_child());
    return $child;
  }

  function &next_sibling() {
    $sibling =& PHP4DOMTree::from_DOMDocument($this->_element->next_sibling());
    return $sibling;
  }
  
  function node_type() { 
    return $this->_element->node_type();
  }

  function &parent() {
    $parent =& PHP4DOMTree::from_DOMDocument($this->_element->parent());
    return $parent;
  }

  function &previous_sibling() {
    $sibling =& PHP4DOMTree::from_DOMDocument($this->_element->previous_sibling());
    return $sibling;
  }

  function tagname() { 
    return $this->_element->tagname();
  }
}
?>