<?php
// $Header: /cvsroot/html2ps/box.table.cell.php,v 1.34 2006/05/27 15:33:26 Konstantin Exp $

class TableCellBox extends GenericContainerBox {
  var $colspan;
  var $rowspan;
  var $column;

  var $_suppress_first;
  var $_suppress_last;

  function show(&$driver) {
//     $driver->save();

//     $driver->moveto($this->get_left(),  $this->get_top());
//     $driver->lineto($this->get_right(), $this->get_top());
//     $driver->lineto($this->get_right(), $this->get_bottom());
//     $driver->lineto($this->get_left(),  $this->get_bottom());
//     $driver->closepath();
//     $driver->clip();

    $status = parent::show($driver);

//     $driver->restore();

    return $status;
  }

  function is_fake() {
    return false;
  }

  function &create(&$root, &$pipeline) {
    $box =& new TableCellBox($root, $pipeline);
    return $box;
  }

  function TableCellBox(&$root, $pipeline) {
    $this->_suppress_first = false;
    $this->_suppress_last  = false;
    
    $this->colspan = 1;
    $this->rowspan = 1;

    // This value will be overwritten in table 'normalize_parent' method
    //
    $this->column  = 0;
    $this->row     = 0;

    if ($root->tagname() === 'td' || $root->tagname() === 'th') {
      // Use cellspacing / cellpadding values from the containing table
      $handler =& get_css_handler('-cellspacing');
      $cellspacing = $handler->get();

      $cp_handler =& get_css_handler('-cellpadding');
      $cellpadding = $cp_handler->get();

      // FIXME: I'll need to resolve that issue with COLLAPSING border model. Now borders
      // are rendered separated

      // if not border set explicitly, inherit value set via border attribute of TABLE tag
      if (is_default_border(get_border())) {
        $border = get_table_border(); 
        pop_border();
        push_border($border);
      };

      $margin =& get_css_handler('margin');
      $margin->replace($margin->default_value());
      
      $handler =& get_css_handler('border-collapse');
      if ($handler->get() == BORDER_COLLAPSE) {
        $h_padding =& get_css_handler('padding');
        
        if ($h_padding->is_default($h_padding->get())) {
          $h_padding->css($cellpadding, $pipeline);
        };
      } else {
        $h_padding =& get_css_handler('padding');

        if ($h_padding->is_default($h_padding->get())) {
          $h_padding->css($cellpadding, $pipeline);
        };
        
        if ($margin->is_default($margin->get())) {
          $margin->css(units_mul($cellspacing, 0.5), $pipeline);
        }
      };

      // Save colspan and rowspan information
      $this->colspan = max(1,(int)$root->get_attribute('colspan'));
      $this->rowspan = max(1,(int)$root->get_attribute('rowspan'));
    } // $root->tagname() == 'td'

    // Call parent constructor
    $this->GenericContainerBox();

    // 'vertical-align' CSS value is not inherited from the table cells
    $handler =& get_css_handler('vertical-align');

    $handler->push_default();

    $this->create_content($root, $pipeline);

    global $g_config;
    if ($g_config['mode'] == "quirks") {
      // QUIRKS MODE:
      // H1-H6 and P elements should have their top/bottom margin suppressed if they occur as the first/last table cell child 
      // correspondingly; note that we cannot do it usung CSS rules, as there's no selectors for the last child. 
      //
      $child = $root->first_child();
      if ($child) {
        while ($child && $child->node_type() != XML_ELEMENT_NODE) {
          $child = $child->next_sibling();
        };

      
        if ($child) {
          if (array_search(strtolower($child->tagname()), array("h1","h2","h3","h4","h5","h6","p"))) {
            $this->_suppress_first = true;
          }
        };
      };

      $child = $root->last_child();
      if ($child) {
        while ($child && $child->node_type() != XML_ELEMENT_NODE) {
          $child = $child->previous_sibling();
        };
        
        if ($child) {
          if (array_search(strtolower($child->tagname()), array("h1","h2","h3","h4","h5","h6","p"))) {
            $this->_suppress_last = true;
          }
        };
      };
    };

    // pop the default vertical-align value
    $handler->pop();
  }

  // Inherited from GenericFormattedBox

  function get_cell_baseline() {
    $content = $this->get_first_data();
    if ($content === null) { return 0; }
    return $content->baseline;
  }

  // Flow-control
  function reflow(&$parent, &$context) {
    GenericFormattedBox::reflow($parent, $context);

    global $g_config;
    $size = count($this->content);
    if ($g_config['mode'] == "quirks" && $size > 0) {
      // QUIRKS MODE:
      // H1-H6 and P elements should have their top/bottom margin suppressed if they occur as the first/last table cell child 
      // correspondingly; note that we cannot do it usung CSS rules, as there's no selectors for the last child. 
      //
      
      $first =& $this->get_first();
      if (!is_null($first) && $this->_suppress_first && $first->is_block()) {
        $first->margin->top->value = 0;
        $first->margin->top->percentage = false;
      };

      $last =& $this->get_last();
      if (!is_null($last) && $this->_suppress_last && $last->is_block()) {
        $last->margin->bottom->value = 0;
        $last->margin->bottom->percentage = false;
      };
    };

    // Determine upper-left _content_ corner position of current box 
    $this->put_left($parent->_current_x + $this->get_extra_left());

    // NOTE: Table cell margin is used as a cell-spacing value
    $this->put_top($parent->_current_y - $this->border->top->get_width() - $this->padding->top->value);

    // CSS 2.1: 
    // Floats, absolutely positioned elements, inline-blocks, table-cells, and elements with 'overflow' other than
    // 'visible' establish new block formatting contexts.
    $context->push();
    $context->push_container_uid($this->uid);

    // Reflow cell content
    $this->reflow_content($context);

    // Extend the table cell height to fit all contained floats
    //
    // Determine the bottom edge corrdinate of the bottommost float
    //
    $float_bottom = $context->float_bottom();
      
    if ($float_bottom !== null) {
      $this->extend_height($float_bottom);
    };

    // Restore old context
    $context->pop_container_uid();
    $context->pop();
  }
}

class FakeTableCellBox extends TableCellBox {
  var $colspan;
  var $rowspan;

  function FakeTableCellBox() {
    // Required to reset any constraints initiated by CSS properties
    push_css_defaults();

    $this->colspan = 1;
    $this->rowspan = 1;
    $this->GenericContainerBox();

    $this->content[] = new NullBox;

    pop_css_defaults();
  }

  function show(&$viewport) {
    return true;
  }
  
  function is_fake() {
    return true;
  }
}

?>