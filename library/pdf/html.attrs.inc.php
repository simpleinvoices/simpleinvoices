<?php
// $Header: /cvsroot/html2ps/html.attrs.inc.php,v 1.63 2007/03/15 18:37:32 Konstantin Exp $

global $g_tag_attrs;
$g_tag_attrs = array(
                     /**
                      * Attribute handlers applicable to all tags
                      */
                     '*'       => array(
                                        'id'   => 'attr_id',
                                        ),

                     /**
                      * Tag-specific attribute handlers
                      */
                     'a'       => array(
                                        'href' => 'attr_href',
                                        'name' => 'attr_name'
                                        ),
                     'body'    => array(
                                        'background'   => 'attr_background',
                                        'bgcolor'      => 'attr_bgcolor',
                                        'dir'          => 'attr_dir',
                                        'text'         => 'attr_body_text',
                                        'link'         => 'attr_body_link',
                                        'topmargin'    => 'attr_body_topmargin',
                                        'leftmargin'   => 'attr_body_leftmargin',
                                        'marginheight' => 'attr_body_marginheight',
                                        'marginwidth'  => 'attr_body_marginwidth'
                                        ),
                     'div'     => array(
                                        'align' => 'attr_align'
                                        ),
                     'font'    => array(
                                        'size'  => 'attr_font_size',
                                        'color' => 'attr_font_color',
                                        'face'  => 'attr_font_face'
                                        ),
                     'form'    => array(
                                          'action'  => 'attr_form_action'
                                          ),
                     'frame'   => array(
                                        'frameborder'  => 'attr_frameborder',
                                        'marginwidth'  => 'attr_iframe_marginwidth',
                                        'marginheight' => 'attr_iframe_marginheight'
                                        ),
                     'frameset'=> array(
                                        'frameborder' => 'attr_frameborder'
                                        ),
                     'h1'      => array(
                                        'align' => 'attr_align'
                                        ),
                     'h2'      => array(
                                        'align' => 'attr_align'
                                        ),
                     'h3'      => array(
                                        'align' => 'attr_align'
                                        ),
                     'h4'      => array(
                                        'align' => 'attr_align'
                                        ),
                     'h5'      => array(
                                        'align' => 'attr_align'
                                        ),
                     'h6'      => array(
                                        'align' => 'attr_align'
                                        ),
                     'hr'      => array(
                                        'align' => 'attr_self_align',
                                        'width' => 'attr_width',
                                        'color' => 'attr_hr_color'
                                        ),
                     'input'   => array(
                                        'name'  => 'attr_input_name',
                                        'size'  => 'attr_input_size'
                                        ),
                     'iframe'  => array(
                                        'frameborder'  => 'attr_frameborder',
                                        'marginwidth'  => 'attr_iframe_marginwidth',
                                        'marginheight' => 'attr_iframe_marginheight',
                                        'height'       => 'attr_height_required',
                                        'width'        => 'attr_width'
                                        ),
                     'img'     => array(
                                        'width'  => 'attr_width',
                                        'height' => 'attr_height_required',
                                        'border' => 'attr_border',
                                        'hspace' => 'attr_hspace',
                                        'vspace' => 'attr_vspace',
                                        'align'  => 'attr_img_align'
                                        ),
                     'marquee' => array(
                                        'width'  => 'attr_width', 
                                        'height' => 'attr_height_required'
                                        ),
                     'object'  => array(
                                        'width'  => 'attr_width', 
                                        'height' => 'attr_height'
                                        ),
                     'ol'      => array(
                                        'start' => 'attr_start',
                                        'type' => 'attr_ol_type'
                                        ),
                     'p'       => array(
                                        'align' => 'attr_align'
                                        ),
                     'table'   => array(
                                        'border'      => 'attr_table_border', 
                                        'bordercolor' => 'attr_table_bordercolor', 
                                        'align'       => 'attr_table_float_align',
                                        'bgcolor'     => 'attr_bgcolor',
                                        'width'       => 'attr_width',
                                        'background'  => 'attr_background', 
                                        'height'      => 'attr_height', 
                                        'cellspacing' => 'attr_cellspacing', 
                                        'cellpadding' => 'attr_cellpadding',
                                        'rules'       => 'attr_table_rules' // NOTE that 'rules' should appear _after_ 'border' handler!
                                        ),
                     'td'      => array(
                                        'align'      => 'attr_align', 
                                        'valign'     => 'attr_valign', 
                                        'height'     => 'attr_height', 
                                        'background' => 'attr_background', 
                                        'bgcolor'    => 'attr_bgcolor',
                                        'nowrap'     => 'attr_nowrap',
                                        'width'      => 'attr_width'
                                        ),
                     'textarea'=> array(
                                        'rows'       => 'attr_textarea_rows',
                                        'cols'       => 'attr_textarea_cols'
                                        ),
                     'th'      => array(
                                        'align'      => 'attr_align', 
                                        'valign'     => 'attr_valign', 
                                        'height'     => 'attr_height', 
                                        'background' => 'attr_background', 
                                        'bgcolor'    => 'attr_bgcolor',
                                        'nowrap'     => 'attr_nowrap',
                                        'width'      => 'attr_width'
                                        ),
                     'tr'      => array(
                                        'align'   => 'attr_align',
                                        'bgcolor' => 'attr_bgcolor', 
                                        'valign'  => 'attr_row_valign', 
                                        'height'  => 'attr_height'
                                        ),
                     'ul'      => array(
                                        'start' => 'attr_start',
                                        'type' => 'attr_ul_type'
                                        )
);


