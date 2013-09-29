<?php
// $Header: /cvsroot/html2ps/box.php,v 1.46 2007/05/06 18:49:29 Konstantin Exp $

// This variable is used to track the reccurrent framesets
// they can be produced by inaccurate or malicious HTML-coder 
// or by some cookie- or referrer- based identification system
//
$GLOBALS['g_frame_level'] = 0;

// Called when frame node  is to be processed 
function inc_frame_level() {
  global $g_frame_level;
  $g_frame_level ++;

  if ($g_frame_level > MAX_FRAME_NESTING_LEVEL) {
    trigger_error('Frame nesting too deep',
                  E_USER_ERROR);
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
function _fix_display_position_float(&$css_state) {
  // Specified value -> Computed value
  // inline-table -> table
  // inline, run-in, table-row-group, table-column, table-column-group, table-header-group, 
  // table-footer-group, table-row, table-cell, table-caption, inline-block -> block
  // others-> same as specified
  
  $display = $css_state->getProperty(CSS_DISPLAY);

  switch ($display) {
  case "inline-table":
    $css_state->setProperty(CSS_DISPLAY, 'table');
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
    $css_state->setProperty(CSS_DISPLAY, 'block');
    return false;

    // There are display types that cannot be directly converted to block; in this case we need to create a "wrapper" floating 
    // or positioned block box and put our real box into it.
  case "-button":
  case "-button-submit":
  case "-button-reset":
  case "-button-image":
  case "-checkbox":
  case "-iframe":
  case "-image":
  case "-legend":
  case "-password":
  case "-radio":
  case "-select":
  case "-text":
  case "-textarea":
    // No change
    return true;

    // Display values that are not affected by "float" property
  case "-frame":
  case "-frameset":
    // 'block' is assumed here
  default:
    // No change
    return false;
  }
}

function &create_pdf_box(&$root, &$pipeline) {
  switch ($root->node_type()) {
  case XML_DOCUMENT_NODE:
    // TODO: some magic from traverse_dom_tree
    $box =& create_document_box($root, $pipeline);
    return $box;
  case XML_ELEMENT_NODE:   
    $box =& create_node_box($root, $pipeline);
    return $box;
  case XML_TEXT_NODE:
    $box =& create_text_box($root, $pipeline);
    return $box;
  default:
    die("Unsupported node type:".$root->node_type());
  }  
}

function &create_document_box(&$root, &$pipeline) {
  return BlockBox::create($root, $pipeline);
}

function &create_node_box(&$root, &$pipeline) {
  // Determine CSS proerty value for current child
  $css_state =& $pipeline->getCurrentCSSState();
  $css_state->pushDefaultState();

  $default_css = $pipeline->getDefaultCSS();
  $default_css->apply($root, $css_state, $pipeline);

  // Store the default 'display' value; we'll need it later when checking for impossible tag/display combination
  $handler =& CSS::get_handler(CSS_DISPLAY);
  $default_display = $handler->get($css_state->getState());
    
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
  $css =& $pipeline->getCurrentCSS();
  $css->apply($root, $css_state, $pipeline);

  // values from 'style' attribute
  if ($root->has_attribute("style")) { 
    parse_style_attr($root, $css_state, $pipeline); 
  };
    
  _fix_tag_display($default_display, $css_state, $pipeline);

  execute_attrs_after_styles($root, $pipeline);

  // CSS 2.1:
  // 9.7 Relationships between 'display', 'position', and 'float'
  // The three properties that affect box generation and layout  
  // 'display', 'position', and 'float'  interact as follows:
  // 1. If 'display' has the value 'none', then 'position' and 'float' do not apply. 
  //    In this case, the element generates no box.
  $position_handler =& CSS::get_handler(CSS_POSITION);
  $float_handler    =& CSS::get_handler(CSS_FLOAT);

  // 2. Otherwise, if 'position' has the value 'absolute' or 'fixed', the box is absolutely positioned, 
  //    the computed value of 'float' is 'none', and display is set according to the table below. 
  //    The position of the box will be determined by the 'top', 'right', 'bottom' and 'left' properties and 
  //    the box's containing block.
  $position = $css_state->getProperty(CSS_POSITION);
  if ($position === CSS_PROPERTY_INHERIT) {
    $position = $css_state->getInheritedProperty(CSS_POSITION);
  };

  if ($position === POSITION_ABSOLUTE || 
      $position === POSITION_FIXED) {
    $float_handler->replace(FLOAT_NONE, $css_state);
    $need_block_wrapper |= _fix_display_position_float($css_state);
  };

  // 3. Otherwise, if 'float' has a value other than 'none', the box is floated and 'display' is set
  //    according to the table below.
  $float = $css_state->getProperty(CSS_FLOAT);
  if ($float != FLOAT_NONE) {
    $need_block_wrapper |= _fix_display_position_float($css_state);
  };

  // Process some special nodes, which should not get their 'display' values overwritten (unless 
  // current display value is 'none'
  $current_display = $css_state->getProperty(CSS_DISPLAY);

  if ($current_display != 'none') {
    switch ($root->tagname()) {
    case 'body':
      $handler =& CSS::get_handler(CSS_DISPLAY);
      $handler->css('-body', $pipeline);
      break;
    case 'br':
      $handler =& CSS::get_handler(CSS_DISPLAY);
      $handler->css('-break', $pipeline);
      break;
    case 'img':
      $handler =& CSS::get_handler(CSS_DISPLAY);
      $need_block_wrapper |= ($handler->get($css_state->getState()) == "block");
      $handler->css('-image', $pipeline);
      break;
    };
  };

  // 4. Otherwise, if the element is the root element, 'display' is set according to the table below.
  // 5. Otherwise, the remaining 'display' property values apply as specified. (see _fix_display_position_float)

  switch($css_state->getProperty(CSS_DISPLAY)) {
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
    $box =& NullBox::create();
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
  case "table-row-group":
  case "table-header-group":
  case "table-footer-group":
    $box =& TableSectionBox::create($root, $pipeline);
    break;
  case "-text":
    $box =& TextInputBox::create($root, $pipeline);
    break;
  case "-password":
    $box =& PasswordInputBox::create($root, $pipeline);
    break;
  default:
    /**
     * If 'display' value is invalid or unsupported, fall back to 'block' mode
     */
    error_log("Unsupported 'display' value: ".$css_state->getProperty(CSS_DISPLAY));
    $box =& BlockBox::create($root, $pipeline);
    break;
  }

  // Now check if pseudoelement should be created; in this case we'll use the "inline wrapper" box
  // containing both generated box and pseudoelements
  //
  $pseudoelements = $box->getCSSProperty(CSS_HTML2PS_PSEUDOELEMENTS);

  if ($pseudoelements & CSS_HTML2PS_PSEUDOELEMENTS_BEFORE) {
    // Check if :before preudoelement exists
    $before =& create_pdf_pseudoelement($root, SELECTOR_PSEUDOELEMENT_BEFORE, $pipeline);
    if (!is_null($before)) {
      $box->insert_child(0, $before);
    };
  };

  if ($pseudoelements & CSS_HTML2PS_PSEUDOELEMENTS_AFTER) {
    // Check if :after pseudoelement exists
    $after =& create_pdf_pseudoelement($root, SELECTOR_PSEUDOELEMENT_AFTER, $pipeline);
    if (!is_null($after)) {
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
    /**
     * Clear POSITION/FLOAT properties on wrapped boxes
     */
    $box->setCSSProperty(CSS_POSITION, POSITION_STATIC);
    $box->setCSSProperty(CSS_POSITION, FLOAT_NONE);

    $wc = $box->getCSSProperty(CSS_WIDTH);

    // Note that if element width have been set as a percentage constraint and we're adding a block wrapper,
    // then we need to:
    // 1. set the same percentage width constraint to the wrapper element (will be done implicilty if we will not
    // modify the 'width' CSS handler stack
    // 2. set the wrapped element's width constraint to 100%, otherwise it will be narrower than expected
    if ($wc->isFraction()) {
      $box->setCSSProperty(CSS_WIDTH, new WCFraction(1));
    } 

    $handler =& CSS::get_handler(CSS_MARGIN);
    $box->setCSSProperty(CSS_MARGIN, $handler->default_value());

    /** 
     * Note:  default border does  not contain  any fontsize-dependent
     * values, so we may safely use zero as a base font size
     */
    $border_handler =& CSS::get_handler(CSS_BORDER);
    $value = $border_handler->default_value();
    $value->units2pt(0);
    $box->setCSSProperty(CSS_BORDER, $value);

    $handler =& CSS::get_handler(CSS_PADDING);
    $box->setCSSProperty(CSS_PADDING, $handler->default_value());

    $handler =& CSS::get_handler(CSS_BACKGROUND);
    $box->setCSSProperty(CSS_BACKGROUND, $handler->default_value());

    // Create "clean" block box
    $wrapper =& new BlockBox();
    $wrapper->readCSS($pipeline->getCurrentCSSState());    
    $wrapper->add_child($box);

    // Remove CSS propery values from stack
    execute_attrs_after($root, $pipeline);
    
    $css_state->popState();

    return $wrapper;
  } else {
    // Remove CSS propery values from stack
    execute_attrs_after($root, $pipeline);
    $css_state->popState();

    $box->set_tagname($root->tagname());
    return $box;
  };
}

function &create_text_box(&$root, &$pipeline) {
  // Determine CSS property value for current child
  $css_state =& $pipeline->getCurrentCSSState();
  $css_state->pushDefaultTextState();

  /**
   * No text boxes generated by empty text nodes.
   * Note that nodes containing spaces only are NOT empty, as they may
   * correspond, for example, to whitespace between tags.
   */
  if ($root->content !== "") {
    $box =& InlineBox::create($root, $pipeline);
  } else {
    $box = null;
  }
  
  // Remove CSS property values from stack
  $css_state->popState();
  
  return $box;
}

function &create_pdf_pseudoelement($root, $pe_type, &$pipeline) {     
  // Store initial values to CSS stack
  $css_state =& $pipeline->getCurrentCSSState();
  $css_state->pushDefaultState();

  // Initially generated boxes do not require block wrappers
  // Block wrappers are required in following cases:
  // - float property is specified for non-block box which cannot be directly converted to block box
  //   (a button, for example)
  // - display set to block for such box 
  $need_block_wrapper = false;

  $css =& $pipeline->getCurrentCSS();
  $css->apply_pseudoelement($pe_type, $root, $css_state, $pipeline);

  // Now, if no content found, just return
  //
  $content_obj = $css_state->getProperty(CSS_CONTENT);
  if ($content_obj === CSS_PROPERTY_INHERIT) {
    $content_obj = $css_state->getInheritedProperty(CSS_CONTENT);
  };
  $content = $content_obj->render($pipeline->get_counters());

  if ($content === '') { 
    $css_state->popState();

    $dummy = null;
    return $dummy; 
  };
  
  // CSS 2.1:
  // 9.7 Relationships between 'display', 'position', and 'float'
  // The three properties that affect box generation and layout  
  // 'display', 'position', and 'float'  interact as follows:
  // 1. If 'display' has the value 'none', then 'position' and 'float' do not apply. 
  //    In this case, the element generates no box.
    
  // 2. Otherwise, if 'position' has the value 'absolute' or 'fixed', the box is absolutely positioned, 
  //    the computed value of 'float' is 'none', and display is set according to the table below. 
  //    The position of the box will be determined by the 'top', 'right', 'bottom' and 'left' properties and 
  //    the box's containing block.
  $position_handler =& CSS::get_handler(CSS_POSITION);
  $float_handler    =& CSS::get_handler(CSS_FLOAT);

  $position = $position_handler->get($css_state->getState());
  if ($position === CSS_PROPERTY_INHERIT) {
    $position = $css_state->getInheritedProperty(CSS_POSITION);
  };

  if ($position === POSITION_ABSOLUTE || $position === POSITION_FIXED) {
    $float_handler->replace(FLOAT_NONE);
    $need_block_wrapper |= _fix_display_position_float($css_state);
  };

  // 3. Otherwise, if 'float' has a value other than 'none', the box is floated and 'display' is set
  //    according to the table below.
  $float = $float_handler->get($css_state->getState());
  if ($float != FLOAT_NONE) {
    $need_block_wrapper |= _fix_display_position_float($css_state);
  };
  
  // 4. Otherwise, if the element is the root element, 'display' is set according to the table below.
  // 5. Otherwise, the remaining 'display' property values apply as specified. (see _fix_display_position_float)
  
  // Note that pseudoelements may get only standard display values
  $display_handler =& CSS::get_handler(CSS_DISPLAY);
  $display = $display_handler->get($css_state->getState());

  switch ($display) {
  case 'block':
    $box =& BlockBox::create_from_text($content, $pipeline);
    break;
  case 'inline':
    $ws_handler =& CSS::get_handler(CSS_WHITE_SPACE);
    $box =& InlineBox::create_from_text($content, 
                                        $ws_handler->get($css_state->getState()),
                                        $pipeline);
    break;
  default:
    die('Unsupported "display" value: '.$display_handler->get($css_state->getState()));
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
    $handler =& CSS::get_handler(CSS_MARGIN);
    $handler->css("0",$pipeline);
    
    pop_border();
    push_border(default_border());
    
    pop_padding();
    push_padding(default_padding());
    
    $handler =& CSS::get_handler(CSS_BACKGROUND);
    $handler->css('transparent',$pipeline);
    
    // Create "clean" block box
    $wrapper =& new BlockBox();
    $wrapper->readCSS($pipeline->getCurrentCSSState());
    $wrapper->add_child($box);
        
    $css_state->popState();   
    return $wrapper;
  } else {
    $css_state->popState();
    return $box;
  };
}

function is_inline(&$box) {
  if (is_a($box, "TextBox")) { return true; };

  $display = $box->getCSSProperty(CSS_DISPLAY);

  return 
    $display === '-button' ||
    $display === '-button-reset' ||
    $display === '-button-submit' ||
    $display === '-button-image' ||
    $display === '-checkbox' ||
    $display === '-image' ||
    $display === 'inline' || 
    $display === 'inline-block' ||
    $display === 'none' ||
    $display === '-radio' ||
    $display === '-select' ||
    $display === '-text' ||
    $display === '-password';
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