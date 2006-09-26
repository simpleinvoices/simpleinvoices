<?php
// $Header: /cvsroot/html2ps/box.php,v 1.35 2006/05/27 15:33:26 Konstantin Exp $

// This variable is used to track the reccurrent framesets
// they can be produced by inaccurate or malicious HTML-coder 
// or by some cookie- or referrer- based identification system
//
$g_frame_level = 0;

// Called when frame node  is to be processed 
function inc_frame_level() {
  global $g_frame_level;
  $g_frame_level ++;

  if ($g_frame_level > MAX_FRAME_NESTING_LEVEL) {
    die("Frame nesting too deep\n");
  };
}

// Called when frame (and all nested frames, of course) processing have been completed
//
function dec_frame_level() {
  global $g_frame_level;
  $g_frame_level --;
}

// Calculate 'display' CSS property according to CSS 2.1 paragraph 9.7 
// "Relationships between 'display', 'position', and 'float'" 
// (The last table in that paragraph)
//
// @return flag indication of current box need a block box wrapper
//
function _fix_display_position_float() {
  // Specified value -> Computed value
  // inline-table -> table
  // inline, run-in, table-row-group, table-column, table-column-group, table-header-group, 
  // table-footer-group, table-row, table-cell, table-caption, inline-block -> block
  // others-> same as specified
  
  $handler =& get_css_handler('display');
  $display = $handler->get();
  $handler->pop();

  switch ($display) {
  case "inline-table":
    $handler->push('table');
    return false;
  case "inline":
  case "run-in":
  case "table-row-group":
  case "table-column":
  case "table-column-group":
  case "table-header-group":
  case "table-footer-group":
  case "table-row":
  case "table-cell":
  case "table-caption":
  case "inline-block":
    // Note that as we're using some non-standard display values, we need to add them to translation table
    $handler->push('block');
    return false;

    // There are display types that cannot be directly converted to block; in this case we need to create a "wrapper" floating 
    // or positioned block box and put our real box into it.
  case "-button":
  case "-button-submit":
  case "-button-reset":
  case "-button-image":
  case "-checkbox":
  case "-iframe":
  case "-radio":
  case "-select":
  case "-text":
  case "-textarea":
  case "-password":
  case "-image":
    $handler->push($display);
    return true;

    // Display values that are not affected by "float" property
  case "-frame":
  case "-frameset":
  case "-legend":
    // 'block' is assumed here
  default:
    $handler->push($display);
    return false;
  }
}

