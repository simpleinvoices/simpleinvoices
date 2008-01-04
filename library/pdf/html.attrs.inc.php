<?php
// $Header: /cvsroot/html2ps/html.attrs.inc.php,v 1.50 2006/05/27 15:33:27 Konstantin Exp $

global $g_tag_attrs;
$g_tag_attrs = array(
                     /**
                      * Attribute handlers applicable to all tags
                      */
                     '*'       => array(
                                        'id'   => 'attr_id'
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
                                        'name'  => 'attr_input_name'
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
                                        'start' => 'attr_start'
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
                                        'start' => 'attr_start'
                                        )
);


function execute_attrs_before($root, &$pipeline) { execute_attrs($root, "_before", $pipeline); }
function execute_attrs_after($root, &$pipeline) { execute_attrs($root, "_after", $pipeline); }
function execute_attrs_after_styles($root, &$pipeline) { execute_attrs($root, "_after_styles", $pipeline); }

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
  $handler =& get_css_handler('-html2ps-link-destination');
  $handler->css($root->get_attribute("name"), $pipeline);
}
function attr_name_after_styles(&$root, &$pipeline) {};
function attr_name_after(&$root, &$pipeline) {};

// A ID
function attr_id_before(&$root, &$pipeline) {
  $handler =& get_css_handler('-html2ps-link-destination');
  $handler->css($root->get_attribute("id"), $pipeline);
}
function attr_id_after_styles(&$root, &$pipeline) {};
function attr_id_after(&$root, &$pipeline) {};


// A HREF
function attr_href_before(&$root, &$pipeline) {
  $handler =& get_css_handler('-html2ps-link-target');
  $handler->css($root->get_attribute("href"), $pipeline);
}
function attr_href_after_styles(&$root, &$pipeline) {};
function attr_href_after(&$root, &$pipeline) {};

// IFRAME 
function attr_frameborder_before(&$root, &$pipeline) {
  if ($root->get_attribute("frameborder") == "1") {
    css_border("inset black 1px",$root);
  } else {
    pop_border();
    push_border(default_border());
  };
}
function attr_frameborder_after_styles(&$root, &$pipeline) {};
function attr_frameborder_after(&$root, &$pipeline) {};

function attr_iframe_marginheight_before(&$root, &$pipeline) {
  $handler =& get_css_handler('padding-top');
  $handler->css((int)$root->get_attribute("marginheight")."px",$pipeline);
  $handler =& get_css_handler('padding-bottom');
  $handler->css((int)$root->get_attribute("marginheight")."px",$pipeline);
}
function attr_iframe_marginheight_after_styles(&$root, &$pipeline) {};
function attr_iframe_marginheight_after(&$root, &$pipeline) {};

function attr_iframe_marginwidth_before(&$root, &$pipeline) {
  $handler =& get_css_handler('padding-right');
  $handler->css((int)$root->get_attribute("marginwidth")."px",$pipeline);
  $handler =& get_css_handler('padding-left');
  $handler->css((int)$root->get_attribute("marginwidth")."px",$pipeline);
}
function attr_iframe_marginwidth_after_styles(&$root, &$pipeline) {};
function attr_iframe_marginwidth_after(&$root, &$pipeline) {};


// BODY-specific
function attr_body_text_before(&$root, &$pipeline) {
  $handler =& get_css_handler('color');
  $handler->css($root->get_attribute("text"),$pipeline);
}
function attr_body_text_after_styles(&$root, &$pipeline) {};
function attr_body_text_after(&$root, &$pipeline) {};

function attr_body_link_before(&$root, &$pipeline) {
  $color = $root->get_attribute("link");

  // -1000 means priority modifier; so, any real CSS rule will have more priority than 
  // this fake rule

  $rule = array(array(SELECTOR_SEQUENCE, array(array(SELECTOR_TAG, "a"),
                                               array(SELECTOR_PSEUDOCLASS_LINK_LOW_PRIORITY))),
                array('color' => $color),
                "",
                -1000);

  global $g_css_obj;
  $g_css_obj->add_rule($rule, $pipeline);
} 
function attr_body_link_after_styles(&$root, &$pipeline) {};
function attr_body_link_after(&$root, &$pipeline) {};

