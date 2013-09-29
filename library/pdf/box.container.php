<?php
// $Header: /cvsroot/html2ps/box.container.php,v 1.68 2007/05/06 18:49:29 Konstantin Exp $

require_once(HTML2PS_DIR.'strategy.width.min.php');
require_once(HTML2PS_DIR.'strategy.width.min.nowrap.php');
require_once(HTML2PS_DIR.'strategy.width.max.php');
require_once(HTML2PS_DIR.'strategy.width.max.natural.php');

/**
 * @package HTML2PS
 * @subpackage Document
 *
 * This file contains the abstract class describing the behavior of document element
 * containing some other document elements.
 */

/**
 * @package HTML2PS
 * @subpackage Document
 * 
 * The GenericContainerBox class is a common superclass for all document elements able 
 * to contain other elements. This class does provide the line-box handling utilies and
 * some minor float related-functions.
 * 
 */
class GenericContainerBox extends GenericFormattedBox {
  /**
   * @var Array A list of contained elements (of type GenericFormattedBox)
   * @access public
   */
  var $content;

  var $_first_line;

  /**
   * @var Array A list of child nodes in the current line box; changes dynamically 
   * during the reflow process.
   * @access private
   */
  var $_line;

  /**
   * Sometimes floats may appear inside the line box, consider the following code,
   * for example: "<div>text<div style='float:left'>float</div>word</div>". In
   * this case, the floating DIV should be rendered below the "text word" line;
   * thus, we need to keep a list of deferred floating elements and render them
   * when current line box closes.
   *
   * @var Array A list of floats which should be flown after current line box ends;
   * @access private
   */
  var $_deferred_floats;

  /**
   * @var float Current output X value inside the current element
   * @access public
   */
  var $_current_x;

  /**
   * @var float Current output Y value inside the current element
   * @access public
   */
  var $_current_y;

  function destroy() {
    for ($i=0, $size = count($this->content); $i < $size; $i++) {
      $this->content[$i]->destroy();
    };
    unset($this->content);

    parent::destroy();
  }

  /** 
   * Render current container box using the specified output method.
   *
   * @param OutputDriver $driver The output driver object
   * 
   * @return Boolean flag indicating the success or 'null' value in case of critical rendering 
   * error
   */
  function show(&$driver) {
    GenericFormattedBox::show($driver);

    $overflow = $this->getCSSProperty(CSS_OVERFLOW);

    /**
     * Sometimes the content may overflow container boxes. This situation arise, for example,
     * for relative-positioned child boxes, boxes having constrained height and in some
     * other cases. If the container box does not have CSS 'overflow' property 
     * set to 'visible' value, the content should be visually clipped using container box
     * padding area.
     */
    if ($overflow !== OVERFLOW_VISIBLE) {
      $driver->save();
      $this->_setupClip($driver);
    };    

    /**
     * Render child elements
     */
    for ($i=0, $size = count($this->content); $i < $size; $i++) {    
      $child =& $this->content[$i];

      /**
       * We'll check the visibility property here
       * Reason: all boxes (except the top-level one) are contained in some other box, 
       * so every box will pass this check. The alternative is to add this check into every
       * box class show member.
       *
       * The only exception of absolute positioned block boxes which are drawn separately;
       * their show method is called explicitly; the similar check should be performed there
       */
      if ($child->isVisibleInFlow()) {
        /**
         * To reduce the drawing overhead, we'll check if some part if current child element
         * belongs to current output page. If not, there will be no reason to draw this 
         * child this time.
         * 
         * @see OutputDriver::contains()
         *
         * @todo In rare cases the element content may be placed outside the element itself;
         * in such situantion content may be visible on the page, while element is not.
         * This situation should be resolved somehow.
         */
        if ($driver->contains($child)) {
          if (is_null($child->show($driver))) {
            return null;
          };
        };
      };
    }

    /** 
     * Restore previous clipping mode, if it have been modified for non-'overflow: visible' 
     * box.
     */
    if ($overflow !== OVERFLOW_VISIBLE) {
      $driver->restore();
    };

    return true;
  }

