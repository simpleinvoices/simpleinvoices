<?php

/**
 * @abstract
 */
class BoxPageMargin extends GenericContainerBox {
  /**
   * @param $at_rule CSSAtRuleMarginBox At-rule object describing margin box to be created
   * @return Object Object of concrete BoxPageMargin descendant type
   */
  function &create(&$pipeline, $at_rule) {
    switch ($at_rule->getSelector()) {
    case CSS_MARGIN_BOX_SELECTOR_TOP:
      $box =& new BoxPageMarginTop($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_TOP_LEFT_CORNER:
      $box =& new BoxPageMarginTopLeftCorner($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_TOP_LEFT:
      $box =& new BoxPageMarginTopLeft($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_TOP_CENTER:
      $box =& new BoxPageMarginTopCenter($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT:
      $box =& new BoxPageMarginTopRight($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT_CORNER:
      $box =& new BoxPageMarginTopRightCorner($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_BOTTOM:
      $box =& new BoxPageMarginBottom($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT_CORNER:
      $box =& new BoxPageMarginBottomLeftCorner($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT:
      $box =& new BoxPageMarginBottomLeft($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_BOTTOM_CENTER:
      $box =& new BoxPageMarginBottomCenter($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT:
      $box =& new BoxPageMarginBottomRight($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT_CORNER:
      $box =& new BoxPageMarginBottomRightCorner($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_LEFT_TOP:
      $box =& new BoxPageMarginLeftTop($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_LEFT_MIDDLE:
      $box =& new BoxPageMarginLeftMiddle($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_LEFT_BOTTOM:
      $box =& new BoxPageMarginLeftBottom($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_RIGHT_TOP:
      $box =& new BoxPageMarginRightTop($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_RIGHT_MIDDLE:
      $box =& new BoxPageMarginRightMiddle($pipeline, $at_rule);
      break;
    case CSS_MARGIN_BOX_SELECTOR_RIGHT_BOTTOM:
      $box =& new BoxPageMarginRightBottom($pipeline, $at_rule);
      break;
    default:
      trigger_error("Unknown selector type", E_USER_ERROR);
    };

    return $box;
  }

  function BoxPageMargin(&$pipeline, $at_rule) {
    $state =& $pipeline->getCurrentCSSState();
    $state->pushDefaultState();

    $root = null;
    $at_rule->css->apply($root, $state, $pipeline);

    $this->GenericContainerBox();
    $this->readCSS($state);

    $state->pushDefaultstate();

    /**
     * Check whether 'content' or '-html2ps-html-content' properties had been defined 
     * (if both properties are defined, -html2ps-html-content takes precedence)
     */
    $raw_html_content =& $at_rule->getCSSProperty(CSS_HTML2PS_HTML_CONTENT);
    $html_content = $raw_html_content->render($pipeline->get_counters());

    if ($html_content !== '') {
      // We should wrap html_content in DIV tag, 
      // as we treat only the very first box of the resulting DOM tree as margin box content

      $html_content = html2xhtml("<div>".$html_content."</div>");
      $tree = TreeBuilder::build($html_content);
      $tree_root = traverse_dom_tree_pdf($tree);
      $body_box =& create_pdf_box($tree_root, $pipeline);
      $box =& $body_box->content[0];
    } else {
      $raw_content =& $at_rule->getCSSProperty(CSS_CONTENT);
      $content = $raw_content->render($pipeline->get_counters());

      $box =& InlineBox::create_from_text($content,
                                          WHITESPACE_PRE_LINE,
                                          $pipeline);
    }
    $this->add_child($box);

    $state->popState();
    $state->popState();
  }

  function get_cell_baseline() {
    return 0;
  }

  function reflow(&$driver, &$media, $boxes) {
    $context = new FlowContext;
    $this->_position($media, $boxes, $context);

    $this->setCSSProperty(CSS_WIDTH, new WCConstant($this->get_width()));
    $this->put_height_constraint(new HCConstraint(array($this->height, false), 
                                                  null, 
                                                  null));

    $this->reflow_content($context);
    
    /**
     * Apply vertical-align (behave like table cell)
     */
    $va = CSSVerticalAlign::value2pdf($this->getCSSProperty(CSS_VERTICAL_ALIGN));

    $va->apply_cell($this,$this->get_full_height(),0);
  }

  function show(&$driver) {    
    $this->offset(0, $driver->offset);
    $this->show_fixed($driver);
  }

  function _calc_sizes($full_width, $left, $center, $right) {
    $context = new FlowContext;

    $left_width   = $left->get_max_width($context);
    $center_width = $center->get_max_width($context);
    $right_width  = $right->get_max_width($context);
    
    $calculated_left_width   = 0;
    $calculated_center_width = 0;
    $calculated_right_width  = 0;

    if ($center_width > 0) {
      $calculated_center_width = $full_width * $center_width / ($center_width + 2*max($left_width, $right_width));
      $calculated_left_width   = ($full_width - $calculated_center_width) / 2;
      $calculated_right_width  = $calculated_left_width;
    } elseif ($left_width == 0 && $right_width == 0) {
      $calculated_center_width = 0;
      $calculated_left_width   = 0;
      $calculated_right_width  = 0;
    } elseif ($left_width == 0) {
      $calculated_center_width = 0;
      $calculated_left_width   = 0;
      $calculated_right_width  = $full_width;
    } elseif ($right_width == 0) {
      $calculated_center_width = 0;
      $calculated_left_width   = $full_width;
      $calculated_right_width  = 0;
    } else {
      $calculated_center_width = 0;
      $calculated_left_width   = $full_width * $left_width / ($left_width + $right_width);
      $calculated_right_width  = $full_width - $calculated_left_width;
    };

    return array($calculated_left_width, 
                 $calculated_center_width,
                 $calculated_right_width);
  }
}

class BoxPageMarginTop extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    $this->put_left($this->get_extra_left() + 0);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height()));    

    $this->put_full_width(mm2pt($media->width()));
    $this->put_full_height(mm2pt($media->margins['top']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginTopLeftCorner extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    $this->put_left($this->get_extra_left() + 0);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height()));    

    $this->put_full_width(mm2pt($media->margins['left']));
    $this->put_full_height(mm2pt($media->margins['top']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginTopLeft extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_width()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_TOP_LEFT],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_TOP_CENTER],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT]);

    $this->put_left($this->get_extra_left() + mm2pt($media->margins['left']));
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height()));

    $this->put_full_width($left);
    $this->put_full_height(mm2pt($media->margins['top']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginTopCenter extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_width()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_TOP_LEFT],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_TOP_CENTER],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT]);

    $this->put_left($this->get_extra_left() + mm2pt($media->margins['left']) + $left);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height()));

    $this->put_full_width($center);
    $this->put_full_height(mm2pt($media->margins['top']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginTopRight extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_width()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_TOP_LEFT],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_TOP_CENTER],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT]);

    $this->put_left($this->get_extra_left() + mm2pt($media->margins['left']) + $left + $center);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height()));

    $this->put_full_width($right);
    $this->put_full_height(mm2pt($media->margins['top']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginTopRightCorner extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    $this->put_left($this->get_extra_left() + mm2pt($media->width() - $media->margins['right']));
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height()));    

    $this->put_full_width(mm2pt($media->margins['right']));
    $this->put_full_height(mm2pt($media->margins['top']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginBottomLeftCorner extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    $this->put_left($this->get_extra_left() + 0);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->margins['bottom']));

    $this->put_full_width(mm2pt($media->margins['left']));
    $this->put_full_height(mm2pt($media->margins['bottom']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginBottomLeft extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_width()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_CENTER],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT]);