function execute_attrs_before($root, &$pipeline) { execute_attrs($root, '_before', $pipeline); }
function execute_attrs_after($root, &$pipeline) { execute_attrs($root, '_after', $pipeline); }
function execute_attrs_after_styles($root, &$pipeline) { execute_attrs($root, '_after_styles', $pipeline); }

function execute_attrs(&$root, $suffix, &$pipeline) {
  global $g_tag_attrs;

  foreach ($g_tag_attrs['*'] as $attr => $fun) {
    if ($root->has_attribute($attr)) {
      $fun = $fun.$suffix;
      $fun($root, $pipeline);
    };
  };

  if (array_key_exists($root->tagname(), $g_tag_attrs)) {
    foreach ($g_tag_attrs[$root->tagname()] as $attr => $fun) {
      if ($root->has_attribute($attr)) {
        $fun = $fun.$suffix;
        $fun($root, $pipeline);
      };
    };
  };
};

// ========= Handlers

// A NAME
function attr_name_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_HTML2PS_LINK_DESTINATION);
  $handler->css($root->get_attribute('name'), $pipeline);
}
function attr_name_after_styles(&$root, &$pipeline) {};
function attr_name_after(&$root, &$pipeline) {};

// A ID
function attr_id_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_HTML2PS_LINK_DESTINATION);
  $handler->css($root->get_attribute('id'), $pipeline);
}
function attr_id_after_styles(&$root, &$pipeline) {};
function attr_id_after(&$root, &$pipeline) {};


// A HREF
function attr_href_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_HTML2PS_LINK_TARGET);
  $handler->css($root->get_attribute('href'), $pipeline);
}
function attr_href_after_styles(&$root, &$pipeline) {};
function attr_href_after(&$root, &$pipeline) {};

// IFRAME 
function attr_frameborder_before(&$root, &$pipeline) {
  $css_state =& $pipeline->getCurrentCSSState();
  $handler =& CSS::get_handler(CSS_BORDER);

  switch ($root->get_attribute('frameborder')) {
  case '1':
    $handler->css('inset black 1px', $pipeline);
    break;
  case '0':
    $handler->css('none', $pipeline);
    break;
  };
}
function attr_frameborder_after_styles(&$root, &$pipeline) {};
function attr_frameborder_after(&$root, &$pipeline) {};