  /** 
   * Render current fixed-positioned container box using the specified output method. Unlike
   * the 'show' method, there's no check if current page viewport contains current element, as
   * fixed-positioned may be drawn on the page margins, outside the viewport.
   *
   * @param OutputDriver $driver The output driver object
   * 
   * @return Boolean flag indicating the success or 'null' value in case of critical rendering 
   * error
   *
   * @see GenericContainerBox::show()
   * 
   * @todo the 'show' and 'show_fixed' method code are almost the same except the child element 
   * method called in the inner loop; also, no check is done if current viewport contains this element,
   * thus sllowinf printing data on page margins, where no data should be printed normally
   * I suppose some more generic method containing the common code should be made.
   */
  function show_fixed(&$driver) {
    GenericFormattedBox::show($driver);

    $overflow = $this->getCSSProperty(CSS_OVERFLOW);

    /**
     * Sometimes the content may overflow container boxes. This situation arise, for example,
     * for relative-positioned child boxes, boxes having constrained height and in some
     * other cases. If the container box does not have CSS 'overflow' property 
     * set to 'visible' value, the content should be visually clipped using container box
     * padding area.
     */
    if ($overflow !== OVERFLOW_VISIBLE) {
      // Save graphics state (of course, BEFORE the clipping area will be set)
      $driver->save();
      $this->_setupClip($driver);
    };    

    /**
     * Render child elements
     */
    $size = count($this->content);
    for ($i=0; $i < $size; $i++) {    
      /**
       * We'll check the visibility property here
       * Reason: all boxes (except the top-level one) are contained in some other box, 
       * so every box will pass this check. The alternative is to add this check into every
       * box class show member.
       *
       * The only exception of absolute positioned block boxes which are drawn separately;
       * their show method is called explicitly; the similar check should be performed there
       */
      $child =& $this->content[$i];
      if ($child->getCSSProperty(CSS_VISIBILITY) === VISIBILITY_VISIBLE) {
        // Fixed-positioned blocks are displayed separately;
        // If we call them now, they will be drawn twice
        if ($child->getCSSProperty(CSS_POSITION) != POSITION_FIXED) {
          if (is_null($child->show_fixed($driver))) {
            return null;
          };
        };
      };
    }

    /** 
     * Restore previous clipping mode, if it have been modified for non-'overflow: visible' 
     * box.
     */
    if ($overflow !== OVERFLOW_VISIBLE) {
      $driver->restore();
    };

    return true;
  }

