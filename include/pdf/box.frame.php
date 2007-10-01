<?php
// $Header: /cvsroot/html2ps/box.frame.php,v 1.18 2006/05/27 15:33:26 Konstantin Exp $

class FrameBox extends GenericContainerBox {
  function &create(&$root, &$pipeline) {
    $box =& new FrameBox($root, $pipeline);
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

  function FrameBox(&$root, &$pipeline) {
    // Inherit 'border' CSS value from parent (FRAMESET tag), if current FRAME 
    // has no FRAMEBORDER attribute, and FRAMESET has one
    $parent = $root->parent();
    if (!$root->has_attribute('frameborder') &&
        $parent->has_attribute('frameborder')) {
      pop_border();
      push_border(get_border());
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
    global $g_css;
    $old_css = $g_css;
    global $g_css_obj;
    $old_obj = $g_css_obj;
    
    scan_styles($tree, $pipeline);
    // Temporary hack: convert CSS rule array to CSS object
    $g_css_obj = new CSSObject;
    foreach ($g_css as $rule) {
      $g_css_obj->add_rule($rule, $pipeline);
    }
    
    // TODO: stinks. Rewrite
    //
    $frame_root = traverse_dom_tree_pdf($tree);
    
    $box_child =& create_pdf_box($frame_root, $pipeline);
    $this->add_child($box_child);
    
    // Restore old stylesheet
    //
    $g_css = $old_css;
    $g_css_obj = $old_obj;

    $pipeline->pop_base_url();
  }
}

class FramesetBox extends GenericContainerBox {
  var $rows;
  var $cols;

  function &create(&$root, &$pipeline) {
    $box =& new FramesetBox($root, $pipeline);
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
    $this->put_width_constraint(new WCConstant($viewport->get_width()));

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
      $frame->put_width_constraint(new WCConstant($frame->get_width()));

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