function attr_body_topmargin_before(&$root, &$pipeline) {
  $handler =& get_css_handler('margin-top');
  $handler->css((int)$root->get_attribute("topmargin")."px",$pipeline);
}
function attr_body_topmargin_after_styles(&$root, &$pipeline) {};
function attr_body_topmargin_after(&$root, &$pipeline) {};

function attr_body_leftmargin_before(&$root, &$pipeline) {
  $handler =& get_css_handler('margin-left');
  $handler->css((int)$root->get_attribute("leftmargin")."px",$pipeline);
}
function attr_body_leftmargin_after_styles(&$root, &$pipeline) {};
function attr_body_leftmargin_after(&$root, &$pipeline) {};

function attr_body_marginheight_before(&$root, &$pipeline) {
  $h_top    =& get_css_handler('margin-top');
  $h_bottom =& get_css_handler('margin-bottom');

  $top      = $h_top->get();

  $h_bottom->css(((int)$root->get_attribute("marginheight") - $top->value)."px",$pipeline);
}
function attr_body_marginheight_after_styles(&$root, &$pipeline) {};
function attr_body_marginheight_after(&$root, &$pipeline) {};

function attr_body_marginwidth_before(&$root, &$pipeline) {
  $h_left  =& get_css_handler('margin-left');
  $h_right =& get_css_handler('margin-right');

  $left = $h_left->get();

  $h_right->css(((int)$root->get_attribute("marginwidth") - $left->value)."px",$pipeline);
}
function attr_body_marginwidth_after_styles(&$root, &$pipeline) {};
function attr_body_marginwidth_after(&$root, &$pipeline) {};

// === nowrap
function attr_nowrap_before(&$root, &$pipeline) {
  $handler =& get_css_handler('-nowrap');
  $handler->push(NOWRAP_NOWRAP);
} 

function attr_nowrap_after_styles(&$root, &$pipeline) {}
function attr_nowrap_after(&$root, &$pipeline) {}

// === hspace

function attr_hspace_before(&$root, &$pipeline) {
  $handler =& get_css_handler('padding-left');
  $handler->css((int)$root->get_attribute("hspace")."px",$pipeline);
  $handler =& get_css_handler('padding-right');
  $handler->css((int)$root->get_attribute("hspace")."px",$pipeline);
}

function attr_hspace_after_styles(&$root, &$pipeline) {}

function attr_hspace_after(&$root, &$pipeline) {}

// === vspace

function attr_vspace_before(&$root, &$pipeline) {
  $handler =& get_css_handler('padding-top');
  $handler->css((int)$root->get_attribute("vspace")."px",$pipeline);
  $handler =& get_css_handler('padding-bottom');
  $handler->css((int)$root->get_attribute("vspace")."px",$pipeline);
}

function attr_vspace_after_styles(&$root, &$pipeline) {}
function attr_vspace_after(&$root, &$pipeline) {}

// === background

function attr_background_before(&$root, &$pipeline) {
  $handler =& get_css_handler('background-image');
  $handler->css("url(".$root->get_attribute("background").")",$pipeline);
}
function attr_background_after_styles(&$root, &$pipeline) {}
function attr_background_after(&$root, &$pipeline) {}

// === align

function attr_table_float_align_before(&$root, &$pipeline) {
  if ($root->get_attribute("align") === "center") {
//       $handler =& get_css_handler('-localalign');
//       $handler->replace(LA_CENTER);
      
    $margin_left =& get_css_handler('margin-left');
    $margin_left->css('auto',$pipeline);
    
    $margin_right =& get_css_handler('margin-right');
    $margin_right->css('auto',$pipeline);
  } else {
    $float =& get_css_handler('float');
    $float->replace($float->parse($root->get_attribute("align")));
  };
}
function attr_table_float_align_after_styles(&$root, &$pipeline) {}
function attr_table_float_align_after(&$root, &$pipeline) {}