function attr_iframe_marginheight_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_PADDING_TOP);
  $handler->css((int)$root->get_attribute('marginheight').'px',$pipeline);
  $handler =& CSS::get_handler(CSS_PADDING_BOTTOM);
  $handler->css((int)$root->get_attribute('marginheight').'px',$pipeline);
}
function attr_iframe_marginheight_after_styles(&$root, &$pipeline) {};
function attr_iframe_marginheight_after(&$root, &$pipeline) {};

function attr_iframe_marginwidth_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_PADDING_RIGHT);
  $handler->css((int)$root->get_attribute('marginwidth').'px',$pipeline);
  $handler =& CSS::get_handler(CSS_PADDING_LEFT);
  $handler->css((int)$root->get_attribute('marginwidth').'px',$pipeline);
}
function attr_iframe_marginwidth_after_styles(&$root, &$pipeline) {};
function attr_iframe_marginwidth_after(&$root, &$pipeline) {};


// BODY-specific
function attr_body_text_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_COLOR);
  $handler->css($root->get_attribute('text'),$pipeline);
}
function attr_body_text_after_styles(&$root, &$pipeline) {};
function attr_body_text_after(&$root, &$pipeline) {};

function attr_body_link_before(&$root, &$pipeline) {
  $color = $root->get_attribute('link');

  // -1000 means priority modifier; so, any real CSS rule will have more priority than 
  // this fake rule

  $collection = new CSSPropertyCollection();
  $collection->addProperty(CSSPropertyDeclaration::create(CSS_COLOR, $color, $pipeline));
  $rule = array(array(SELECTOR_SEQUENCE, array(array(SELECTOR_TAG, 'a'),
                                               array(SELECTOR_PSEUDOCLASS_LINK_LOW_PRIORITY))),
                $collection,
                '',
                -1000);

  $css =& $pipeline->getCurrentCSS();
  $css->add_rule($rule, $pipeline);
} 
function attr_body_link_after_styles(&$root, &$pipeline) {};
function attr_body_link_after(&$root, &$pipeline) {};

function attr_body_topmargin_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_MARGIN_TOP);
  $handler->css((int)$root->get_attribute('topmargin').'px',$pipeline);
}
function attr_body_topmargin_after_styles(&$root, &$pipeline) {};
function attr_body_topmargin_after(&$root, &$pipeline) {};

function attr_body_leftmargin_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_MARGIN_LEFT);
  $handler->css((int)$root->get_attribute('leftmargin').'px',$pipeline);
}
function attr_body_leftmargin_after_styles(&$root, &$pipeline) {};
function attr_body_leftmargin_after(&$root, &$pipeline) {};

function attr_body_marginheight_before(&$root, &$pipeline) {
  $css_state =& $pipeline->getCurrentCSSState();

  $h_top    =& CSS::get_handler(CSS_MARGIN_TOP);
  $h_bottom =& CSS::get_handler(CSS_MARGIN_BOTTOM);

  $top       = $h_top->get($css_state->getState());

  $h_bottom->css(((int)$root->get_attribute('marginheight') - $top->value).'px',$pipeline);
}
function attr_body_marginheight_after_styles(&$root, &$pipeline) {};
function attr_body_marginheight_after(&$root, &$pipeline) {};

function attr_body_marginwidth_before(&$root, &$pipeline) {
  $css_state =& $pipeline->getCurrentCSSState();

  $h_left  =& CSS::get_handler(CSS_MARGIN_LEFT);
  $h_right =& CSS::get_handler(CSS_MARGIN_RIGHT);

  $left = $h_left->get($css_state->getState());

  $h_right->css(((int)$root->get_attribute('marginwidth') - $left->value).'px',$pipeline);
}
function attr_body_marginwidth_after_styles(&$root, &$pipeline) {};
function attr_body_marginwidth_after(&$root, &$pipeline) {};

