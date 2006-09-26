<?php
// $Header: /cvsroot/html2ps/css.rules.inc.php,v 1.6 2006/03/19 09:25:36 Konstantin Exp $

class CSSRule {
  var $selector;
  var $body;
  var $baseurl;
  var $order;
  var $important;

  var $specificity;
  var $pseudoelement;

  function apply(&$root, &$pipeline) {
    return apply_css_rule_obj($this->body, $this->baseurl, $root, $pipeline);
  }

  function CSSRule($rule, &$pipeline) {
    $this->selector = $rule[0];
    $this->body     = $rule[1];
    $this->baseurl  = $rule[2];
    $this->order    = $rule[3];

    // Pre-parse property values
    foreach (array_keys($this->body) as $key) {
      $handler =& get_css_handler($key);
      if ($handler) { 
        $value = $this->parse_important($key, $this->body[$key]);

        $pipeline->push_base_url($this->baseurl);
        $this->body[$key] = $handler->parse($value, $pipeline); 
        $pipeline->pop_base_url();
      };
    };


    $this->specificity   = css_selector_specificity($this->selector);
    $this->pseudoelement = css_find_pseudoelement($this->selector);
  }

  function get_order() { return $this->order; }
  function get_pseudoelement() { return $this->pseudoelement; }
  function get_selector() { return $this->selector; }
  function get_specificity() { return $this->specificity; }

  function match($root) {
    return match_selector($this->selector, $root);
  }

  // Check if this property contains !important declaration,
  // store information about important properties and REMOVE
  // this declaration from the value
  //
  function parse_important($key, $value) {
    if (preg_match("/^(.*)!\s*important\s*$/",$value,$matches)) {
      $this->important[$key] = true;
      return $matches[1];
    } else {
      return $value;
    };
  }
}

function rule_get_selector(&$rule) { return $rule[0]; };

function cmp_rules($r1, $r2) {
  $a = css_selector_specificity($r1[0]);
  $b = css_selector_specificity($r2[0]);

  for ($i=0; $i<=2; $i++) {
    if ($a[$i] != $b[$i]) { return ($a[$i] < $b[$i]) ? -1 : 1; };
  };

  // If specificity of selectors is equal, use rules natural order in stylesheet

  return $r1[3] < $r2[3] ? -1 : 1;
}

function cmp_rule_objs($r1, $r2) {
  $a = $r1->get_specificity();
  $b = $r2->get_specificity();

  for ($i=0; $i<=2; $i++) {
    if ($a[$i] != $b[$i]) { return ($a[$i] < $b[$i]) ? -1 : 1; };
  };

  // If specificity of selectors is equal, use rules natural order in stylesheet

  return $r1->get_order() < $r2->get_order() ? -1 : 1;
}

?>