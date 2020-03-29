<?php
// $Header: /cvsroot/html2ps/box.frame.php,v 1.24 2007/02/18 09:55:10 Konstantin Exp $

class FrameBox extends GenericContainerBox {
  function &create(&$root, &$pipeline) {
    $box =& new FrameBox($root, $pipeline);
    $box->readCSS($pipeline->getCurrentCSSState());
    return $box;
  }

  function reflow(&$parent, &$context) {
    // If frame contains no boxes (for example, the src link is broken)
    // we just return - no further processing will be done
    if (count($this->content) == 0) { return; };

    // First box contained in a frame should always fill all its height
    $this->content[0]->put_full_height($this->get_height());

    $hc = new HCConstraint(array($this->get_height(), false),
                           array($this->get_height(), false), 
                           array($this->get_height(), false));
    $this->content[0]->put_height_constraint($hc);

    $context->push_collapsed_margin(0);
    $context->push_container_uid($this->uid);

    $this->reflow_content($context);

    $context->pop_collapsed_margin();
    $context->pop_container_uid();
  }

  /**
   * Reflow absolutely positioned block box. Note that according to CSS 2.1 
   * the only types of boxes which could be absolutely positioned are 
   * 'block' and 'table'
   * 
   * @param FlowContext $context A flow context object containing the additional layout data.
   *
   * @link http://www.w3.org/TR/CSS21/visuren.html#dis-pos-flo CSS 2.1: Relationships between 'display', 'position', and 'float'
   */
  function reflow_absolute(&$context) {
    GenericFormattedBox::reflow($this->parent, $context);

    $position_strategy =& new StrategyPositionAbsolute();
    $position_strategy->apply($this);
    
    /**
     * As sometimes left/right values may not be set, we need to use the "fit" width here.
     * If box have a width constraint, 'get_max_width' will return constrained value; 
     * othersise, an intrictic width will be returned. 
     * 
     * Note that get_max_width returns width _including_ external space line margins, borders and padding;
     * as we're setting the "internal" - content width, we must subtract "extra" space width from the 
     * value received
     *
     * @see GenericContainerBox::get_max_width()
     */

    $this->put_width($this->get_max_width($context) - $this->_get_hor_extra());
    
    /**
     * Update the width, as it should be calculated based upon containing block width, not real parent.
     * After this we should remove width constraints or we may encounter problem 
     * in future when we'll try to call get_..._width functions for this box
     *
     * @todo Update the family of get_..._width function so that they would apply constraint
     * using the containing block width, not "real" parent width
     */
    $wc = $this->getCSSProperty(CSS_WIDTH);

    $containing_block =& $this->_get_containing_block();
    $this->put_width($wc->apply($this->get_width(), 
                                $containing_block['right'] - $containing_block['left']));
    $this->setCSSProperty(CSS_WIDTH, new WCNone());

    /**
     * Layout element's children 
     */
    $this->reflow_content($context);

    /**
     * As absolute-positioned box generated new flow contexy, extend the height to fit all floats
     */
    $this->fitFloats($context);

    /** 
     * If element have been positioned using 'right' or 'bottom' property,
     * we need to offset it, as we assumed it had zero width and height at
     * the moment we placed it
     */
    $right = $this->getCSSProperty(CSS_RIGHT);
    $left  = $this->getCSSProperty(CSS_LEFT);
    if ($left->isAuto() && !$right->isAuto()) {
      $this->offset(-$this->get_width(), 0);
    };

    $bottom = $this->getCSSProperty(CSS_BOTTOM);
    $top    = $this->getCSSProperty(CSS_TOP);
    if ($top->isAuto() && !$bottom->isAuto()) {
      $this->offset(0, $this->get_height());
    };
  }

  function FrameBox(&$root, &$pipeline) {
    $css_state =& $pipeline->getCurrentCSSState();

    // Inherit 'border' CSS value from parent (FRAMESET tag), if current FRAME 
    // has no FRAMEBORDER attribute, and FRAMESET has one
    $parent = $root->parent();
    if (!$root->has_attribute('frameborder') &&
        $parent->has_attribute('frameborder')) {
      $parent_border = $css_state->getPropertyOnLevel(CSS_BORDER, CSS_PROPERTY_LEVEL_PARENT);
      $css_state->setProperty(CSS_BORDER, $parent_border->copy());
    }

    $this->GenericContainerBox($root);

    // If NO src attribute specified, just return.
    if (!$root->has_attribute('src')) { return; };

    // Determine the fullly qualified URL of the frame content
    $src  = $root->get_attribute('src');
    $url  = $pipeline->guess_url($src);
    $data = $pipeline->fetch($url);

    /**
     * If framed page could not be fetched return immediately
     */
    if (is_null($data)) { return; };

    /**
     * Render only iframes containing HTML only
     *
     * Note that content-type header may contain additional information after the ';' sign
     */
    $content_type = $data->get_additional_data('Content-Type');
    $content_type_array = explode(';', $content_type);
    if ($content_type_array[0] != "text/html") { return; };

    $html = $data->get_content();
      
    // Remove control symbols if any
    $html = preg_replace('/[\x00-\x07]/', "", $html);
    $converter = Converter::create();
    $html = $converter->to_utf8($html, $data->detect_encoding());
    $html = html2xhtml($html);
    $tree = TreeBuilder::build($html);
      
    // Save current stylesheet, as each frame may load its own stylesheets
    //
    $pipeline->pushCSS();
    $css =& $pipeline->getCurrentCSS();
    $css->scan_styles($tree, $pipeline);
    
    $frame_root = traverse_dom_tree_pdf($tree);   
    $box_child  =& create_pdf_box($frame_root, $pipeline);
    $this->add_child($box_child);
    
    // Restore old stylesheet
    //
    $pipeline->popCSS();

    $pipeline->pop_base_url();
  }