function attr_img_align_before(&$root, &$pipeline) {
  if (preg_match("/left|right/", $root->get_attribute("align"))) {
    $float =& get_css_handler('float');
    $float->replace($float->parse($root->get_attribute("align")));
  } else {
    $handler =& get_css_handler('vertical-align');
    $handler->replace($handler->parse($root->get_attribute("align")));
  };
}
function attr_img_align_after_styles(&$root, &$pipeline) {}
function attr_img_align_after(&$root, &$pipeline) {}

function attr_self_align_before(&$root, &$pipeline) {
  $handler =& get_css_handler('-localalign');
  switch ($root->get_attribute("align")) {
  case "left":
    $handler->replace(LA_LEFT);
    break;
  case "center":
    $handler->replace(LA_CENTER);
    break;
  case "right":
    $handler->replace(LA_RIGHT);
    break;
  default:
    $handler->replace(LA_LEFT);
    break;
  };
}

function attr_self_align_after_styles(&$root, &$pipeline) {}
function attr_self_align_after(&$root, &$pipeline) {}

// === bordercolor

function attr_table_bordercolor_before(&$root, &$pipeline) {
  $color = parse_color_declaration($root->get_attribute("bordercolor"), array(0,0,0));

  $border = get_table_border();
  
  $border['left']['color']   = $color;
  $border['right']['color']  = $color;
  $border['top']['color']    = $color;
  $border['bottom']['color'] = $color;
  
  pop_border();
  push_border($border); 
  push_table_border($border);
}

function attr_table_bordercolor_after_styles(&$root, &$pipeline) {
  pop_border();
}

function attr_table_bordercolor_after(&$root, &$pipeline) {
  pop_table_border();
}

// === border

function attr_border_before(&$root, &$pipeline) {
  $width = (int)$root->get_attribute("border");

  $border = get_border();
  $border['left']['width']   = $width . "px";
  $border['right']['width']  = $width . "px";
  $border['top']['width']    = $width . "px";
  $border['bottom']['width'] = $width . "px";
  
  $border['left']['style']   = BS_SOLID;
  $border['right']['style']  = BS_SOLID;
  $border['top']['style']    = BS_SOLID;
  $border['bottom']['style'] = BS_SOLID;

  pop_border();
  push_border($border); 
}

function attr_border_after_styles(&$root, &$pipeline) {}
function attr_border_after(&$root, &$pipeline) {}

// === rules (table)

function attr_table_rules_before(&$root, &$pipeline) {
  /**
   * Handle 'rules' attribute
   */
  $rules = $root->get_attribute("rules");
  $border = get_table_border();
  switch ($rules) {
  case "none":
    $border['left']['style']   = BS_NONE;
    $border['right']['style']  = BS_NONE;
    $border['top']['style']    = BS_NONE;
    $border['bottom']['style'] = BS_NONE;
    break;
  case "groups":
    // Not supported
    break;
  case "rows":
    $border['left']['style']   = BS_NONE;
    $border['right']['style']  = BS_NONE;
    break;
  case "cols":
    $border['top']['style']    = BS_NONE;
    $border['bottom']['style'] = BS_NONE;
    break;
  case "all":
    break;
  };
 
  pop_table_border();
  push_table_border($border);
}

function attr_table_rules_after_styles(&$root, &$pipeline) {}
function attr_table_rules_after(&$root, &$pipeline) {}

// === border (table)