// === nowrap
function attr_nowrap_before(&$root, &$pipeline) {
  $css_state =& $pipeline->getCurrentCSSState();
  $css_state->setProperty(CSS_HTML2PS_NOWRAP, NOWRAP_NOWRAP);
} 

function attr_nowrap_after_styles(&$root, &$pipeline) {}
function attr_nowrap_after(&$root, &$pipeline) {}

// === hspace

function attr_hspace_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_PADDING_LEFT);
  $handler->css((int)$root->get_attribute('hspace').'px',$pipeline);
  $handler =& CSS::get_handler(CSS_PADDING_RIGHT);
  $handler->css((int)$root->get_attribute('hspace').'px',$pipeline);
}

function attr_hspace_after_styles(&$root, &$pipeline) {}

function attr_hspace_after(&$root, &$pipeline) {}

// === vspace

function attr_vspace_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_PADDING_TOP);
  $handler->css((int)$root->get_attribute('vspace').'px',$pipeline);
  $handler =& CSS::get_handler(CSS_PADDING_BOTTOM);
  $handler->css((int)$root->get_attribute('vspace').'px',$pipeline);
}

function attr_vspace_after_styles(&$root, &$pipeline) {}
function attr_vspace_after(&$root, &$pipeline) {}

// === background

function attr_background_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_BACKGROUND_IMAGE);
  $handler->css('url('.$root->get_attribute('background').')',$pipeline);
}
function attr_background_after_styles(&$root, &$pipeline) {}
function attr_background_after(&$root, &$pipeline) {}

// === align

function attr_table_float_align_before(&$root, &$pipeline) {}
function attr_table_float_align_after_styles(&$root, &$pipeline) {
  if ($root->get_attribute('align') === 'center') {     
    $margin_left =& CSS::get_handler(CSS_MARGIN_LEFT);
    $margin_left->css('auto',$pipeline);
    
    $margin_right =& CSS::get_handler(CSS_MARGIN_RIGHT);
    $margin_right->css('auto',$pipeline);
  } else {
    $float =& CSS::get_handler(CSS_FLOAT);
    $css_state =& $pipeline->getCurrentCSSState();
    $float->replace($float->parse($root->get_attribute('align')),
                    $css_state);
  };
}
function attr_table_float_align_after(&$root, &$pipeline) {}

function attr_img_align_before(&$root, &$pipeline) {
  if (preg_match('/left|right/', $root->get_attribute('align'))) {
    $float =& CSS::get_handler(CSS_FLOAT);
    $css_state =& $pipeline->getCurrentCSSState();
    $float->replace($float->parse($root->get_attribute('align')),
                    $css_state);
  } else {
    $handler =& CSS::get_handler(CSS_VERTICAL_ALIGN);
    $css_state =& $pipeline->getCurrentCSSState();
    $handler->replace($handler->parse($root->get_attribute('align')),
                      $css_state);
  };
}
function attr_img_align_after_styles(&$root, &$pipeline) {}
function attr_img_align_after(&$root, &$pipeline) {}

function attr_self_align_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_HTML2PS_LOCALALIGN);
  $css_state =& $pipeline->getCurrentCSSState();

  switch ($root->get_attribute('align')) {
  case 'left':
    $handler->replace(LA_LEFT,
                      $css_state);
    break;
  case 'center':
    $handler->replace(LA_CENTER,
                      $css_state);
    break;
  case 'right':
    $handler->replace(LA_RIGHT,
                      $css_state);
    break;
  default:
    $handler->replace(LA_LEFT, 
                      $css_state);
    break;
  };
}

function attr_self_align_after_styles(&$root, &$pipeline) {}
function attr_self_align_after(&$root, &$pipeline) {}

// === bordercolor