    $this->put_left($this->get_extra_left() + mm2pt($media->margins['left']));
    $this->put_top(-$this->get_extra_top() +mm2pt($media->margins['bottom']));

    $this->put_full_width($left);
    $this->put_full_height(mm2pt($media->margins['bottom']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginBottomCenter extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_width()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_CENTER],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT]);

    $this->put_left($this->get_extra_left() + mm2pt($media->margins['left']) + $left);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->margins['bottom']));

    $this->put_full_width($center);
    $this->put_full_height(mm2pt($media->margins['bottom']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginBottomRight extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_width()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_CENTER],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT]);

    $this->put_left($this->get_extra_left() + mm2pt($media->margins['left']) + $left + $center);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->margins['bottom']));

    $this->put_full_width($right);
    $this->put_full_height(mm2pt($media->margins['bottom']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginBottomRightCorner extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    $this->put_left($this->get_extra_left() + mm2pt($media->width() - $media->margins['right']));
    $this->put_top(-$this->get_extra_top() +mm2pt($media->margins['bottom']));    

    $this->put_full_width(mm2pt($media->margins['right']));
    $this->put_full_height(mm2pt($media->margins['top']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginBottom extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    $this->put_left($this->get_extra_left() + 0);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->margins['bottom']));    

    $this->put_full_width(mm2pt($media->width()));
    $this->put_full_height(mm2pt($media->margins['bottom']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginLeftTop extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_height()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_TOP],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_MIDDLE],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_BOTTOM]);

    $this->put_left($this->get_extra_left() + 0);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height() - $media->margins['top']));

    $this->put_full_height($left);
    $this->put_full_width(mm2pt($media->margins['left']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginLeftMiddle extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_height()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_TOP],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_MIDDLE],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_BOTTOM]);
    $this->put_left($this->get_extra_left() + 0);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height() - $media->margins['top']) - $left);

    $this->put_full_height($center);
    $this->put_full_width(mm2pt($media->margins['left']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginLeftBottom extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_height()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_TOP],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_MIDDLE],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_BOTTOM]);

    $this->put_left($this->get_extra_left() + 0);
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height() - $media->margins['top']) - $left - $center);

    $this->put_full_height($right);
    $this->put_full_width(mm2pt($media->margins['left']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginRightTop extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_height()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_RIGHT_TOP],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_RIGHT_MIDDLE],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_RIGHT_BOTTOM]);

    $this->put_left($this->get_extra_left() + mm2pt($media->width() - $media->margins['right']));
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height() - $media->margins['top']));

    $this->put_full_height($left);
    $this->put_full_width(mm2pt($media->margins['right']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginRightMiddle extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_height()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_TOP],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_MIDDLE],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_BOTTOM]);

    $this->put_left($this->get_extra_left() + mm2pt($media->width() - $media->margins['right']));
    $this->put_top(-$this->get_extra_top() +mm2pt($media->height() - $media->margins['top']) - $left);

    $this->put_full_height($center);
    $this->put_full_width(mm2pt($media->margins['right']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

class BoxPageMarginRightBottom extends BoxPageMargin {
  function _position($media, $boxes, $context) {
    list($left, $center, $right) = $this->_calc_sizes(mm2pt($media->real_height()),
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_TOP],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_MIDDLE],
                                                      $boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_BOTTOM]);

    $this->put_left($this->get_extra_left() + mm2pt($media->width() - $media->margins['right']));
    $this->put_top(-$this->get_extra_top() + mm2pt($media->height() - $media->margins['top']) - $left - $center);

    $this->put_full_height($right);
    $this->put_full_width(mm2pt($media->margins['right']));

    $this->_current_x = $this->get_left();
    $this->_current_y = $this->get_top();
  }
}

?>