function attr_table_border_before(&$root, &$pipeline) {
  $width = (int)$root->get_attribute("border");

  $border = get_table_border();
  $border['left']['width']   = $width . "px";
  $border['right']['width']  = $width . "px";
  $border['top']['width']    = $width . "px";
  $border['bottom']['width'] = $width . "px";
  
  $border['left']['style']   = BS_SOLID;
  $border['right']['style']  = BS_SOLID;
  $border['top']['style']    = BS_SOLID;
  $border['bottom']['style'] = BS_SOLID;
  
  pop_border();
  push_border($border); 

  push_table_border($border);
}

function attr_table_border_after_styles(&$root, &$pipeline) {}

function attr_table_border_after(&$root, &$pipeline) {
  pop_table_border(); 
}

// === align
function attr_align_before(&$root, &$pipeline) {
  $handler =& get_css_handler('text-align');
  $handler->css($root->get_attribute("align"),$pipeline); 

  $handler =& get_css_handler('-align');
  $handler->css($root->get_attribute("align"),$pipeline);
}

function attr_align_after_styles(&$root, &$pipeline) {}

function attr_align_after(&$root, &$pipeline) {}

// valign
// 'valign' attribute value for table rows is inherited
function attr_row_valign_before(&$root, &$pipeline) {
  $handler =& get_css_handler('vertical-align');
  $handler->css($root->get_attribute("valign"),$pipeline);
}
function attr_row_valign_after_styles(&$root, &$pipeline) {}
function attr_row_valign_after(&$root, &$pipeline) {}

// 'valign' attribute value for boxes other than table rows is not inherited
function attr_valign_before(&$root, &$pipeline) {
  $handler =& get_css_handler('vertical-align');
  $handler->css($root->get_attribute("valign"),$pipeline);
}

function attr_valign_after_styles(&$root, &$pipeline) {}
function attr_valign_after(&$root, &$pipeline) {}
              
// bgcolor

function attr_bgcolor_before(&$root, &$pipeline) {
  $handler =& get_css_handler('background-color');
  $handler->css($root->get_attribute("bgcolor"),$pipeline); 
}
function attr_bgcolor_after_styles(&$root, &$pipeline) {}
function attr_bgcolor_after(&$root, &$pipeline) {}

// width

function attr_width_before(&$root, &$pipeline) {
  $width =& get_css_handler('width');

  $value = $root->get_attribute("width");
  if (preg_match("/^\d+$/", $value)) { $value .= "px"; };

  $width->css($value,$pipeline);
}

function attr_width_after_styles(&$root, &$pipeline) {}
function attr_width_after(&$root, &$pipeline) {}

// height

// Difference between "attr_height" and "attr_height_required":
// attr_height sets the minimal box height so that is cal be expanded by it content;
// a good example is table rows and cells; on the other side, attr_height_required
// sets the fixed box height - it is useful for boxes which content height can be greater
// that box height - marquee or iframe, for example

function attr_height_required_before(&$root, &$pipeline) {
  $handler =& get_css_handler('height');

  $value = $root->get_attribute("height");
  if (preg_match("/^\d+$/", $value)) { $value .= "px"; };
  $handler->css($value,$pipeline);
}

function attr_height_required_after_styles(&$root, &$pipeline) {}

function attr_height_required_after(&$root, &$pipeline) {}

function attr_height_before(&$root, &$pipeline) {
  $handler =& get_css_handler('min-height');

  $value = $root->get_attribute("height");
  if (preg_match("/^\d+$/", $value)) { $value .= "px"; };
  $handler->css($value,$pipeline);
}

function attr_height_after_styles(&$root, &$pipeline) {}
function attr_height_after(&$root, &$pipeline) {}

// FONT attributes
function attr_font_size_before(&$root, &$pipeline) {
  $size = $root->get_attribute("size");

  /**
   * Check if attribute value is empty; no actions will be taken in this case
   */
  if ($size == "") { return; };

  if ($size{0} == "-") {
    $koeff = 1;
    $repeats = (int)substr($size,1);
    for ($i=0; $i<$repeats; $i++) {
      $koeff *= 1/1.2;
    };
    $newsize = sprintf("%.2fem", round($koeff, 2));
  } else if ($size{0} == "+") {
    $koeff = 1;
    $repeats = (int)substr($size,1);
    for ($i=0; $i<$repeats; $i++) {
      $koeff *= 1.2;
    };
    $newsize = sprintf("%.2fem", round($koeff, 2));
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
    $newsize = $newsize . "pt";
  };

  pop_font_size(); push_font_size($newsize);
}
function attr_font_size_after_styles(&$root, &$pipeline) {}
function attr_font_size_after(&$root, &$pipeline) {}

