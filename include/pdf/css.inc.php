<?php
// $Header: /cvsroot/html2ps/css.inc.php,v 1.19 2006/05/27 15:33:27 Konstantin Exp $

class CSSObject {
  var $rules;
  var $tag_filtered;
  
  function add_rule(&$rule, &$pipeline) {
    $rule_obj = new CSSRule($rule, $pipeline);
    $this->rules[] = $rule_obj ;

    $tag = $this->detect_applicable_tag($rule_obj->get_selector());
    if ($tag === null) { $tag = "*"; }
    $this->tag_filtered[$tag][] = $rule_obj;
  }

  function apply(&$root, &$pipeline) {
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
      case SELECTOR_PSEUDOELEMENT_AFTER:
        // Just store something in the 'content' property to indicate that current
        // element have pseudoelements
        //
        $handler =& get_css_handler('content');
        $handler->css("+", $pipeline);
        break;
      default:
        $rule->apply($root, $pipeline);
        break;
      };
    };
  }

  function apply_pseudoelement($element_type, &$root, &$pipeline) {
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
      $rule->apply($root, $pipeline);
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

global $g_css_handlers, $g_css;
$g_css_handlers = array();
$g_css          = array();

function &get_css_handler($property) {
  global $g_css_handlers;
  if (isset($g_css_handlers[$property])) {
    return $g_css_handlers[$property];
  } else {
    $dumb = null;
    return $dumb;
  };
};

function pop_css_defaults() {
  pop_border();

  pop_font_family();
  pop_font_size();
  pop_font_style();
  pop_font_weight();

  pop_line_height();

  global $g_css_handlers;
  $keys = array_keys($g_css_handlers);
  foreach ($keys as $key) {
    $g_css_handlers[$key]->pop();
  }  
}

function push_css_defaults() {
  push_border(default_border());

  push_font_family(get_font_family());

  //  push_font_size(get_font_size());
  push_font_size("1em");

  push_font_style(get_font_style());
  push_font_weight(get_font_weight());

  push_line_height(get_line_height());

  global $g_css_handlers;
  $keys = array_keys($g_css_handlers);
  foreach ($keys as $key) {
    $g_css_handlers[$key]->inherit();
  }  
}

function push_css_text_defaults() {
  // No borders for pure text boxes; border can be controlled via SPANs
  push_border(default_border());
 
  push_font_family(get_font_family());

  //  push_font_size(get_font_size());
  push_font_size("1em");

  push_font_style(get_font_style());
  push_font_weight(get_font_weight());

  push_line_height(get_line_height());

  global $g_css_handlers;
  $keys = array_keys($g_css_handlers);
  foreach ($keys as $key) {
    $g_css_handlers[$key]->inherit_text();
  }  
}

function register_css_property($property, &$handler) {
  global $g_css_handlers;
  $g_css_handlers[$property] =& $handler;
};

// ------------------

class CSSProperty {
  var $_stack;
  var $_inheritable;
  var $_inheritable_text;

  function css($value, &$pipeline) { $this->replace($this->parse($value, $pipeline)); }

  function CSSProperty($inheritable, $inheritable_text) { 
    $this->_inheritable = $inheritable;
    $this->_inheritable_text = $inheritable_text;

    $this->_stack = array(array($this->default_value(),false)); 
  }

  function get() { return $this->_stack[0][0]; }

  function inherit()      { $this->push( ($this->_inheritable ? $this->get() : $this->default_value()) ,false); }
  function inherit_text() { $this->push( ($this->_inheritable_text ? $this->get() : $this->default_value()) ,false); }

  // Check if value on the top of the stack was just CSS-standard default
  // or have been calculated via some attribute or CSS rule
  function is_calculated() { return $this->_stack[0][1]; }

  function is_default($value) { 
    if (is_object($value)) {
      return $value->is_default();
    } else {
      return $this->default_value() === $value; 
    };
  }
  function is_subproperty() { return false; }

  function pop() { array_shift($this->_stack); }

  function push_default() { $this->push($this->default_value(), false); }
  function push_css($value, &$pipeline) { $this->push_default(); $this->css($value, $pipeline); }
  function push($value, $calculated=true) { array_unshift($this->_stack, array($value, $calculated)); }

  //  function replace($value) { $this->pop(); $this->push($value, true); }
  function replace($value) { $this->_stack[0] = array($value, true); }
}

class CSSSubProperty extends CSSProperty {
  var $_owner;
  var $_owner_field;

  function CSSSubProperty(&$owner, $field = "") {
    $this->_owner =& $owner;
    $this->_owner_field = $field;
  }

  function get() {
    $owner =& $this->owner();
    $field = $this->_owner_field;
    $owner_value = $owner->get();
    return $owner_value->$field;
  }

  function is_subproperty() { return true; }
  function &owner() { return $this->_owner; }
 
  function default_value() { }
  function inherit()       { }
  function inherit_text()  { }

  function replace($value) { 
    $owner =& $this->owner();
    $field = $this->_owner_field;

    $owner_value = $owner->get();

    if (is_object($owner_value)) {
      $owner_value = $owner_value->copy();
    };

    if (is_object($value)) {
      $owner_value->$field = $value->copy();
    } else {
      $owner_value->$field = $value;
    };

    $owner->replace($owner_value);
  }

  function push($value)    { }
  function pop()           { }

}
?>