function &create_pdf_box(&$root, &$pipeline) {
  switch ($root->node_type()) {
  case XML_DOCUMENT_NODE:
    // TODO: some magic from traverse_dom_tree
    $box =& BlockBox::create($root, $pipeline);
    break;
  case XML_ELEMENT_NODE:
    // Determine CSS proerty value for current child
    push_css_defaults();

    global $g_css_defaults_obj;
    $g_css_defaults_obj->apply($root, $pipeline);

    // Store the default 'display' value; we'll need it later when checking for impossible tag/display combination
    $handler =& get_css_handler('display');
    $default_display = $handler->get();
    
    // Initially generated boxes do not require block wrappers
    // Block wrappers are required in following cases:
    // - float property is specified for non-block box which cannot be directly converted to block box
    //   (a button, for example)
    // - display set to block for such box 
    $need_block_wrapper = false;

    // TODO: some inheritance magic

    // Order is important. Items with most priority should be applied last
    // Tag attributes
    execute_attrs_before($root, $pipeline);

    // CSS stylesheet
    global $g_css_obj;
    $g_css_obj->apply($root, $pipeline);

    // values from 'style' attribute
    if ($root->has_attribute("style")) { parse_style_attr(null, $root, $pipeline); };
    
    _fix_tag_display($default_display, $pipeline);

    // TODO: do_tag_specials
    // TODO: execute_attrs_after_styles

    // CSS 2.1:
    // 9.7 Relationships between 'display', 'position', and 'float'
    // The three properties that affect box generation and layout — 
    // 'display', 'position', and 'float' — interact as follows:
    // 1. If 'display' has the value 'none', then 'position' and 'float' do not apply. 
    //    In this case, the element generates no box.
    $position_handler =& get_css_handler('position');
    $float_handler    =& get_css_handler('float');

    // 2. Otherwise, if 'position' has the value 'absolute' or 'fixed', the box is absolutely positioned, 
    //    the computed value of 'float' is 'none', and display is set according to the table below. 
    //    The position of the box will be determined by the 'top', 'right', 'bottom' and 'left' properties and 
    //    the box's containing block.
    $position = $position_handler->get();
    if ($position === POSITION_ABSOLUTE || $position === POSITION_FIXED) {
      $float_handler->replace(FLOAT_NONE);
      $need_block_wrapper |= _fix_display_position_float();
    };

    // 3. Otherwise, if 'float' has a value other than 'none', the box is floated and 'display' is set
    //    according to the table below.
    $float = $float_handler->get();
    if ($float != FLOAT_NONE) {
      $need_block_wrapper |= _fix_display_position_float();
    };

    // Process some special nodes
    // BR
    if ($root->tagname() == "br") { 
      $handler =& get_css_handler('display');
      $handler->css('-break', $pipeline);
    };

    if ($root->tagname() == "img") {
      $handler =& get_css_handler('display');
      $need_block_wrapper |= ($handler->get() == "block");
      $handler->css('-image', $pipeline);
    };

    // 4. Otherwise, if the element is the root element, 'display' is set according to the table below.
    // 5. Otherwise, the remaining 'display' property values apply as specified. (see _fix_display_position_float)

    $display_handler =& get_css_handler('display');
    switch(trim($display_handler->get())) {
    case "block":
      $box =& BlockBox::create($root, $pipeline);
      break;
    case "-break":
      $box =& BRBox::create($pipeline); 
      break;
    case "-body":
      $box =& BodyBox::create($root, $pipeline);
      break;
    case "-button":
      $box =& ButtonBox::create($root, $pipeline);
      break;      
    case "-button-reset":
      $box =& ButtonResetBox::create($root, $pipeline);
      break;      
    case "-button-submit":
      $box =& ButtonSubmitBox::create($root, $pipeline);
      break;      
    case "-button-image":
      $box =& ButtonImageBox::create($root, $pipeline);
      break;      
    case "-checkbox":
      $box =& CheckBox::create($root, $pipeline);
      break;
    case "-form":
      $box =& FormBox::create($root, $pipeline);
      break;
    case "-frame":
      inc_frame_level();
      $box =& FrameBox::create($root, $pipeline);
      dec_frame_level();
      break;
    case "-frameset":
      inc_frame_level();
      $box =& FramesetBox::create($root, $pipeline);
      dec_frame_level();
      break;      
    case "-iframe":
      inc_frame_level();
      $box =& IFrameBox::create($root, $pipeline);
      dec_frame_level();
      break;
    case "-textarea":
      $box =& TextAreaInputBox::create($root, $pipeline);
      break;
    case "-image":
      $box =& IMGBox::create($root, $pipeline);      
      break;
    case "inline":
      $box =& InlineBox::create($root, $pipeline);
      break;
    case "inline-block":
      $box =& InlineBlockBox::create($root, $pipeline);
      break;
    case "-legend":
      $box =& LegendBox::create($root, $pipeline);
      break;
    case "list-item":
      $box =& ListItemBox::create($root, $pipeline);
      break;
    case "none":
      $box =& NullBox::create($root, $pipeline);
      break;
    case "-radio":
      $box =& RadioBox::create($root, $pipeline);
      break;
    case "-select":
      $box =& SelectBox::create($root, $pipeline);
      break;
    case "table":
      $box =& TableBox::create($root, $pipeline);
      break;
    case "table-cell":
      $box =& TableCellBox::create($root, $pipeline);
      break;
    case "table-row":
      $box =& TableRowBox::create($root, $pipeline);
      break;
    case "-table-section":
      $box =& TableSectionBox::create($root, $pipeline);
      break;
    case "-text":
      $box =& TextInputBox::create($root, $pipeline);
      break;
    case "-password":
      $box =& PasswordInputBox::create($root, $pipeline);
      break;
    default:
      die("Unsupported 'display' value: ".$display_handler->get());
    }

    // Now check if pseudoelement should be created; in this case we'll use the "inline wrapper" box
    // containing both generated box and pseudoelements
    //
    if ($box->content_pseudoelement !== "") {
      $content_handler =& get_css_handler('content');
      
      // Check if :before preudoelement exists
      $before = create_pdf_pseudoelement($root, SELECTOR_PSEUDOELEMENT_BEFORE, $pipeline);
      if ($before) {
        $box->insert_child(0, $before);
      };

      // Check if :after pseudoelement exists
      $after = create_pdf_pseudoelement($root, SELECTOR_PSEUDOELEMENT_AFTER, $pipeline);
      if ($after) {
        $box->add_child($after);
      };
    };

    // Check if this box needs a block wrapper (for example, floating button)
    // Note that to keep float/position information, we clear the CSS stack only
    // AFTER the wrapper box have been created; BUT we should clear the following CSS properties
    // to avoid the fake wrapper box actually affect the layout:
    // - margin
    // - border 
    // - padding 
    // - background
    //
    if ($need_block_wrapper) {
      // Note that if element width have been set as a percentage constraint and we're adding a block wrapper,
      // then we need to:
      // 1. set the same percentage width constraint to the wrapper element (will be done implicilty if we will not
      // modify the 'width' CSS handler stack
      // 2. set the wrapped element's width constraint to 100%, otherwise it will be narrower than expected
      if (is_a($box->_width_constraint, "WCFraction")) {
        $box->_width_constraint = new WCFraction(1);
      } 

      $handler =& get_css_handler('margin');
      $box->margin = $handler->default_value();

      $box->border = new BorderPDF(default_border());

      $handler =& get_css_handler('padding');
      $box->padding = $handler->default_value();

      $handler =& get_css_handler('background');
      $box->background = $handler->default_value();

//       $handler =& get_css_handler('margin');
//       $box->margin = $handler->parse("0");

//       // Clear CSS properties
//       pop_border();
//       push_border(default_border());

//       $handler =& get_css_handler('padding');
//       $handler->css('0');

//       $handler =& get_css_handler('background');
//       $handler->css('transparent');

      // Create "clean" block box
      $wrapper =& new BlockBox();
      $wrapper->add_child($box);

      // Remove CSS propery values from stack
      execute_attrs_after($root, $pipeline);
      pop_css_defaults();

      // Clear CSS properties handled by wrapper
      $box->float = FLOAT_NONE;
      $box->position = POSITION_STATIC;

      return $wrapper;
    } else {
      // Remove CSS propery values from stack
      execute_attrs_after($root, $pipeline);
      pop_css_defaults();
      
      return $box;
    };

    break;
  case XML_TEXT_NODE:
    // Determine CSS property value for current child
    push_css_text_defaults();
    // No text boxes generated by empty text nodes 
    //    if (trim($root->content) !== "") {
    if ($root->content !== "") {
      $box =& InlineBox::create($root, $pipeline);
    } else {
      $box = null;
    }
    // Remove CSS property values from stack
    pop_css_defaults();

    return $box;
    break;
  default:
    die("Unsupported node type:".$root->node_type());
  }  
}

