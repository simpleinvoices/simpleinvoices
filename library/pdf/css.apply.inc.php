<?php

function apply_css_rule_obj($properties, $baseurl, $root, &$pipeline) {
  $pipeline->push_base_url($baseurl);

  foreach ($properties as $key => $value) {
    switch ($key) {
    case 'border':
      css_border($value, $root);
      break;
    case 'border-color':
      css_border_color($value, $root);
      break;
    case 'border-top':
      css_border_top($value, $root);
      break;
    case 'border-right':
      css_border_right($value, $root);
      break;
    case 'border-bottom':
      css_border_bottom($value, $root);
      break;
    case 'border-left':
      css_border_left($value, $root);
      break;
    case 'border-style':
      css_border_style($value, $root);
      break;
    case 'border-top-style':
      css_border_top_style($value, $root);
      break;
    case 'border-right-style':
      css_border_right_style($value, $root);
      break;
    case 'border-bottom-style':
      css_border_bottom_style($value, $root);
      break;
    case 'border-left-style':
      css_border_left_style($value, $root);
      break;
    case 'border-top-color':
      css_border_top_color($value, $root);
      break;
    case 'border-right-color':
      css_border_right_color($value, $root);
      break;
    case 'border-bottom-color':
      css_border_bottom_color($value, $root);
      break;
    case 'border-left-color':
      css_border_left_color($value, $root);
      break;
    case 'border-width':
      css_border_width($value, $root);
      break;
    case 'border-top-width':
      css_border_top_width($value, $root);
      break;
    case 'border-right-width':
      css_border_right_width($value, $root);
      break;
    case 'border-bottom-width':
      css_border_bottom_width($value, $root);
      break;
    case 'border-left-width':
      css_border_left_width($value, $root);
      break;
    case 'font':
      css_font($value, $root);
      break;
    case 'font-family':
      css_font_family($value, $root);
      break;
    case 'font-size':
      css_font_size($value, $root);
      break;
    case 'font-style':
      css_font_style($value, $root);
      break;
    case 'font-weight':
      css_font_weight($value, $root);
      break;
    case 'line-height':
      css_line_height($value, $root);
      break;
    default:
      $handler =& get_css_handler($key);
      if ($handler) {
        $handler->replace($value, $pipeline);
      };
      break;
    };    
  };

  $pipeline->pop_base_url();
}

?>