function attr_table_bordercolor_before(&$root, &$pipeline) {
  $color = parse_color_declaration($root->get_attribute('bordercolor'));

  $css_state =& $pipeline->getCurrentCSSState();
  $border =& $css_state->getProperty(CSS_HTML2PS_TABLE_BORDER);
  $border =& $border->copy();
  
  $border->left->color   = $color;
  $border->right->color  = $color;
  $border->top->color    = $color;
  $border->bottom->color = $color;

//   $css_state->pushState();
//   $css_state->setProperty(CSS_HTML2PS_TABLE_BORDER, $border);

//   $css_state->pushState();
//   $css_state->setProperty(CSS_BORDER, $border);
}

function attr_table_bordercolor_after_styles(&$root, &$pipeline) {
//   $css_state =& $pipeline->getCurrentCSSState();
//   $css_state->popState();
}

function attr_table_bordercolor_after(&$root, &$pipeline) { 
//   $css_state =& $pipeline->getCurrentCSSState();
//   $css_state->popState();
}

// === border

function attr_border_before(&$root, &$pipeline) {
  $width = (int)$root->get_attribute('border');

  $css_state =& $pipeline->getCurrentCSSState();
  $border =& $css_state->getProperty(CSS_BORDER);
  $border =& $border->copy();

  $border->left->width   = Value::fromData($width, UNIT_PX);
  $border->right->width  = Value::fromData($width, 'px');
  $border->top->width    = Value::fromData($width, 'px');
  $border->bottom->width = Value::fromData($width, 'px');
  
  $border->left->style   = BS_SOLID;
  $border->right->style  = BS_SOLID;
  $border->top->style    = BS_SOLID;
  $border->bottom->style = BS_SOLID;

  $css_state->setProperty(CSS_BORDER, $border);
}

function attr_border_after_styles(&$root, &$pipeline) {}
function attr_border_after(&$root, &$pipeline) {}

// === rules (table)

function attr_table_rules_before(&$root, &$pipeline) {
  /**
   * Handle 'rules' attribute
   */
  $rules = $root->get_attribute('rules');

  $css_state =& $pipeline->getCurrentCSSState();
  $border = $css_state->getProperty(CSS_HTML2PS_TABLE_BORDER);

  switch ($rules) {
  case 'none':
    $border->left->style   = BS_NONE;
    $border->right->style  = BS_NONE;
    $border->top->style    = BS_NONE;
    $border->bottom->style = BS_NONE;
    break;
  case 'groups':
    // Not supported
    break;
  case 'rows':
    $border->left->style   = BS_NONE;
    $border->right->style  = BS_NONE;
    break;
  case 'cols':
    $border->top->style    = BS_NONE;
    $border->bottom->style = BS_NONE;
    break;
  case 'all':
    break;
  };
 
  $css_state->setProperty(CSS_HTML2PS_TABLE_BORDER, $border);
}

function attr_table_rules_after_styles(&$root, &$pipeline) {}
function attr_table_rules_after(&$root, &$pipeline) {}

// === border (table)

function attr_table_border_before(&$root, &$pipeline) {
  $width = (int)$root->get_attribute('border');

  $css_state =& $pipeline->getCurrentCSSState();
  $border =& $css_state->getProperty(CSS_HTML2PS_TABLE_BORDER);
  $border =& $border->copy();

  $border->left->width   = Value::fromData($width, UNIT_PX);
  $border->right->width  = Value::fromData($width, UNIT_PX);
  $border->top->width    = Value::fromData($width, UNIT_PX);
  $border->bottom->width = Value::fromData($width, UNIT_PX);
  
  $border->left->style   = BS_SOLID;
  $border->right->style  = BS_SOLID;
  $border->top->style    = BS_SOLID;
  $border->bottom->style = BS_SOLID;
  
  $css_state->setProperty(CSS_BORDER, $border);

  $css_state->pushState();
  $border =& $border->copy();
  $css_state->setProperty(CSS_HTML2PS_TABLE_BORDER, $border);
}

function attr_table_border_after_styles(&$root, &$pipeline) {}

function attr_table_border_after(&$root, &$pipeline) {
  $css_state =& $pipeline->getCurrentCSSState();
  $css_state->popState();  
}