function &create_pdf_pseudoelement($root, $pe_type, &$pipeline) {     
  // Store initial values to CSS stack
  //
  push_css_defaults();

  // Apply default stylesheet rules (using base element)
  global $g_css_defaults_obj;
  $g_css_defaults_obj->apply($root, $pipeline);

  // Initially generated boxes do not require block wrappers
  // Block wrappers are required in following cases:
  // - float property is specified for non-block box which cannot be directly converted to block box
  //   (a button, for example)
  // - display set to block for such box 
  $need_block_wrapper = false;

  // Order is important. Items with most priority should be applied last
  // Tag attributes
  execute_attrs_before($root, $pipeline);

  // CSS stylesheet
  global $g_css_obj;
  $g_css_obj->apply($root, $pipeline);
  
  // values from 'style' attribute
  if ($root->has_attribute("style")) { parse_style_attr(null, $root, $pipeline); };
  
  // Pseudoelement-specific rules; be default, it should flow inline
  //
  $handler =& get_css_handler('display');
  $handler->css('inline',$pipeline);
  $handler =& get_css_handler('content');
  $handler->css("",$pipeline);
  $handler =& get_css_handler('float');
  $handler->css("none",$pipeline);
  $handler =& get_css_handler('position');
  $handler->css("static",$pipeline);
  $handler =& get_css_handler('margin');
  $handler->css("0",$pipeline);
  $handler =& get_css_handler('width');
  $handler->css("auto",$pipeline);
  $handler =& get_css_handler('height');
  $handler->css("auto",$pipeline);

  $g_css_obj->apply_pseudoelement($pe_type, $root, $pipeline);

  // Now, if no content found, just return
  //
  $handler =& get_css_handler('content');
  $content = $handler->get();
  if ($content === "") { 
    pop_css_defaults();
    $dummy = null;
    return $dummy; 
  };
  
  // CSS 2.1:
  // 9.7 Relationships between 'display', 'position', and 'float'
  // The three properties that affect box generation and layout — 
  // 'display', 'position', and 'float' — interact as follows:
  // 1. If 'display' has the value 'none', then 'position' and 'float' do not apply. 
  //    In this case, the element generates no box.
  $position_handler =& get_css_handler('position');
  $float_handler    =& get_css_handler('float');
    
  // 2. Otherwise, if 'position' has the value 'absolute' or 'fixed', the box is absolutely positioned, 
  //    the computed value of 'float' is 'none', and display is set according to the table below. 
  //    The position of the box will be determined by the 'top', 'right', 'bottom' and 'left' properties and 
  //    the box's containing block.
  $position = $position_handler->get();
  if ($position === POSITION_ABSOLUTE || $position === POSITION_FIXED) {
    $float_handler->replace(FLOAT_NONE);
    $need_block_wrapper |= _fix_display_position_float();
  };

  // 3. Otherwise, if 'float' has a value other than 'none', the box is floated and 'display' is set
  //    according to the table below.
  $float = $float_handler->get();
  if ($float != FLOAT_NONE) {
    $need_block_wrapper |= _fix_display_position_float();
  };
  
  // 4. Otherwise, if the element is the root element, 'display' is set according to the table below.
  // 5. Otherwise, the remaining 'display' property values apply as specified. (see _fix_display_position_float)
  
  // Note that pseudoelements may get only standard display values
  $display_handler =& get_css_handler('display');
  switch(trim($display_handler->get())) {
  case "block":
    $box =& BlockBox::create_from_text($content);
    break;
  case "inline":
    $ws_handler =& get_css_handler('white-space');
    $box =& InlineBox::create_from_text($content, $ws_handler->get());
    break;
  default:
    die("Unsupported 'display' value: ".$display_handler->get());
  }

  // Check if this box needs a block wrapper (for example, floating button)
  // Note that to keep float/position information, we clear the CSS stack only
  // AFTER the wrapper box have been created; BUT we should clear the following CSS properties
  // to avoid the fake wrapper box actually affect the layout:
  // - margin
  // - border 
  // - padding 
  // - background
  //
  if ($need_block_wrapper) {
    $handler =& get_css_handler('margin');
    $handler->css("0",$pipeline);
    
    pop_border();
    push_border(default_border());
    
    pop_padding();
    push_padding(default_padding());
    
    $handler =& get_css_handler('background');
    $handler->css('transparent',$pipeline);
    
    // Create "clean" block box
    $wrapper =& new BlockBox();
    $wrapper->add_child($box);
    
    // Remove CSS propery values from stack
    execute_attrs_after($root, $pipeline);
    pop_css_defaults();
    
    return $wrapper;
  } else {
    // Remove CSS propery values from stack
    execute_attrs_after($root, $pipeline);
    pop_css_defaults();
    
    return $box;
  };
}

function is_inline(&$box) {
  return 
    $box->display === '-button' ||
    $box->display === '-button-reset' ||
    $box->display === '-button-submit' ||
    $box->display === '-button-image' ||
    $box->display === '-checkbox' ||
    $box->display === '-image' ||
    $box->display === 'inline' || 
    $box->display === 'inline-block' ||
    $box->display === 'none' ||
    $box->display === '-radio' ||
    $box->display === '-select' ||
    $box->display === '-text' ||
    $box->display === '-password';
}

function is_whitespace(&$box) {
  return 
    is_a($box, "WhitespaceBox") ||
    is_a($box, "NullBox");
}

function is_container(&$box) {
  return is_a($box, "GenericContainerBox") && 
    !is_a($box, "GenericInlineBox") || 
    is_a($box, "InlineBox");
}

function is_span(&$box) {
  return is_a($box, "InlineBox");
}

function is_table_cell(&$box) {
  return is_a($box, "TableCellBox");
}
?>