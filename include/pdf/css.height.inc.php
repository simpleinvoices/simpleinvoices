<?php
// $Header: /cvsroot/html2ps/css.height.inc.php,v 1.24 2005/12/13 18:24:11 Konstantin Exp $

class CSSHeight extends CSSProperty {
  function CSSHeight() { $this->CSSProperty(false, false); }

  function inherit() { 
    // Determine parent 'display' value
    //
    $handler =& get_css_handler('display');
    $parent_display = $handler->get_parent();

    // Inherit height from table-row to table-cell
    if ($parent_display === "table-row") {
      $this->push($this->get());
      return;
    }

    $this->push($this->default_value());
  }

  function default_value() { return false; }

  function parse($value) { 
    if ($value === 'auto') {
      return $this->default_value();
    };

    if ($value{strlen($value)-1} == "%") {
      return array((int)$value, true); 
    } else {
      return array($value,false);
    };
  }
}

class CSSMinHeight extends CSSProperty {
  function CSSMinHeight() { $this->CSSProperty(false, false); }

  function inherit() { 
    // Determine parent 'display' value
    $handler =& get_css_handler('display');
    $parent_display = $handler->get_parent();

    // Inherit vertical-align from table-rows 
    if ($parent_display === "table-row") {
      $this->push($this->get());
      return;
    }

    $this->push(is_inline_element($parent_display) ? $this->get() : $this->default_value());
  }

  function default_value() { return false; }

  function parse($value) { 
    // Check if user specified empty value
    if ($value === "") { return CSSHeight::default_value(); };

    if ($value{strlen($value)-1} == "%") {
      return array((int)$value, true); 
    } else {
      return array($value,false);
    };
  }
}

class CSSMaxHeight extends CSSProperty {
  function CSSMaxHeight() { $this->CSSProperty(false, false); }

  function inherit() { 
    // Determine parent 'display' value
    $handler =& get_css_handler('display');
    $parent_display = $handler->get_parent();

    // Inherit vertical-align from table-rows 
    if ($parent_display === "table-row") {
      $this->push($this->get());
      return;
    }

    $this->push(is_inline_element($parent_display) ? $this->get() : $this->default_value());
  }

  function default_value() { return false; }

  function parse($value) { 
    if ($value{strlen($value)-1} == "%") {
      return array((int)$value, true); 
    } else {
      return array($value,false);
    };
  }
}
 
register_css_property("height",     new CSSHeight);
register_css_property("min-height", new CSSMinHeight);
register_css_property("max-height", new CSSMaxHeight);

?>