// === dir
function attr_dir_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_TEXT_ALIGN);
  switch (strtolower($root->get_attribute('dir'))) {
  case 'ltr':
    $handler->css('left',$pipeline); 
    return;
  case 'rtl':
    $handler->css('right',$pipeline); 
    return;
  };
}

function attr_dir_after_styles(&$root, &$pipeline) {}
function attr_dir_after(&$root, &$pipeline) {}

// === align
function attr_align_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_TEXT_ALIGN);
  $handler->css($root->get_attribute('align'),$pipeline); 

  $handler =& CSS::get_handler(CSS_HTML2PS_ALIGN);
  $handler->css($root->get_attribute('align'),$pipeline);
}

function attr_align_after_styles(&$root, &$pipeline) {}

function attr_align_after(&$root, &$pipeline) {}

// valign
// 'valign' attribute value for table rows is inherited
function attr_row_valign_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_VERTICAL_ALIGN);
  $handler->css($root->get_attribute('valign'),$pipeline);
}
function attr_row_valign_after_styles(&$root, &$pipeline) {}
function attr_row_valign_after(&$root, &$pipeline) {}

// 'valign' attribute value for boxes other than table rows is not inherited
function attr_valign_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_VERTICAL_ALIGN);
  $handler->css($root->get_attribute('valign'),
                $pipeline);
}

function attr_valign_after_styles(&$root, &$pipeline) {}
function attr_valign_after(&$root, &$pipeline) {}
              
// bgcolor

function attr_bgcolor_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_BACKGROUND_COLOR);
  $handler->css($root->get_attribute('bgcolor'), $pipeline); 
}
function attr_bgcolor_after_styles(&$root, &$pipeline) {}
function attr_bgcolor_after(&$root, &$pipeline) {}

// width

function attr_width_before(&$root, &$pipeline) {
  $width =& CSS::get_handler(CSS_WIDTH);

  $value = $root->get_attribute('width');
  if (preg_match('/^\d+$/', $value)) { $value .= 'px'; };

  $width->css($value,$pipeline);
}

function attr_width_after_styles(&$root, &$pipeline) {}
function attr_width_after(&$root, &$pipeline) {}

// height

// Difference between 'attr_height' and 'attr_height_required':
// attr_height sets the minimal box height so that is cal be expanded by it content;
// a good example is table rows and cells; on the other side, attr_height_required
// sets the fixed box height - it is useful for boxes which content height can be greater
// that box height - marquee or iframe, for example

function attr_height_required_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_HEIGHT);

  $value = $root->get_attribute('height');
  if (preg_match('/^\d+$/', $value)) { $value .= 'px'; };
  $handler->css($value,$pipeline);
}

function attr_height_required_after_styles(&$root, &$pipeline) {}

function attr_height_required_after(&$root, &$pipeline) {}

function attr_height_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_MIN_HEIGHT);

  $value = $root->get_attribute('height');
  if (preg_match('/^\d+$/', $value)) { $value .= 'px'; };
  $handler->css($value,$pipeline);
}

function attr_height_after_styles(&$root, &$pipeline) {}
function attr_height_after(&$root, &$pipeline) {}

