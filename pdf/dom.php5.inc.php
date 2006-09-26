<?php
class DOMTree {
  var $domelement;
  var $content;
  
  function document_element() { return $this; }
  function DOMTree($domelement) {
    $this->domelement = $domelement;
    $this->content = $domelement->textContent;
  }

  function first_child() {
    if ($this->domelement->firstChild) {
      return new DOMTree($this->domelement->firstChild);
    } else {
      return false;
    };
  }
  function from_DOMDocument($domdocument) { return new DOMTree($domdocument->documentElement); }

  function get_attribute($name) { return $this->domelement->getAttribute($name); }
  function get_content() { return $this->domelement->textContent; }

  function has_attribute($name) { return $this->domelement->hasAttribute($name); }

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

  function next_sibling() {
    if ($this->domelement->nextSibling) {
      return new DOMTree($this->domelement->nextSibling);
    } else {
      return false;
    };
  }
  function node_type() { return $this->domelement->nodeType; }

  function parent() {
    if ($this->domelement->parentNode) {
      return new DOMTree($this->domelement->parentNode);
    } else {
      return false;
    };
  }
  function previous_sibling() {
    if ($this->domelement->previousSibling) {
      return new DOMTree($this->domelement->previousSibling);
    } else {
      return false;
    };
  }

  function tagname() { return $this->domelement->localName; }
}
?>