function attr_font_color_before(&$root, &$pipeline) {
  $handler =& get_css_handler('color');
  $handler->css($root->get_attribute("color"),$pipeline);
}
function attr_font_color_after_styles(&$root, &$pipeline) {}
function attr_font_color_after(&$root, &$pipeline) {}

function attr_font_face_before(&$root, &$pipeline) {
  pop_font_family();
  push_font_family(parse_font_family($root->get_attribute("face")));
}
function attr_font_face_after_styles(&$root, &$pipeline) {}
function attr_font_face_after(&$root, &$pipeline) {}

function attr_form_action_before(&$root, &$pipeline) {
  $handler =& get_css_handler('-html2ps-form-action');
  if ($root->has_attribute('action')) {
    $handler->css($pipeline->guess_url($root->get_attribute('action')),$pipeline);
  } else {
    $handler->css(null,$pipeline);
  };
}
function attr_form_action_after_styles(&$root, &$pipeline) {}
function attr_form_action_after(&$root, &$pipeline) {}

function attr_input_name_before(&$root, &$pipeline) {
  $handler =& get_css_handler('-html2ps-form-radiogroup');
  if ($root->has_attribute('name')) {
    $handler->css($root->get_attribute('name'),$pipeline);
  };
}
function attr_input_name_after_styles(&$root, &$pipeline) {}
function attr_input_name_after(&$root, &$pipeline) {}

// TABLE

function attr_cellspacing_before(&$root, &$pipeline) {
  $handler =& get_css_handler('-cellspacing');
  $handler->css($root->get_attribute("cellspacing")."px",$pipeline);
}
function attr_cellspacing_after_styles(&$root, &$pipeline) {}
function attr_cellspacing_after(&$root, &$pipeline) {}

function attr_cellpadding_before(&$root, &$pipeline) {
  $handler =& get_css_handler('-cellpadding');
  $handler->css($root->get_attribute("cellpadding")."px",$pipeline);
}
function attr_cellpadding_after_styles(&$root, &$pipeline) {}
function attr_cellpadding_after(&$root, &$pipeline) {}

// UL/OL 'start' attribute
function attr_start_before(&$root, &$pipeline) {
  $handler =& get_css_handler('-list-counter');
  $handler->replace((int)$root->get_attribute("start"));
}
function attr_start_after_styles(&$root, &$pipeline) {}
function attr_start_after(&$root, &$pipeline) {}

// Textarea

function attr_textarea_rows_before(&$root, &$pipeline) {
  $handler =& get_css_handler('height');
  $handler->css(sprintf("%dem", (int)$root->get_attribute("rows")*1.40),$pipeline);
}
function attr_textarea_rows_after_styles(&$root, &$pipeline) {}
function attr_textarea_rows_after(&$root, &$pipeline) {}

function attr_textarea_cols_before(&$root, &$pipeline) {
  $handler =& get_css_handler('width');
  $handler->css(sprintf("%dem", (int)$root->get_attribute("cols")*0.675),$pipeline);
}
function attr_textarea_cols_after_styles(&$root, &$pipeline) {}
function attr_textarea_cols_after(&$root, &$pipeline) {}

/**
 * HR-specific attributes
 */
function attr_hr_color_before(&$root, &$pipeline) {
  css_border_color($root->get_attribute("color"),$root); 
}
function attr_hr_color_after_styles(&$root, &$pipeline) {}
function attr_hr_color_after(&$root, &$pipeline) {}


?>