// FONT attributes
function attr_font_size_before(&$root, &$pipeline) {
  $size = $root->get_attribute('size');

  /**
   * Check if attribute value is empty; no actions will be taken in this case
   */
  if ($size == '') { return; };

  if ($size{0} == '-') {
    $koeff = 1;
    $repeats = (int)substr($size,1);
    for ($i=0; $i<$repeats; $i++) {
      $koeff *= 1/1.2;
    };
    $newsize = sprintf('%.2fem', round($koeff, 2));
  } else if ($size{0} == '+') {
    $koeff = 1;
    $repeats = (int)substr($size,1);
    for ($i=0; $i<$repeats; $i++) {
      $koeff *= 1.2;
    };
    $newsize = sprintf('%.2fem', round($koeff, 2));
  } else {
    switch ((int)$size) {
    case 1:
      $newsize = BASE_FONT_SIZE_PT/1.2/1.2;
      break;
    case 2:
      $newsize = BASE_FONT_SIZE_PT/1.2;
      break;
    case 3:
      $newsize = BASE_FONT_SIZE_PT;
      break;
    case 4:
      $newsize = BASE_FONT_SIZE_PT*1.2;
      break;
    case 5:
      $newsize = BASE_FONT_SIZE_PT*1.2*1.2;
      break;
    case 6:
      $newsize = BASE_FONT_SIZE_PT*1.2*1.2*1.2;
      break;
    case 7:
      $newsize = BASE_FONT_SIZE_PT*1.2*1.2*1.2*1.2;
      break;
    default:
      $newsize = BASE_FONT_SIZE_PT;
      break;
    };
    $newsize = $newsize . 'pt';
  };

  $handler =& CSS::get_handler(CSS_FONT_SIZE);
  $handler->css($newsize, $pipeline);
}
function attr_font_size_after_styles(&$root, &$pipeline) {}
function attr_font_size_after(&$root, &$pipeline) {}

function attr_font_color_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_COLOR);
  $handler->css($root->get_attribute('color'),$pipeline);
}
function attr_font_color_after_styles(&$root, &$pipeline) {}
function attr_font_color_after(&$root, &$pipeline) {}

function attr_font_face_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_FONT_FAMILY);
  $handler->css($root->get_attribute('face'), $pipeline);
}
function attr_font_face_after_styles(&$root, &$pipeline) {}
function attr_font_face_after(&$root, &$pipeline) {}

function attr_form_action_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_HTML2PS_FORM_ACTION);
  if ($root->has_attribute('action')) {
    $handler->css($pipeline->guess_url($root->get_attribute('action')),$pipeline);
  } else {
    $handler->css(null,$pipeline);
  };
}
function attr_form_action_after_styles(&$root, &$pipeline) {}
function attr_form_action_after(&$root, &$pipeline) {}

function attr_input_name_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_HTML2PS_FORM_RADIOGROUP);
  if ($root->has_attribute('name')) {
    $handler->css($root->get_attribute('name'),$pipeline);
  };
}
function attr_input_name_after_styles(&$root, &$pipeline) {}
function attr_input_name_after(&$root, &$pipeline) {}

function attr_input_size_before(&$root, &$pipeline) {
  // Check if current node has 'size' attribute
  if (!$root->has_attribute('size')) {
    return;
  };
  $size = $root->get_attribute('size');

  // Get the exact type of the input node, as 'size' has
  // different meanings for different input types
  $type = 'text';
  if ($root->has_attribute('type')) {
    $type = strtolower($root->get_attribute('type'));
  };

  switch ($type) {
  case 'text':
  case 'password':
    $handler =& CSS::get_handler(CSS_WIDTH);
    $width = sprintf('%.2fem', INPUT_SIZE_BASE_EM + $size*INPUT_SIZE_EM_KOEFF);
    $handler->css($width, $pipeline);
    break;
  };
};

function attr_input_size_after_styles(&$root, &$pipeline) {}
function attr_input_size_after(&$root, &$pipeline) {}

// TABLE

function attr_cellspacing_before(&$root, &$pipeline) {
  $css_state =& $pipeline->getCurrentCSSState();
  $handler =& CSS::get_handler(CSS_HTML2PS_CELLSPACING);
  $handler->replace(Value::fromData((int)$root->get_attribute('cellspacing'), UNIT_PX),
                    $css_state);
}
function attr_cellspacing_after_styles(&$root, &$pipeline) {}
function attr_cellspacing_after(&$root, &$pipeline) {}

function attr_cellpadding_before(&$root, &$pipeline) {
  $css_state =& $pipeline->getCurrentCSSState();
  $handler =& CSS::get_handler(CSS_HTML2PS_CELLPADDING);
  $handler->replace(Value::fromData((int)$root->get_attribute('cellpadding'), UNIT_PX),
                    $css_state);
}
function attr_cellpadding_after_styles(&$root, &$pipeline) {}
function attr_cellpadding_after(&$root, &$pipeline) {}