  function _find(&$box) {
    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      if ($this->content[$i]->uid == $box->uid) { 
        return $i; 
      };
    }
    return null;
  }

  // Inserts new child box at the specified (zero-based) offset; 0 stands for first child
  // 
  // @param $index index to insert child at
  // @param $box child to be inserted
  //
  function insert_child($index, &$box) {
    $box->parent =& $this;

    // Offset the content array
    for ($i = count($this->content)-1; $i>= $index; $i--) {
      $this->content[$i+1] =& $this->content[$i];
    };

    $this->content[$index] =& $box;
  }

  function insert_before(&$what, &$where) {
    if ($where) {
      $index = $this->_find($where);

      if (is_null($index)) { 
        return null; 
      };

      $this->insert_child($index, $what);
    } else {
      // If 'where' is not specified, 'what' should become the last child
      $this->add_child($what);
    };
    
    return $what;
  }

  function add_child(&$box) {
    $this->append_child($box);
  }

  function append_child(&$box) {
    // In general, this function is called like following:
    // $box->add_child(create_pdf_box(...))
    // As create_pdf_box _may_ return null value (for example, for an empty text node),
    // we should process the case of $box == null here
    if ($box) {
      $box->parent =& $this;
      $this->content[] =& $box;
    };
  }

  // Get first child of current box which actually will be drawn 
  // on the page. So, whitespace and null boxes will be ignored
  // 
  // See description of is_null for null box definition.
  // (not only NullBox is treated as null box)
  //
  // @return reference to the first visible child of current box 
  function &get_first() {
    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      if (!is_whitespace($this->content[$i]) && 
          !$this->content[$i]->is_null()) {
        return $this->content[$i];
      };
    };

    // We use this construct to avoid notice messages in PHP 4.4 and PHP 5
    $dummy = null;
    return $dummy;
  }

  // Get first text or image child of current box which actually will be drawn 
  // on the page. 
  // 
  // See description of is_null for null box definition.
  // (not only NullBox is treated as null box)
  //
  // @return reference to the first visible child of current box 
  function &get_first_data() {
    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      if (!is_whitespace($this->content[$i]) && !$this->content[$i]->is_null()) {
        if (is_container($this->content[$i])) {
          $data =& $this->content[$i]->get_first_data();
          if (!is_null($data)) { return $data; };
        } else {
          return $this->content[$i];
        };
      };
    };

    // We use this construct to avoid notice messages in PHP 4.4 and PHP 5
    $dummy = null;
    return $dummy;
  }

  // Get last child of current box which actually will be drawn 
  // on the page. So, whitespace and null boxes will be ignored
  // 
  // See description of is_null for null box definition.
  // (not only NullBox is treated as null box)
  //
  // @return reference to the last visible child of current box 
  function &get_last() {
    for ($i=count($this->content)-1; $i>=0; $i--) {
      if (!is_whitespace($this->content[$i]) && !$this->content[$i]->is_null()) {
        return $this->content[$i];
      };
    };

    // We use this construct to avoid notice messages in PHP 4.4 and PHP 5
    $dummy = null;
    return $dummy;
  }

  function offset_if_first(&$box, $dx, $dy) {
    if ($this->is_first($box)) {
      // The top-level box (page box) should never be offset 
      if ($this->parent) {
        if (!$this->parent->offset_if_first($box, $dx, $dy)) {
          $this->offset($dx, $dy);
          return true;
        };
      };
    };
    return false;
  }
    
  function offset($dx, $dy) {
    parent::offset($dx, $dy);

    $this->_current_x += $dx;
    $this->_current_y += $dy;

    // Offset contents
    $size = count($this->content);
    for ($i=0; $i < $size; $i++) {
      $this->content[$i]->offset($dx, $dy);
    }
  }

  function GenericContainerBox() {
    $this->GenericFormattedBox();

    // By default, box does not have any content
    $this->content = array();

    // Initialize line box
    $this->_line = array();

    // Initialize floats-related stuff
    $this->_deferred_floats = array();

    $this->_additional_text_indent = 0;

    // Current-point
    $this->_current_x = 0;
    $this->_current_y = 0;

    // Initialize floating children array
    $this->_floats = array();
  }

  function add_deferred_float(&$float) {
    $this->_deferred_floats[] =& $float;
  }

  /**
   * Create the child nodes of current container object using the parsed HTML data
   *
   * @param mixed $root node corresponding to the current container object
   */
  function create_content(&$root, &$pipeline) {
    // Initialize content
    $child = $root->first_child();
    while ($child) {
      $box_child =& create_pdf_box($child, $pipeline);
      $this->add_child($box_child);
      $child = $child->next_sibling();
    };
  }

  // Content-handling functions

  function is_container() {
    return true;
  }

  function get_content() {
    return join('', array_map(array($this, 'get_content_callback'), $this->content));
  }

  function get_content_callback(&$node) {
    return $node->get_content();
  }

  // Get total height of this box content (including floats, if any)
  // Note that floats can be contained inside children, so we'll need to use
  // this function recusively 
  function get_real_full_height() {
    $content_size = count($this->content);

    $overflow = $this->getCSSProperty(CSS_OVERFLOW);

    // Treat items with overflow: hidden specifically, 
    // as floats flown out of this boxes will not be visible
    if ($overflow == OVERFLOW_HIDDEN) {
      return $this->get_full_height();
    };

    // Check if this object is totally empty
    $first = $this->get_first();
    if (is_null($first)) { 
      return 0; 
    };

    // Initialize the vertical extent taken by content using the 
    // very first child
    $max_top    = $first->get_top_margin();
    $min_bottom = $first->get_bottom_margin();

    for ($i=0; $i<$content_size; $i++) {     
      if (!$this->content[$i]->is_null()) {
        // Check if top margin of current child is to the up 
        // of vertical extent top margin
        $max_top    = max($max_top, $this->content[$i]->get_top_margin());

        /**
         * Check if current child bottom margin will extend 
         * the vertical space OR if it contains floats extending 
         * this, unless this child have overflow: hidden, because this 
         * will prevent additional content to be visible
         */
        if (!$this->content[$i]->is_container()) {
          $min_bottom = min($min_bottom,
                            $this->content[$i]->get_bottom_margin());
        } else {
          $content_overflow = $this->content[$i]->getCSSProperty(CSS_OVERFLOW);

          if ($content_overflow == OVERFLOW_HIDDEN) {
            $min_bottom = min($min_bottom,
                              $this->content[$i]->get_bottom_margin());
          } else {
            $min_bottom = min($min_bottom,
                              $this->content[$i]->get_bottom_margin(),
                              $this->content[$i]->get_top_margin() - 
                              $this->content[$i]->get_real_full_height());
          };
        };
      };
    }

    return max(0, $max_top - $min_bottom) + $this->_get_vert_extra();
  }

  // LINE-LENGTH RELATED FUNCTIONS

  function _line_length() {
    $sum = 0;
    $size = count($this->_line);

    for ($i=0; $i < $size; $i++) {
      // Note that the line length should include the inline boxes margin/padding
      // as inline boxes are not directly included to the parent line box,
      // we'll need to check the parent of current line box element, 
      // and, if it is an inline box, AND this element is last or first contained element
      // add correcponsing padding value
      $element =& $this->_line[$i];

      if (isset($element->wrapped) && !is_null($element->wrapped)) {
        if ($i==0) {
          $sum += $element->get_full_width() - $element->getWrappedWidth();
        } else {
          $sum += $element->getWrappedWidthAndHyphen();
        };
      } else {
        $sum += $element->get_full_width();
      };

      if ($element->parent) {
        $first = $element->parent->get_first();
        $last  = $element->parent->get_last();

        if (!is_null($first) && $first->uid === $element->uid) { 
          $sum += $element->parent->get_extra_line_left(); 
        }

        if (!is_null($last) && $last->uid === $element->uid) { 
          $sum += $element->parent->get_extra_line_right(); 
        }
      };
    }

    if ($this->_first_line) {
      $ti = $this->getCSSProperty(CSS_TEXT_INDENT);
      $sum += $ti->calculate($this);
      $sum += $this->_additional_text_indent;
    };

    return $sum;
  }

  function _line_length_delta(&$context) {
    return max($this->get_available_width($context) - $this->_line_length(),0);
  }

  /**
   * Get the last box in current line box
   */
  function &last_in_line() {
    $size = count($this->_line);
    if ($size < 1) {
      $dummy = null;
      return $dummy;
    };

    return $this->_line[$size-1];
  }
  
  // WIDTH

  function get_min_width_natural(&$context) {
    $content_size = count($this->content);

    /**
     * If box does not have any context, its minimal width is determined by extra horizontal space:
     * padding, border width and margins
     */
    if ($content_size == 0) { 
      $min_width = $this->_get_hor_extra();
      return $min_width;
    };

    /**
     * If we're in 'nowrap' mode, minimal and maximal width will be equal
     */
    $white_space = $this->getCSSProperty(CSS_WHITE_SPACE);
    $pseudo_nowrap = $this->getCSSProperty(CSS_HTML2PS_NOWRAP);
    if ($white_space   == WHITESPACE_NOWRAP || 
        $pseudo_nowrap == NOWRAP_NOWRAP) { 
      $min_width = $this->get_min_nowrap_width($context);
      return $min_width; 
    }

    /**
     * We need to add text indent size to the width of the first item
     */
    $start_index = 0;
    while ($start_index < $content_size && 
           $this->content[$start_index]->out_of_flow()) { 
      $start_index++; 
    };

    if ($start_index < $content_size) {
      $ti = $this->getCSSProperty(CSS_TEXT_INDENT);
      $minw = 
        $ti->calculate($this) + 
        $this->content[$start_index]->get_min_width_natural($context);
    } else {
      $minw = 0;
    };

    for ($i=$start_index; $i<$content_size; $i++) {
      $item =& $this->content[$i];
      if (!$item->out_of_flow()) {
        $minw = max($minw, $item->get_min_width($context));
      };
    }

    /**
     * Apply width constraint to min width. Return maximal value
     */
    $wc = $this->getCSSProperty(CSS_WIDTH);
    $containing_block =& $this->_get_containing_block();

    $min_width = $minw;
    return $min_width;
  }

  function get_min_width(&$context) {
    $strategy = new StrategyWidthMin();
    return $strategy->apply($this, $context);    
  }

  function get_min_nowrap_width(&$context) {
    $strategy = new StrategyWidthMinNowrap();
    return $strategy->apply($this, $context);
  }

  // Note: <table width="100%" inside some block box cause this box to expand
  // $limit - maximal width which should not be exceeded; by default, there's no limit at all
  // 
  function get_max_width_natural(&$context, $limit=10E6) {
    $strategy = new StrategyWidthMaxNatural($limit);
    return $strategy->apply($this, $context);
  }

  function get_max_width(&$context, $limit=10E6) {
    $strategy = new StrategyWidthMax($limit);
    return $strategy->apply($this, $context);
  }

  function close_line(&$context, $lastline = false) {
    // Align line-box using 'text-align' property
    $size = count($this->_line);

    if ($size > 0) {
      $last_item =& $this->_line[$size-1];
      if (is_whitespace($last_item)) {
        $last_item->width = 0;
        $last_item->height = 0;
      };
    };

    // Note that text-align should not be applied to the block boxes!
    // As block boxes will be alone in the line-box, we can check
    // if the very first box in the line is inline; if not - no justification should be made
    //
    if ($size > 0) {
      if (is_inline($this->_line[0])) {
        $cb = CSSTextAlign::value2pdf($this->getCSSProperty(CSS_TEXT_ALIGN));
        $cb($this, $context, $lastline);
      } else {
        // Nevertheless, CENTER tag and P/DIV with ALIGN attribute set should affect the 
        // position of non-inline children.
        $cb = CSSPseudoAlign::value2pdf($this->getCSSProperty(CSS_HTML2PS_ALIGN));
        $cb($this, $context, $lastline);
      };
    };

    // Apply vertical align to all of the line content
    // first, we need to aling all baseline-aligned boxes to determine the basic line-box height, top and bottom edges
    // then, SUP and SUP positioned boxes (as they can extend the top and bottom edges, but not affected themselves)
    // then, MIDDLE, BOTTOM and TOP positioned boxes in the given order
    //
    $baselined = array();
    $baseline = 0;
    $height = 0;
    for ($i=0; $i < $size; $i++) {
      $vertical_align = $this->_line[$i]->getCSSProperty(CSS_VERTICAL_ALIGN);

      if ($vertical_align == VA_BASELINE) {
        // Add current baseline-aligned item to the baseline
        //
        $baselined[] =& $this->_line[$i];

        $baseline = max($baseline, 
                        $this->_line[$i]->default_baseline);
      };
    };

    $size_baselined = count($baselined);
    for ($i=0; $i < $size_baselined; $i++) {
      $baselined[$i]->baseline = $baseline;

      $height = max($height, 
                    $baselined[$i]->get_full_height() + $baselined[$i]->getBaselineOffset(),
                    $baselined[$i]->get_ascender() + $baselined[$i]->get_descender());

    };

    // SUB vertical align
    //
    for ($i=0; $i < $size; $i++) {
      $vertical_align = $this->_line[$i]->getCSSProperty(CSS_VERTICAL_ALIGN);
      if ($vertical_align == VA_SUB) {
        $this->_line[$i]->baseline = 
          $baseline + $this->_line[$i]->get_full_height()/2;
      };
    }

    // SUPER vertical align
    //
    for ($i=0; $i < $size; $i++) {
      $vertical_align = $this->_line[$i]->getCSSProperty(CSS_VERTICAL_ALIGN);
      if ($vertical_align == VA_SUPER) {
        $this->_line[$i]->baseline = $this->_line[$i]->get_full_height()/2;
      };
    }

    // MIDDLE vertical align
    //
    $middle = 0;
    for ($i=0; $i < $size; $i++) {
      $vertical_align = $this->_line[$i]->getCSSProperty(CSS_VERTICAL_ALIGN);
      if ($vertical_align == VA_MIDDLE) {
        $middle = max($middle, $this->_line[$i]->get_full_height() / 2);
      };
    };

    if ($middle * 2 > $height) {
      // Offset already aligned items
      //
      for ($i=0; $i < $size; $i++) {
        $this->_line[$i]->baseline += ($middle - $height/2);
      };      
      $height = $middle * 2;
    };
 
    for ($i=0; $i < $size; $i++) {
      $vertical_align = $this->_line[$i]->getCSSProperty(CSS_VERTICAL_ALIGN);
      if ($vertical_align == VA_MIDDLE) {
        $this->_line[$i]->baseline = $this->_line[$i]->default_baseline + ($height/2 - $this->_line[$i]->get_full_height()/2);
      };
    }

    // BOTTOM vertical align
    //
    $bottom = 0;
    for ($i=0; $i < $size; $i++) {
      $vertical_align = $this->_line[$i]->getCSSProperty(CSS_VERTICAL_ALIGN);
      if ($vertical_align == VA_BOTTOM) {
        $bottom = max($bottom, $this->_line[$i]->get_full_height());
      };
    };

    if ($bottom > $height) {
      // Offset already aligned items
      //
      for ($i=0; $i < $size; $i++) {
        $this->_line[$i]->baseline += ($bottom - $height);
      };      
      $height = $bottom;
    };

    for ($i=0; $i < $size; $i++) {
      $vertical_align = $this->_line[$i]->getCSSProperty(CSS_VERTICAL_ALIGN);
      if ($vertical_align == VA_BOTTOM) {
        $this->_line[$i]->baseline = $this->_line[$i]->default_baseline + $height - $this->_line[$i]->get_full_height();
      };
    }

    // TOP vertical align
    //
    $bottom = 0;
    for ($i=0; $i < $size; $i++) {
      $vertical_align = $this->_line[$i]->getCSSProperty(CSS_VERTICAL_ALIGN);
      if ($vertical_align == VA_TOP) {
        $bottom = max($bottom, $this->_line[$i]->get_full_height());
      };
    };

    if ($bottom > $height) {
      $height = $bottom;
    };

    for ($i=0; $i < $size; $i++) {
      $vertical_align = $this->_line[$i]->getCSSProperty(CSS_VERTICAL_ALIGN);
      if ($vertical_align == VA_TOP) {
        $this->_line[$i]->baseline = $this->_line[$i]->default_baseline;
      };
    }

    // Calculate the bottom Y coordinate of last line box
    //
    $line_bottom = $this->_current_y;
    foreach ($this->_line AS $line_element) {
      // This line is required; say, we have sequence of text and image inside the container,
      // AND image have greater baseline than text; in out case, text will be offset to the bottom 
      // of the page and we lose the gap between text and container bottom edge, unless we'll re-extend
      // containier height

      // Note that we're using the colapsed margin value to get the Y coordinate to extend height to,
      // as bottom margin may be collapsed with parent

      $effective_bottom = 
        $line_element->get_top() - 
        $line_element->get_height() - 
        $line_element->get_extra_bottom();

      $this->extend_height($effective_bottom);
      $line_bottom = min($effective_bottom, $line_bottom);
    }

    $this->extend_height($line_bottom);

    // Clear the line box
    $this->_line = array();

    // Reset current X coordinate to the far left
    $this->_current_x = $this->get_left();
    
    // Extend Y coordinate
    $this->_current_y = $line_bottom;

    // Render the deferred floats
    for ($i = 0, $size = count($this->_deferred_floats); $i < $size; $i++) {
      $this->_deferred_floats[$i]->reflow_static_float($this, $context);
    };
    // Clear deferred float list
    $this->_deferred_floats = array();

    // modify the current-x value, so that next inline box will not intersect any floating boxes
    $this->_current_x = $context->float_left_x($this->_current_x, $this->_current_y);

    $this->_first_line = false;
  }

  function append_line(&$item) {
    $this->_line[] =& $item;
  }

  // Line box should be treated as empty in following cases: 
  // 1. It is really empty (so, it contains 0 boxes)
  // 2. It contains only whitespace boxes
  function line_box_empty() {
    $size = count($this->_line);
    if ($size == 0) { return true; }

    // Scan line box
    for ($i=0; $i<$size; $i++) {
      if (!is_whitespace($this->_line[$i]) && 
          !$this->_line[$i]->is_null()) { return false; };
    }

    // No non-whitespace boxes were found
    return true;
  }

  function reflow_anchors(&$viewport, &$anchors, $page_heights) {
    GenericFormattedBox::reflow_anchors($viewport, $anchors, $page_heights);

    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      $this->content[$i]->reflow_anchors($viewport, $anchors, $page_heights);
    }
  }

  function fitFloats(&$context) {
    $float_bottom = $context->float_bottom();     
    if (!is_null($float_bottom)) { 
      $this->extend_height($float_bottom); 
    };
    
    $float_right = $context->float_right();
    if (!is_null($float_right)) { 
      $this->extend_width($float_right); 
    };
  }

  function reflow_content(&$context) {
    $text_indent = $this->getCSSProperty(CSS_TEXT_INDENT);

    $this->close_line($context);

    $this->_first_line = true;

    // If first child is inline - apply text-indent
    $first = $this->get_first();
    if (!is_null($first)) {
      if (is_inline($first)) {
        $this->_current_x += $text_indent->calculate($this);
        $this->_current_x += $this->_additional_text_indent;
      };
    };

    $this->height = 0;
    // Reset current Y value
    $this->_current_y = $this->get_top();

    $size = count($this->content);
    for ($i=0; $i < $size; $i++) {
      $child =& $this->content[$i];
      $child->reflow($this, $context);
    };

    $this->close_line($context, true);
  }

  function reflow_inline() {
    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      $this->content[$i]->reflow_inline();
    };
  }

  function reflow_text(&$viewport) {
    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      if (is_null($this->content[$i]->reflow_text($viewport))) {
        return null;
      };
    }
    return true;
  }

  /**
   * Position/size current box as floating one
   */
  function reflow_static_float(&$parent, &$context) {
    // Defer the float rendering till the next line box
    if (!$parent->line_box_empty()) {
      $parent->add_deferred_float($this);
      return;
    };

    // Calculate margin values if they have been set as a percentage
    $this->_calc_percentage_margins($parent);
    $this->_calc_percentage_padding($parent);

    // Calculate width value if it have been set as a percentage
    $this->_calc_percentage_width($parent, $context);

    // Calculate margins and/or width is 'auto' values have been specified
    $this->_calc_auto_width_margins($parent);

    // Determine the actual width of the floating box
    // Note that get_max_width returns both content and extra width
    $this->put_full_width($this->get_max_width_natural($context, $this->parent->get_width()));

    // We need to call this function before determining the horizontal coordinate
    // as after vertical offset the additional space to the left may apperar
    $y = $this->apply_clear($parent->_current_y, $context);

    // determine the position of top-left floating box corner
    if ($this->getCSSProperty(CSS_FLOAT) === FLOAT_RIGHT) {
      $context->float_right_xy($parent, $this->get_full_width(), $x, $y);
      $x -= $this->get_full_width();
    } else {
      $context->float_left_xy($parent, $this->get_full_width(), $x, $y);
    };

    // Note that $x and $y contain just a free space corner coordinate;
    // If our float has a margin/padding space, we'll need to offset ot a little;
    // Remember that float margins are never collapsed!
    $this->moveto($x + $this->get_extra_left(), $y - $this->get_extra_top());  

    // Reflow contents. 
    // Note that floating box creates a new float flow context for it children.

    $context->push_floats();

    // Floating box create a separate margin collapsing context
    $context->push_collapsed_margin(0);

    $this->reflow_content($context); 

    $context->pop_collapsed_margin();

    // Floats and boxes with overflow: hidden
    // should completely enclose its child floats
    $this->fitFloats($context);

    // restore old float flow context
    $context->pop_floats();
    
    // Add this  box to the list of floats in current context
    $context->add_float($this);

    // Now fix the value of _current_x for the parent box; it is required
    // in the following case:
    // <body><img align="left">some text
    // in such situation floating image is flown immediately, but it the close_line call have been made before, 
    // so _current_x value of container box will be still equal to ots left content edge; by calling float_left_x again,
    // we'll force "some text" to be offset to the right
    $parent->_current_x = $context->float_left_x($parent->_current_x, $parent->_current_y);
  }  

  function reflow_whitespace(&$linebox_started, &$previous_whitespace) {
    $previous_whitespace = false;
    $linebox_started = false;

    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      $child =& $this->content[$i];

      $child->reflow_whitespace($linebox_started, $previous_whitespace);      
    };

    // remove the last whitespace in block box
    $this->remove_last_whitespace();

    // Non-inline box have terminated; we may be sure that line box will be closed
    // at this moment and new line box after this will be generated
    if (!is_inline($this)) { 
      $linebox_started = false; 
    };

    return;
  }

  function remove_last_whitespace() {
    if (count($this->content) == 0) { 
      return; 
    };

    $i = count($this->content)-1;
    $last = $this->content[$i];
    while ($i >= 0 && is_whitespace($this->content[$i])) {
      $this->remove($this->content[$i]);
      
      $i --;
      if ($i >= 0) {
        $last = $this->content[$i];
      };
    };

    if ($i >= 0) {
      if (is_container($this->content[$i])) {
        $this->content[$i]->remove_last_whitespace();
      };
    };
  }

  function remove(&$box) {
    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      if ($this->content[$i]->uid === $box->uid) {
        $this->content[$i] = NullBox::create();
      };
    };

    return;
  }

  function is_first(&$box) {
    $first =& $this->get_first();

    // Check if there's no first box at all
    //
    if (is_null($first)) { return false; };

    return $first->uid == $box->uid;
  }

  function is_null() {
    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      if (!$this->content[$i]->is_null()) { return false; };
    };
    return true;
  }

  // Calculate the available widths - e.g. content width minus space occupied by floats;
  // as floats may not fill the whole height of this box, this value depends on Y-coordinate.
  // We use current_Y in calculations
  //
  function get_available_width(&$context) {
    $left_float_width = $context->float_left_x($this->get_left(), $this->_current_y) - $this->get_left();
    $right_float_width = $this->get_right() - $context->float_right_x($this->get_right(), $this->_current_y);
    return $this->get_width() - $left_float_width - $right_float_width;
  }

  function pre_reflow_images() {
    $size = count($this->content);
    for ($i=0; $i<$size; $i++) {
      $this->content[$i]->pre_reflow_images();
    };
  }

  function _setupClip(&$driver) {
    if (!is_null($this->parent)) {
      $this->parent->_setupClip($driver);
    };

    $overflow = $this->getCSSProperty(CSS_OVERFLOW);
    if ($overflow !== OVERFLOW_VISIBLE && !$GLOBALS['g_config']['debugnoclip']) {
      $driver->moveto( $this->get_left_border() , $this->get_top_border());
      $driver->lineto( $this->get_right_border(), $this->get_top_border());
      $driver->lineto( $this->get_right_border(), $this->get_bottom_border());
      $driver->lineto( $this->get_left_border() , $this->get_bottom_border());
      $driver->closepath();
      $driver->clip();
    };
  }

  /**
   * DOMish functions
   */
  function &get_element_by_id($id) {
    if (isset($GLOBALS['__html_box_id_map'])) {
      return $GLOBALS['__html_box_id_map'][$id];
    } else {
      $dummy = null;
      return $dummy;
    };
  }
  
  /* 
   *  this is just a fake at the moment
   */
  function get_body() {
    return $this;
  }

  function getChildNodes() {
    return $this->content;
  }
}

?>