  /**
   * Note that if both top and bottom are 'auto', box will use vertical coordinate 
   * calculated using guess_corder in 'reflow' method which could be used if this
   * box had 'position: static'
   */
  function _positionAbsoluteVertically($containing_block) {
    $bottom = $this->getCSSProperty(CSS_BOTTOM);
    $top    = $this->getCSSProperty(CSS_TOP);

    if (!$top->isAuto()) {
      if ($top->isPercentage()) {
        $top_value = ($containing_block['top'] - $containing_block['bottom']) / 100 * $top->getPercentage();
      } else {
        $top_value = $top->getPoints();
      };
      $this->put_top($containing_block['top'] - $top_value - $this->get_extra_top());
    } elseif (!$bottom->isAuto()) { 
      if ($bottom->isPercentage()) {
        $bottom_value = ($containing_block['top'] - $containing_block['bottom']) / 100 * $bottom->getPercentage();
      } else {
        $bottom_value = $bottom->getPoints();
      };
      $this->put_top($containing_block['bottom'] + $bottom_value + $this->get_extra_bottom());
    };
  }

  /**
   * Note that  if both  'left' and 'right'  are 'auto', box  will use
   * horizontal coordinate  calculated using guess_corder  in 'reflow'
   * method which could be used if this box had 'position: static'
   */
  function _positionAbsoluteHorizontally($containing_block) {
    $left  = $this->getCSSProperty(CSS_LEFT);
    $right = $this->getCSSProperty(CSS_RIGHT);

    if (!$left->isAuto()) { 
      if ($left->isPercentage()) {
        $left_value = ($containing_block['right'] - $containing_block['left']) / 100 * $left->getPercentage();
      } else {
        $left_value = $left->getPoints();
      };
      $this->put_left($containing_block['left'] + $left_value + $this->get_extra_left());
    } elseif (!$right->isAuto()) {
      if ($right->isPercentage()) {
        $right_value = ($containing_block['right'] - $containing_block['left']) / 100 * $right->getPercentage();
      } else {
        $right_value = $right->getPoints();
      };
      $this->put_left($containing_block['right'] - $right_value - $this->get_extra_right());
    };
  }
}

class FramesetBox extends GenericContainerBox {
  var $rows;
  var $cols;

  function &create(&$root, &$pipeline) {
    $box =& new FramesetBox($root, $pipeline);
    $box->readCSS($pipeline->getCurrentCSSState());
    return $box;
  }

  function FramesetBox(&$root, $pipeline) {
    $this->GenericContainerBox($root);
    $this->create_content($root, $pipeline);
    
    // Now determine the frame layout inside the frameset
    $this->rows = $root->has_attribute('rows') ? $root->get_attribute('rows') : "100%";
    $this->cols = $root->has_attribute('cols') ? $root->get_attribute('cols') : "100%";
  }

  function reflow(&$parent, &$context) {
    $viewport =& $context->get_viewport();

    // Frameset always fill all available space in viewport
    $this->put_left($viewport->get_left() + $this->get_extra_left());
    $this->put_top($viewport->get_top() - $this->get_extra_top());

    $this->put_full_width($viewport->get_width());
    $this->setCSSProperty(CSS_WIDTH, new WCConstant($viewport->get_width()));

    $this->put_full_height($viewport->get_height());
    $this->put_height_constraint(new WCConstant($viewport->get_height()));    
    
    // Parse layout-control values
    $rows = guess_lengths($this->rows, $this->get_height());
    $cols = guess_lengths($this->cols, $this->get_width());
    
    // Now reflow all frames in frameset
    $cur_col = 0;
    $cur_row = 0;
    for ($i=0; $i < count($this->content); $i++) {
      // Had we run out of cols/rows?
      if ($cur_row >= count($rows)) {
        // In valid HTML we never should get here, but someone can provide less frame cells 
        // than frames. Extra frames will not be rendered at all
        return;
      }

      $frame =& $this->content[$i];

      /**
       * Depending on the source HTML, FramesetBox may contain some non-frame boxes; 
       * we'll just ignore them
       */
      if (!is_a($frame, "FramesetBox") &&
          !is_a($frame, "FrameBox")) {
        continue;
      };

      // Guess frame size and position
      $frame->put_left($this->get_left() + array_sum(array_slice($cols, 0, $cur_col)) + $frame->get_extra_left());
      $frame->put_top($this->get_top()   - array_sum(array_slice($rows, 0, $cur_row)) - $frame->get_extra_top());

      $frame->put_full_width($cols[$cur_col]);
      $frame->setCSSProperty(CSS_WIDTH, new WCConstant($frame->get_width()));

      $frame->put_full_height($rows[$cur_row]);
      $frame->put_height_constraint(new WCConstant($frame->get_height()));

      // Reflow frame contents
      $context->push_viewport(FlowViewport::create($frame));
      $frame->reflow($this, $context);
      $context->pop_viewport();

      // Move to the next frame position
      // Next columns
      $cur_col ++;
      if ($cur_col >= count($cols)) {
        // Next row
        $cur_col = 0;
        $cur_row ++;
      }
    }
  }
}
?>