// UL/OL 'start' attribute
function attr_start_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_HTML2PS_LIST_COUNTER);
  $css_state =& $pipeline->getCurrentCSSState();
  $handler->replace((int)$root->get_attribute('start'),
                    $css_state);
}
function attr_start_after_styles(&$root, &$pipeline) {}
function attr_start_after(&$root, &$pipeline) {}

// UL 'type' attribute
//
// For  the UL  element, possible  values for  the type  attribute are
// disc, square, and circle. The default value depends on the level of
// nesting of the current list. These values are case-insensitive.
//
// How each value is presented  depends on the user agent. User agents
// should attempt to  present a "disc" as a  small filled-in circle, a
// "circle"  as a  small circle  outline, and  a "square"  as  a small
// square outline.
//
function attr_ul_type_before(&$root, &$pipeline) {
  $type = (string)$root->get_attribute('type');
  $handler =& CSS::get_handler(CSS_LIST_STYLE_TYPE);
  $css_state =& $pipeline->getCurrentCSSState();

  switch (strtolower($type)) {
  case 'disc':
    $handler->replace(LST_DISC, $css_state);
    break;
  case 'circle':
    $handler->replace(LST_CIRCLE, $css_state);
    break;
  case 'square':
    $handler->replace(LST_SQUARE, $css_state);
    break;
  };
}
function attr_ul_type_after_styles(&$root, &$pipeline) {}
function attr_ul_type_after(&$root, &$pipeline) {}

// OL 'type' attribute
//
// For the OL element, possible values for the type attribute are summarized in the table below (they are case-sensitive):
// Type 	Numbering style
// 1 	arabic numbers 	1, 2, 3, ...
// a 	lower alpha 	a, b, c, ...
// A 	upper alpha 	A, B, C, ...
// i 	lower roman 	i, ii, iii, ...
// I 	upper roman 	I, II, III, ...
//
function attr_ol_type_before(&$root, &$pipeline) {
  $type = (string)$root->get_attribute('type');
  $handler =& CSS::get_handler(CSS_LIST_STYLE_TYPE);
  $css_state =& $pipeline->getCurrentCSSState();

  switch ($type) {
  case '1':
    $handler->replace(LST_DECIMAL, $css_state);
    break;
  case 'a':
    $handler->replace(LST_LOWER_LATIN, $css_state);
    break;
  case 'A':
    $handler->replace(LST_UPPER_LATIN, $css_state);
    break;
  case 'i':
    $handler->replace(LST_LOWER_ROMAN, $css_state);
    break;
  case 'I':
    $handler->replace(LST_UPPER_ROMAN, $css_state);
    break;
  };
}
function attr_ol_type_after_styles(&$root, &$pipeline) {}
function attr_ol_type_after(&$root, &$pipeline) {}

// Textarea

function attr_textarea_rows_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_HEIGHT);
  $handler->css(sprintf('%dem', (int)$root->get_attribute('rows')*1.40),$pipeline);
}
function attr_textarea_rows_after_styles(&$root, &$pipeline) {}
function attr_textarea_rows_after(&$root, &$pipeline) {}

function attr_textarea_cols_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_WIDTH);
  $handler->css(sprintf('%dem', (int)$root->get_attribute('cols')*0.675),$pipeline);
}
function attr_textarea_cols_after_styles(&$root, &$pipeline) {}
function attr_textarea_cols_after(&$root, &$pipeline) {}

/**
 * HR-specific attributes
 */
function attr_hr_color_before(&$root, &$pipeline) {
  $handler =& CSS::get_handler(CSS_BORDER_COLOR);
  $handler->css($root->get_attribute('color'), $pipeline); 
}
function attr_hr_color_after_styles(&$root, &$pipeline) {}
function attr_hr_color_after(&$root, &$pipeline) {}


?>