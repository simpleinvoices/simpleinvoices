<?php
// $Header: /cvsroot/html2ps/box.table.php,v 1.59 2007/04/01 12:11:24 Konstantin Exp $

class CellSpan {
  var $row;
  var $column;
  var $size;
}

/**
 * It is assumed that every row contains at least one cell
 */
class TableBox extends GenericContainerBox {
  var $cwc;
  var $_cached_min_widths;

  function TableBox() {
    $this->GenericContainerBox();

    // List of column width constraints
    $this->cwc = array();

    $this->_cached_min_widths = null;
  }

  function readCSS(&$state) {
    parent::readCSS($state);
    
    $this->_readCSS($state,
                    array(CSS_BORDER_COLLAPSE,
                          CSS_TABLE_LAYOUT));
    
    $this->_readCSSLengths($state,
                           array(CSS_HTML2PS_CELLPADDING,
                                 CSS_HTML2PS_CELLSPACING));
  }

  function &cell($r, $c) {
    return $this->content[$r]->content[$c];
  }

  function rows_count() {
    return count($this->content);
  }

  // NOTE: assumes that rows are already normalized!
  function cols_count() {
    return count($this->content[0]->content);
  }

  // FIXME: just a stub
  function append_line(&$e) {}

  function &create(&$root, &$pipeline) {
    $box =& new TableBox();   
    $box->readCSS($pipeline->getCurrentCSSState());

    // This row should not inherit any table specific properties!
    // 'overflow' for example
    //
    $css_state =& $pipeline->getCurrentCSSState();
    $css_state->pushDefaultState();

    $row =& new TableRowBox($root);
    $row->readCSS($css_state);

    $box->add_child($row);

    $css_state->popState();

    // Setup cellspacing / cellpadding values
    if ($box->getCSSProperty(CSS_BORDER_COLLAPSE) == BORDER_COLLAPSE) {
      $handler =& CSS::get_handler(CSS_PADDING);
      $box->setCSSProperty(CSS_PADDING, $handler->default_value());
    };

    // Set text-align to 'left'; all browsers I've ever seen prevent inheriting of
    // 'text-align' property by the tables.
    // Say, in the following example the text inside the table cell will be aligned left, 
    // instead of inheriting 'center' value. 
    //
    // <div style="text-align: center; background-color: green;">
    // <table width="100" bgcolor="red">
    // <tr><td>TEST
    // </table>
    // </div>
    $handler =& CSS::get_handler(CSS_TEXT_ALIGN);
    $handler->css('left', $pipeline);

    // Parse table contents
    $child = $root->first_child();
    $col_index = 0;
    while ($child) {
      if ($child->node_type() === XML_ELEMENT_NODE) {
        if ($child->tagname() === 'colgroup') {
          // COLGROUP tags do not generate boxes; they contain information on the columns
          //
          $col_index = $box->parse_colgroup_tag($child, $col_index);
        } else {
          $child_box =& create_pdf_box($child, $pipeline);
          $box->add_child($child_box);
        };
      };
      
      $child = $child->next_sibling();
    };

    $box->normalize($pipeline);
    $box->normalize_cwc();
    $box->normalize_rhc();
    $box->normalize_parent();

    return $box;
  }

  // Parse the data in COL node;
  // currently only 'width' attribute is parsed
  //
  // @param $root reference to a COL dom node
  // @param $index index of column corresponding to this node
  function parse_col(&$root, $index) {
    if ($root->has_attribute('width')) {
      // The value if 'width' attrubute is "multi-length";
      // it means that it could be:
      // 1. absolute value (10)
      // 2. percentage value (10%)
      // 3. relative value (3* or just *)
      //
      
      // TODO: support for relative values
      
      $value = $root->get_attribute('width');
      if (is_percentage($value)) {
        $this->cwc[$index] = new WCFraction(((int)$value) / 100);
      } else {
        $this->cwc[$index] = new WCConstant(px2pt((int)$value));
      };
    };
  }

  // Traverse the COLGROUP node and save the column-specific information 
  //
  // @param $root COLGROUP node
  // @param $start_index index of the first column in this column group
  // @return index of column after the last processed
  //
  function parse_colgroup_tag(&$root, $start_index) {
    $index = $start_index;

    // COLGROUP may contain zero or more COLs
    //
    $child = $root->first_child();
    while ($child) {
      if ($child->tagname() === 'col') {
        $this->parse_col($child, $index);
        $index ++;
      };
      $child = $child->next_sibling();
    };

    return $index;
  }

  function normalize_parent() {
    for ($i=0; $i<count($this->content); $i++) {
      $this->content[$i]->parent =& $this;

      for ($j=0; $j<count($this->content[$i]->content); $j++) {
        $this->content[$i]->content[$j]->parent =& $this;
        
        // Set the column number for the cell to further reference
        $this->content[$i]->content[$j]->column = $j;

        // Set the column number for the cell to further reference
        $this->content[$i]->content[$j]->row    = $i;
      }
    }
  }

  // Normalize row height constraints
  // 
  // no return value
  //
  function normalize_rhc() {
    // Initialize the constraint array with the empty constraints
    $this->rhc = array();
    for ($i=0, $size = count($this->content); $i < $size; $i++) {
      $this->rhc[$i] = new HCConstraint(null, null, null);
    };

    // Scan all cells
    for ($i=0, $num_rows = count($this->content); $i < $num_rows; $i++) {
      $row =& $this->content[$i];

      for ($j=0, $num_cells = count($row->content); $j < $num_cells; $j++) {
        $cell = $row->content[$j];

        // Ignore cells with rowspans
        if ($cell->rowspan > 1) { continue; }
        
        // Put current cell width constraint as a columns with constraint
        $this->rhc[$i] = merge_height_constraint($this->rhc[$i], $cell->get_height_constraint());
        
        // Now reset the cell width constraint; cell width should be affected by ceolumn constraint only
        $hc = new HCConstraint(null, null, null);
        $cell->put_height_constraint($hc);
      };
    };
  }

  // Normalize column width constraints
  // Note that cwc array may be partially prefilled by a GOLGROUP/COL-generated constraints!
  // 
  function normalize_cwc() {
    // Note we've called 'normalize' method prior to 'normalize_cwc',
    // so we already have all rows of equal length
    //
    for ($i=0, $num_cols = count($this->content[0]->content); $i < $num_cols; $i++) {
      // Check if there's already COL-generated constraint for this column
      //
      if (!isset($this->cwc[$i])) {
        $this->cwc[$i] = new WCNone;
      };
    }

    // For each column (we should have table already normalized - so lengths of all rows are equal)
    for ($i=0, $num_cols = count($this->content[0]->content); $i < $num_cols; $i++) {

      // For each row
      for ($j=0, $num_rows = count($this->content); $j < $num_rows; $j++) {
        $cell =& $this->content[$j]->content[$i];

        // Ignore cells with colspans 
        if ($cell->colspan > 1) { continue; }

        // Put current cell width constraint as a columns with constraint
        $this->cwc[$i] = merge_width_constraint($this->cwc[$i], $cell->getCSSProperty(CSS_WIDTH));

        // Now reset the cell width constraint; cell width should be affected by ceolumn constraint only
        $cell->setCSSProperty(CSS_WIDTH, new WCNone);
      }
    }

    // Now fix the overconstrained columns; first of all, sum of all percentage-constrained 
    // columns should be less or equal than 100%. If sum is greater, the last column
    // percentage is reduced in order to get 100% as a result.
    $rest = 1;
    for ($i=0, $num_cols = count($this->content[0]->content); $i < $num_cols; $i++) {
      // Get current CWC
      $wc =& $this->cwc[$i];
      
      if ($wc->isFraction()) {
        $wc->fraction = min($rest, $wc->fraction);
        $rest -= $wc->fraction;
      };
    };

    /**
     * Now, let's process cells spanninig several columns.
     */

    /**
     * Let's check if there's any colspanning cells filling the whole table width and 
     * containing non-100% percentage constraint
     */

    // For each row
    for ($j=0; $j<count($this->content); $j++) {
      /**
       * Check if the first cell in this row satisfies the above condition
       */

      $cell =& $this->content[$j]->content[0];

      /**
       * Note that there should be '>='; '==' is not enough, as sometimes cell is declared to span 
       * more columns than there are in the table
       */
      $cell_wc = $cell->getCSSProperty(CSS_WIDTH);
      if (!$cell->is_fake() &&
          $cell_wc->isFraction() &&
          $cell->colspan >= count($this->content[$j])) {
        
        /**
         * Clear the constraint; anyway, it should be replaced with 100% in this case, as
         * this cell is the only cell in the row
         */

        $wc = new WCNone;
        $cell->setCSSProperty(CSS_WIDTH, $wc);
      };
    };
  }

  /**
   * Normalize table by adding fake cells for colspans and rowspans
   * Also, if there is any empty rows (without cells), add at least one fake cell
   */
  function normalize(&$pipeline) {
    /**
     * Fix empty rows by adding a fake cell
     */
    for ($i=0; $i<count($this->content); $i++) {
      $row =& $this->content[$i];
      if (count($row->content) == 0) {
        $this->content[$i]->add_fake_cell_before(0, $pipeline);
      };
    };

    /**
     * first, normalize colspans 
     */
    for ($i=0; $i<count($this->content); $i++) {
      $this->content[$i]->normalize($pipeline);
    };

    /**
     * second, normalize rowspans
     * 
     * We should scan table column-by-column searching for row-spanned cells; 
     * consider the following example: 
     *
     * <table>
     * <tr>
     * <td>A1</td>
     * <td rowspan="3">B1</td>
     * <td>C1</td>
     * </tr>
     *
     * <tr>
     * <td rowspan="2">A2</td>
     * <td>C2</td>
     * </tr>
     *
     * <tr>
     * <td>C3</td>
     * </tr>
     * </table>
     */
    
    $i_col = 0;
    do {
      $flag = false;
      for ($i_row=0; $i_row<count($this->content); $i_row++) {
        $row =& $this->content[$i_row];
        if ($i_col < count($row->content)) {
          $flag = true;

          // Check if this rowspan runs off the last row
          $row->content[$i_col]->rowspan = min($row->content[$i_col]->rowspan,
                                               count($this->content) - $i_row);

          if ($row->content[$i_col]->rowspan > 1) {
            
            // Note that min($i_row + $row->content[$i_col]->rowspan, count($this->content)) is 
            // required, as we cannot be sure that table actually contains the number
            // of rows used in rowspan 
            //
            for ($k=$i_row+1; $k<min($i_row + $row->content[$i_col]->rowspan, count($this->content)); $k++) {
              
              // Note that if rowspanned cell have a colspan, we should insert SEVERAL fake cells!
              //
              for ($cs = 0; $cs < $row->content[$i_col]->colspan; $cs++) {
                $this->content[$k]->add_fake_cell_before($i_col, $pipeline);
              };
            };
          };
        };
      };

      $i_col ++;
    } while ($flag);

    // third, make all rows equal in length by padding with fake-cells
    $length = 0;
    for ($i=0; $i<count($this->content); $i++) {
      $length = max($length, count($this->content[$i]->content));
    }
    for ($i=0; $i<count($this->content); $i++) {
      $row =& $this->content[$i];
      while ($length > count($row->content)) {
        $row->append_fake_cell($pipeline);
      }
    }
  }

  // Overrides default 'add_child' in GenericFormattedBox
  function add_child(&$item) {
    // Check if we're trying to add table cell to current table directly, without any table-rows
    if ($item->isCell()) {
      // Add cell to the last row
      $last_row =& $this->content[count($this->content)-1];
      $last_row->add_child($item);

    } elseif ($item->isTableRow()) {
      // If previous row is empty, remove it (get rid of automatically generated table row in constructor)
      if (count($this->content) > 0) {
        if (count($this->content[count($this->content)-1]->content) == 0) {
          array_pop($this->content);
        }
      };

      // Just add passed row 
      $this->content[] =& $item;
    } elseif ($item->isTableSection()) {
      // Add table section rows to current table, then drop section box
      for ($i=0, $size = count($item->content); $i < $size; $i++) {
        $this->add_child($item->content[$i]);
      }
    };
  }

  // Table-specific functions

  // PREDICATES
  function is_constrained_column($index) {
    return !is_a($this->get_cwc($index),"wcnone");
  }

  // ROWSPANS
  function table_have_rowspan($x,$y) {
    return $this->content[$y]->content[$x]->rowspan;
  }

  function table_fit_rowspans($heights) {
    $spans = $this->get_rowspans();

    // Scan all cells spanning several rows
    foreach ($spans as $span) {
      $cell =& $this->content[$span->row]->content[$span->column];

      // now check if cell height is less than sum of spanned rows heights
      $row_heights = array_slice($heights, $span->row, $span->size);

      // Vertical-align current cell 
      // calculate (approximate) row baseline
      $baseline = $this->content[$span->row]->get_row_baseline();

      // apply vertical-align
      $vertical_align = $cell->getCSSProperty(CSS_VERTICAL_ALIGN);

      $va_fun = CSSVerticalAlign::value2pdf($vertical_align);
      $va_fun->apply_cell($cell, array_sum($row_heights), $baseline);       
      
      if (array_sum($row_heights) > $cell->get_full_height()) {
        // Make cell fill all available vertical space
        $cell->put_full_height(array_sum($row_heights));
      };
    }
  }

  function get_rowspans() {
    $spans = array();

    for ($i=0; $i<count($this->content); $i++) {
      $spans = array_merge($spans, $this->content[$i]->get_rowspans($i));
    };

    return $spans;
  }

  // ROW-RELATED

  /**
   * Calculate set of row heights 
   *
   * At the moment (*), we have a sum of total content heights of percentage constraned rows in
   * $ch variable, and a "free" (e.g. table height - sum of all non-percentage constrained heights) height
   * in the $h variable. Obviously, percentage-constrained rows should be expanded to fill the free space
   *
   * On the other size, there should be a maximal value to expand them to; for example, if sum of 
   * percentage constraints is 33%, then all these rows should fill only 1/3 of the table height, 
   * whatever the content height of other rows is. In this case, other (non-constrained) rows 
   * should be expanded to fill space left.
   *
   * In the latter case, if there's no non-constrained rows, the additional space should be filled by 
   * "plain" rows without any constraints
   *
   * @param $minheight the minimal allowed height of the row; as we'll need to expand rows later
   * and rows containing totally empty cells will have zero height
   * @return array of row heights in media points
   */
  function _row_heights($minheight) {
    $heights = array();
    $cheights = array();
    $height = $this->get_height();
    
    // Calculate "content" and "constrained" heights of table rows
    
    for ($i=0; $i<count($this->content); $i++) {
      $heights[] = max($minheight, $this->content[$i]->row_height());

      // Apply row height constraint
      // we need to specify box which parent will serve as a base for height calculation;

      $hc = $this->get_rhc($i);
      $cheights[] = $hc->apply($heights[$i], $this->content[$i], null);
    };

    // Collapse "constrained" heights of percentage-constrained rows, if they're
    // taking more that available space

    $flags = $this->get_non_percentage_constrained_height_flags();
    $h = $height;
    $ch = 0;
    for ($i=0; $i<count($heights); $i++) {
      if ($flags[$i]) { $h -= $cheights[$i]; } else { $ch += $cheights[$i]; };
    };
    // (*) see note in the function description
    if ($ch > 0) {
      $scale = $h / $ch;
      
      if ($scale < 1) {
        for ($i=0; $i<count($heights); $i++) {
          if (!$flags[$i]) { $cheights[$i] *= $scale; };
        };
      };
    };

    // Expand non-constrained rows, if there's free space still

    $flags = $this->get_non_constrained_height_flags();
    $h = $height;
    $ch = 0;
    for ($i=0; $i<count($cheights); $i++) {
      if (!$flags[$i]) { $h -= $cheights[$i]; } else { $ch += $cheights[$i]; };
    };
    // (*) see note in the function description
    if ($ch > 0) {
      $scale = $h / $ch;
      
      if ($scale < 1) {
        for ($i=0; $i<count($heights); $i++) {
          if ($flags[$i]) { $cheights[$i] *= $scale; };
        };
      };
    };

    // Expand percentage-constrained rows, if there's free space still
    
    $flags = $this->get_non_percentage_constrained_height_flags();
    $h = $height;
    $ch = 0;
    for ($i=0; $i<count($cheights); $i++) {
      if ($flags[$i]) { $h -= $cheights[$i]; } else { $ch += $cheights[$i]; };
    };
    // (*) see note in the function description
    if ($ch > 0) {
      $scale = $h / $ch;
      
      if ($scale < 1) {
        for ($i=0; $i<count($heights); $i++) {
          if (!$flags[$i]) { $cheights[$i] *= $scale; };
        };
      };
    };

    // Get the actual row height
    for ($i=0; $i<count($heights); $i++) {
      $heights[$i] = max($heights[$i], $cheights[$i]);
    };

    return $heights;
  }

  function table_resize_rows(&$heights) {
    $row_top = $this->get_top();

    $size = count($heights);
    for ($i=0; $i<$size; $i++) {
      $this->content[$i]->table_resize_row($heights[$i], $row_top);
      $row_top -= $heights[$i];
    }

    // Set table height to sum of row heights
    $this->put_height(array_sum($heights));
  }
  
  //   // Calculate given table row height 
  //   // 
  //   // @param  $index zero-based row index
  //   // @return value of row height (in media points)
  //   //
  //   function table_row_height($index) {
  //     // Select row
  //     $row =& $this->content[$index];

  //     // Calculate height of each cell contained in this row
  //     $height = 0;
  //     for ($i=0; $i<count($row->content); $i++) {
  //       if ($this->table_have_rowspan($i, $index) <= 1) {
  //         $height = max($height, $row->content[$i]->get_full_height());
  //       }
  //     }
       
  //     return $height;
  //   }

  //   function get_row_baseline($index) {
  //     // Get current row
  //     $row =& $this->content[$index];
  //     // Calculate maximal baseline for each cell contained
  //     $baseline = 0;
  //     for ($i = 0; $i < count($row->content); $i++) {
  //       // Cell baseline is the baseline of its first line box inside this cell
  //       if (count($row->content[$i]->content) > 0) {
  //         $baseline = max($baseline, $row->content[$i]->content[0]->baseline);
  //       };
  //     };
  //     return $baseline;
  //   }

  // Width constraints
  function get_cwc($col) {
    return $this->cwc[$col];
  }

  // Get height constraint for the given row
  // 
  // @param $row number of row (zero-based)
  //
  // @return HCConstraint object
  //
  function get_rhc($row) {
    return $this->rhc[$row];
  }

  // Width calculation
  //
  // Note that if table have no width constraint AND some columns are percentage constrained,
  // then the width of the table can be determined based on the minimal column width; 
  // e.g. if some column have minimal width of 10px and 10% width constraint,
  // then table will have minimal width of 100px. If there's several percentage-constrained columns,
  // then we choose from the generated values the maximal one
  //
  // Of course, all of the above can be applied ONLY to table without width constraint;
  // of theres any w.c. applied to the table, it will have greater than column constraints
  // 
  // We must take constrained table width into account; if there's a width constraint,
  // then we must choose the maximal value between the constrained width and sum of minimal
  // columns widths - so, expanding the constrained width in case it is not enough to fit
  // the table contents
  //
  // @param $context referene to a flow context object
  // @return minimal box width (including the padding/margin/border width! NOT content width)
  //
  function get_min_width(&$context) {
    $widths = $this->get_table_columns_min_widths($context);
    $maxw = $this->get_table_columns_max_widths($context);

    // Expand some columns to fit colspanning cells
    $widths = $this->_table_apply_colspans($widths, $context, 'get_min_width', $widths, $maxw);

    $width = array_sum($widths);
    $base_width = $width;

    $wc = $this->getCSSProperty(CSS_WIDTH);
    if (!$wc->isNull()) {
      // Check if constrained table width should be expanded to fit the table contents
      //
      $width = max($width, $wc->apply(0, $this->parent->get_available_width($context)));
    } else {
      // Now check if there's any percentage column width constraints (note that 
      // if we've get here, than the table width is not constrained). Calculate 
      // the table width basing on these values and select the maximal value
      //
      for ($i=0; $i<$this->cols_count(); $i++) {
        $cwc = $this->get_cwc($i);
        
        $width = max($width, 
                     min($cwc->apply_inverse($widths[$i], $base_width),
                         $this->parent->get_available_width($context) - $this->_get_hor_extra()));
      };
    };

    return $width + $this->_get_hor_extra();    
  }

  function get_min_width_natural(&$context) {
    return $this->get_min_width($context);
  }

  function get_max_width(&$context) {
    $wc = $this->getCSSProperty(CSS_WIDTH);

    if ($wc->isConstant()) {
      return $wc->apply(0, $this->parent->get_available_width($context));
    } else {
      $widths = $this->get_table_columns_max_widths($context);
      $minwc = $this->get_table_columns_min_widths($context);

      $widths = $this->_table_apply_colspans($widths, $context, 'get_max_width', $minwc, $widths);

      $width = array_sum($widths);
      $base_width = $width;

      // Now check if there's any percentage column width constraints (note that 
      // if we've get here, than the table width is not constrained). Calculate 
      // the table width based on these values and select the maximal value
      //
      for ($i=0; $i<$this->cols_count(); $i++) {
        $cwc = $this->get_cwc($i);

        $width = max($width, 
                     min($cwc->apply_inverse($widths[$i], $base_width),
                         $this->parent->get_available_width($context) - $this->_get_hor_extra()));
      };

      return $width + $this->_get_hor_extra();
    }
  }

  function get_max_width_natural(&$context) {
    return $this->get_max_width($context);
  }

  function get_width() {
    $wc  = $this->getCSSProperty(CSS_WIDTH);
    $pwc = $this->parent->getCSSProperty(CSS_WIDTH);

    if (!$this->parent->isCell() || 
        !$pwc->isNull() ||
        !$wc->isFraction()) {
      $width = $wc->apply($this->width, $this->parent->width);
    } else {
      $width = $this->width;
    };

    // Note that table 'padding' property for is handled differently 
    // by different browsers; for example, IE 6 ignores it completely,
    // while FF 1.5 subtracts horizontal padding value from constrained 
    // table width. We emulate FF behavior here
    return $width -
      $this->get_padding_left() -
      $this->get_padding_right();
  }

  function table_column_widths(&$context) {
    $table_layout = $this->getCSSProperty(CSS_TABLE_LAYOUT);
    switch ($table_layout) {
    case TABLE_LAYOUT_FIXED:
//       require_once(HTML2PS_DIR.'strategy.table.layout.fixed.php');
//       $strategy =& new StrategyTableLayoutFixed();
//       break;
    case TABLE_LAYOUT_AUTO:
    default:
      require_once(HTML2PS_DIR.'strategy.table.layout.auto.php');
      $strategy =& new StrategyTableLayoutAuto();
      break;
    };
    
    return $strategy->apply($this, $context);
  }

  // Extend some columns widths (if needed) to fit colspanned cell contents
  //
  function _table_apply_colspans($widths, &$context, $width_fun, $minwc, $maxwc) {
    $colspans = $this->get_colspans();
    
    foreach ($colspans as $colspan) {
      $cell = $this->content[$colspan->row]->content[$colspan->column];

      // apply colspans to the corresponsing colspanned-cell dimension
      //
      $cell_width = $cell->$width_fun($context);

      // Apply cell constraint width, if any AND if table width is constrained
      // if table width is not constrained, we should not do this, as current value 
      // of $table->get_width is maximal width (parent width), not the actual 
      // width of the table
      $wc = $this->getCSSProperty(CSS_WIDTH);
      if (!$wc->isNull()) {
        $cell_wc = $cell->getCSSProperty(CSS_WIDTH);
        $cell_width = $cell_wc->apply($cell_width, $this->get_width());

        // On the other side, constrained with cannot be less than cell minimal width
        $cell_width = max($cell_width, $cell->get_min_width($context));
      };

      // now select the pre-calculated widths of columns covered by this cell
      // select the list of resizable columns covered by this cell
      $spanned_widths = array();
      $spanned_resizable = array();

      for ($i=$colspan->column; $i < $colspan->column+$colspan->size; $i++) {
        $spanned_widths[] = $widths[$i];
        $spanned_resizable[] = ($minwc[$i] != $maxwc[$i]);
      }

      // Sometimes we may encounter the colspan over the empty columns (I mean ALL columns are empty); in this case 
      // we need to make these columns reizable in order to fit colspanned cell contents
      //
      if (array_sum($spanned_widths) == 0) {
        for ($i=0; $i<count($spanned_widths); $i++) {
          $spanned_widths[$i] = EPSILON;
          $spanned_resizable[$i] = true;
        };
      };

      // The same problem may arise when all colspanned columns are not resizable; in this case we'll force all
      // of them to be resized
      $any_resizable = false;
      for ($i=0; $i<count($spanned_widths); $i++) {
        $any_resizable |= $spanned_resizable[$i];
      };
      if (!$any_resizable) {
        for ($i=0; $i<count($spanned_widths); $i++) {
          $spanned_resizable[$i] = true;
        };
      }

      // Expand resizable columns
      //
      $spanned_widths = expand_to_with_flags($cell_width,$spanned_widths,$spanned_resizable);

      // Store modified widths
      array_splice($widths, $colspan->column, $colspan->size, $spanned_widths);
    };

    return $widths;
  }
 
  function get_table_columns_max_widths(&$context) {
    $widths = array();

    for ($i=0; $i<count($this->content[0]->content); $i++) {
      $widths[] = 0;
    };

    for ($i=0; $i<count($this->content); $i++) {
      // Calculate column widths for a current row
      $roww = $this->content[$i]->get_table_columns_max_widths($context);
      for ($j=0; $j<count($roww); $j++) {
        //        $widths[$j] = max($roww[$j], isset($widths[$j]) ? $widths[$j] : 0);
        $widths[$j] = max($roww[$j], $widths[$j]);
      }
    }

    // Use column width constraints - column should not be wider its constrained width
    for ($i=0; $i<count($widths); $i++) {
      $cwc = $this->get_cwc($i);

      // Newertheless, percentage constraints should not be applied IF table 
      // does not have constrained width
      //
      if (!is_a($cwc,"wcfraction")) {
        $widths[$i] = $cwc->apply($widths[$i], $this->get_width());
      };
    }

    // TODO: colspans

    return $widths;
  }

  /**
   * Optimization: calculated widths are cached
   */
  function get_table_columns_min_widths(&$context) {
    if (!is_null($this->_cached_min_widths)) { 
      return $this->_cached_min_widths;
    };

    $widths = array();

    for ($i=0; $i<count($this->content[0]->content); $i++) {
      $widths[] = 0;
    };
    
    $content_size = count($this->content);
    for ($i=0; $i<$content_size; $i++) {
      // Calculate column widths for a current row
      $roww = $this->content[$i]->get_table_columns_min_widths($context);

      $row_size = count($roww);
      for ($j=0; $j<$row_size; $j++) {
        $widths[$j] = max($roww[$j], $widths[$j]);
      }
    }

    $this->_cached_min_widths = $widths;
    return $widths;
  }

  function get_colspans() {
    $colspans = array();

    for ($i=0; $i<count($this->content); $i++) {
      $colspans = array_merge($colspans, $this->content[$i]->get_colspans($i));
    };

    return $colspans;
  }

  function check_constrained_colspan($col) {
    for ($i=0; $i<$this->rows_count(); $i++) {
      $cell =& $this->cell($i, $col);
      $cell_wc = $cell->getCSSProperty(CSS_WIDTH);

      if ($cell->colspan > 1 && 
          !$cell_wc->isNull()) { 
        return true; 
      };
    };
    return false;
  }

  // Tries to change minimal constrained width so that columns will fit into the given
  // table width
  //
  // Note that every width constraint have its own priority; first, the unconstrained columns are collapsed,
  // then - percentage constrained and after all - columns having fixed width
  // 
  // @param $width table width
  // @param $minw array of unconstrained minimal widths
  // @param $minwc array of constrained minimal widths
  // @return list of normalized minimal constrained widths
  //
  function normalize_min_widths($width, $minw, $minwc) {
    // Check if sum of constrained widths is too big
    // Note that we compare sum of constrained width with the MAXIMAL value of table width and
    // sum of uncostrained minimal width; it will prevent from unneeded collapsing of table cells
    // if table content will expand its width anyway
    //
    $twidth = max($width, array_sum($minw));

    // compare with sum of minimal constrained widths 
    //
    if (array_sum($minwc) > $twidth) {
      $delta = array_sum($minwc) - $twidth;

      // Calculate the amount of difference between minimal and constrained minimal width for each columns
      $diff = array();
      for ($i=0; $i<count($minw); $i++) {
        // Do no modify width of columns taking part in constrained colspans
        if (!$this->check_constrained_colspan($i)) {
          $diff[$i] = $minwc[$i] - $minw[$i];
        } else {
          $diff[$i] = 0;
        };
      }
      
      // If no difference is found, we can collapse no columns
      // otherwise scale some columns...
      $cwdelta = array_sum($diff);

      if ($cwdelta > 0) {
        for ($i=0; $i<count($minw); $i++) {
          //          $minwc[$i] = max(0,- ($minwc[$i] - $minw[$i]) / $cwdelta * $delta + $minwc[$i]);
          $minwc[$i] = max(0, -$diff[$i] / $cwdelta * $delta + $minwc[$i]);
        }
      }
    }

    return $minwc;
  }

  function table_have_colspan($x, $y) {
    return $this->content[$y]->content[$x]->colspan;
  }

  // Flow-control
  function reflow(&$parent, &$context) {
    if ($this->getCSSProperty(CSS_FLOAT) === FLOAT_NONE) {
      $status = $this->reflow_static_normal($parent, $context);
    } else {
      $status = $this->reflow_static_float($parent, $context);
    }

    return $status;
  }

  function reflow_absolute(&$context) {
    GenericFormattedBox::reflow($parent, $context);

    // Calculate margin values if they have been set as a percentage
    $this->_calc_percentage_margins($parent);

    // Calculate width value if it had been set as a percentage
    $this->_calc_percentage_width($parent, $context);

    $wc = $this->getCSSProperty(CSS_WIDTH);
    if (!$wc->isNull()) {
      $col_width = $this->get_table_columns_min_widths($context);
      $maxw      = $this->get_table_columns_max_widths($context);
      $col_width = $this->_table_apply_colspans($col_width, $context, 'get_min_width', $col_width, $maxw);

      if (array_sum($col_width) > $this->get_width()) {
        $wc = new WCConstant(array_sum($col_width));
      };
    };

    $position_strategy =& new StrategyPositionAbsolute();
    $position_strategy->apply($this);

    $this->reflow_content($context);
  }

  /**
   * TODO: unlike block elements, table unconstrained width is determined 
   * with its content, so it may be smaller than parent available width!
   */
  function reflow_static_normal(&$parent, &$context) {
    GenericFormattedBox::reflow($parent, $context);

    // Calculate margin values if they have been set as a percentage
    $this->_calc_percentage_margins($parent);

    // Calculate width value if it had been set as a percentage
    $this->_calc_percentage_width($parent, $context);

    $wc = $this->getCSSProperty(CSS_WIDTH);
    if (!$wc->isNull()) {
      $col_width = $this->get_table_columns_min_widths($context);
      $maxw      = $this->get_table_columns_max_widths($context);
      $col_width = $this->_table_apply_colspans($col_width, $context, 'get_min_width', $col_width, $maxw);

      if (array_sum($col_width) > $this->get_width()) {
        $wc = new WCConstant(array_sum($col_width));
      };
    };

    // As table width can be deterimined by its contents, we may calculate auto values 
    // only AFTER the contents have been reflown; thus, we'll offset the table 
    // as a whole by a value of left margin AFTER the content reflow

    // Do margin collapsing
    $y = $this->collapse_margin($parent, $context);

    // At this moment we have top parent/child collapsed margin at the top of context object
    // margin stack
    
    $y = $this->apply_clear($y, $context);
   
    // Store calculated Y coordinate as current Y in the parent box
    $parent->_current_y = $y;
    
    // Terminate current parent line-box 
    $parent->close_line($context);
   
    // And add current box to the parent's line-box (alone)
    $parent->append_line($this);

    // Determine upper-left _content_ corner position of current box 
    // Also see note above regarding margins
    $border = $this->getCSSProperty(CSS_BORDER);
    $padding = $this->getCSSProperty(CSS_PADDING);

    $this->put_left($parent->_current_x + 
                    $border->left->get_width() + 
                    $padding->left->value);
    
    // Note that top margin already used above during maring collapsing
    $this->put_top($parent->_current_y - $border->top->get_width()  - $padding->top->value);
    
    /** 
     * By default, child block box will fill all available parent width;
     * note that actual width will be smaller because of non-zero padding, border and margins
     */
    $this->put_full_width($parent->get_available_width($context));
  
    // Reflow contents
    $this->reflow_content($context);
  
    // Update the collapsed margin value with current box bottom margin
    $margin = $this->getCSSProperty(CSS_MARGIN);

    $context->pop_collapsed_margin();
    $context->pop_collapsed_margin();
    $context->push_collapsed_margin($margin->bottom->value);

    // Calculate margins and/or width is 'auto' values have been specified
    $this->_calc_auto_width_margins($parent); 
    $this->offset($margin->left->value, 0);
    
    // Extend parent's height to fit current box
    $parent->extend_height($this->get_bottom_margin());
    // Terminate parent's line box
    $parent->close_line($context);
  }
  
  // Get a list of boolean values indicating if table rows are height constrained
  // 
  // @return array containing 'true' value at index I if I-th row is not height-constrained
  // and 'false' otherwise
  //
  function get_non_constrained_flags() {
    $flags = array();

    for ($i=0; $i<count($this->content); $i++) {
      $hc = $this->get_rhc($i);
      $flags[$i] = 
        (is_null($hc->constant)) &&
        (is_null($hc->min)) &&
        (is_null($hc->max));
    };

    return $flags;
  }

  // Get a list of boolean values indicating if table rows are height constrained using percentage values
  // 
  // @return array containing 'true' value at index I if I-th row is not height-constrained
  // and 'false' otherwise
  //
  function get_non_percentage_constrained_height_flags() {
    $flags = array();

    for ($i=0; $i<count($this->content); $i++) {
      $hc = $this->get_rhc($i);
      $flags[$i] = 
        (!is_null($hc->constant) ? !$hc->constant[1] : true) &&
        (!is_null($hc->min)      ? !$hc->min[1]      : true) &&
        (!is_null($hc->max)      ? !$hc->max[1]      : true);
    };

    return $flags;
  }

  function get_non_constrained_height_flags() {
    $flags = array();

    for ($i=0; $i<count($this->content); $i++) {
      $hc = $this->get_rhc($i);

      $flags[$i] = $hc->is_null();
    };

    return $flags;
  }

  // Get a list of boolean values indicating if table columns are height constrained
  // 
  // @return array containing 'true' value at index I if I-th columns is not width-constrained
  // and 'false' otherwise
  //
  function get_non_constrained_width_flags() {
    $flags = array();

    for ($i=0; $i<$this->cols_count(); $i++) {
      $wc = $this->get_cwc($i);
      $flags[$i] = is_a($wc,"wcnone");
    };

    return $flags;
  }

  function get_non_constant_constrained_width_flags() {
    $flags = array();

    for ($i=0; $i<$this->cols_count(); $i++) {
      $wc = $this->get_cwc($i);
      $flags[$i] = !is_a($wc,"WCConstant");
    };

    return $flags;
  }

  function check_if_column_image_constrained($col) {
    for ($i=0; $i<$this->rows_count(); $i++) {
      $cell =& $this->cell($i, $col);
      for ($j=0; $j<count($cell->content); $j++) {
        if (!$cell->content[$j]->is_null() &&
            !is_a($cell->content[$j], "GenericImgBox")) {
          return false;
        };
      };
    };
    return true;
  }

  function get_non_image_constrained_width_flags() {
    $flags = array();

    for ($i=0; $i<$this->cols_count(); $i++) {
      $flags[$i] = !$this->check_if_column_image_constrained($i);
    };

    return $flags;
  }

  // Get a list of boolean values indicating if table rows are NOT constant constrained
  // 
  // @return array containing 'true' value at index I if I-th row is height-constrained
  // and 'false' otherwise
  //
  function get_non_constant_constrained_flags() {
    $flags = array();

    for ($i=0; $i<count($this->content); $i++) {
      $hc = $this->get_rhc($i);
      $flags[$i] = is_null($hc->constant);
    };

    return $flags;
  }

  function reflow_content(&$context) {
    // Reflow content

    // Reset current Y value
    //
    $this->_current_y = $this->get_top();

    // Determine the base table width 
    // if width constraint exists, the actual table width will not be changed anyway
    //
    $this->put_width(min($this->get_max_width($context), $this->get_width()));

    // Calculate widths of table columns
    $columns = $this->table_column_widths($context);

    // Collapse table to minimum width (if width is not constrained)
    $real_width = array_sum($columns);
    $this->put_width($real_width);  

    // If width is constrained, and is less than calculated, update the width constraint
    //
    //     if ($this->get_width() < $real_width) {
    //       // $this->put_width_constraint(new WCConstant($real_width));
    //     };

    // Flow cells horizontally in each table row
    for ($i=0; $i<count($this->content); $i++) {
      // Row flow started 
      // Reset current X coordinate to the far left of the table
      $this->_current_x = $this->get_left();

      // Flow each cell in the row
      $span = 0;
      for ($j=0; $j<count($this->content[$i]->content); $j++) {
        // Skip cells covered by colspans (fake cells, anyway)
        if ($span == 0) {
          // Flow current cell
          // Any colspans here?
          $span = $this->table_have_colspan($j, $i);

          // Get sum of width for the current cell (or several cells in colspan)
          // In most cases, $span == 1 here (just a single cell)
          $cw = array_sum(array_slice($columns, $j, $span));

          // store calculated width of the current cell
          $cell =& $this->content[$i]->content[$j];
          $cell->put_full_width($cw);
          $cell->setCSSProperty(CSS_WIDTH, 
                                new WCConstant($cw - 
                                               $cell->_get_hor_extra()));
                    
          // TODO: check for rowspans

          // Flow cell
          $this->content[$i]->content[$j]->reflow($this, $context);

          // Offset current X value by the cell width
          $this->_current_x += $cw;
        };

        // Current cell have been processed or skipped
        $span = max(0, $span-1);
      }

      // calculate row height and do vertical align
      //      $this->table_fit_row($i);

      // row height calculation offset current Y coordinate by the row height calculated
      //      $this->_current_y -= $this->table_row_height($i);
      $this->_current_y -= $this->content[$i]->row_height();
    }

    // Calculate (and possibly adjust height of table rows)
    $heights = $this->_row_heights(0.1);

    // adjust row heights to fit cells spanning several rows
    foreach ($this->get_rowspans() as $rowspan) {
      // Get height of the cell
      $cell_height = $this->content[$rowspan->row]->content[$rowspan->column]->get_full_height();    

      // Get calculated height of the spanned-over rows
      $cell_row_heights = array_slice($heights, $rowspan->row, $rowspan->size);

      // Get list of non-constrained columns
      $flags = array_slice($this->get_non_constrained_flags(), $rowspan->row, $rowspan->size);

      // Expand row heights (only for non-constrained columns)
      $new_heights = expand_to_with_flags($cell_height, 
                                          $cell_row_heights, 
                                          $flags);

      // Check if rows could not be expanded
      //      if (array_sum($new_heights) < $cell_height-1) {
      if (array_sum($new_heights) < $cell_height - EPSILON) {
        // Get list of non-constant-constrained columns
        $flags = array_slice($this->get_non_constant_constrained_flags(), $rowspan->row, $rowspan->size);

        // use non-constant-constrained rows 
        $new_heights = expand_to_with_flags($cell_height, 
                                            $cell_row_heights, 
                                            $flags);
      };

      // Update the rows heights
      array_splice($heights, 
                   $rowspan->row, 
                   $rowspan->size, 
                   $new_heights);
    }

    // Now expand rows to full table height
    $table_height = max($this->get_height(), array_sum($heights));

    // Get list of non-constrained columns
    $flags = $this->get_non_constrained_height_flags();

    // Expand row heights (only for non-constrained columns)
    $heights = expand_to_with_flags($table_height, 
                                    $heights, 
                                    $flags);

    // Check if rows could not be expanded
    if (array_sum($heights) < $table_height - EPSILON) {
      // Get list of non-constant-constrained columns
      $flags = $this->get_non_constant_constrained_flags();
      
      // use non-constant-constrained rows 
      $heights = expand_to_with_flags($table_height, 
                                      $heights, 
                                      $flags);
    };

    // Now we calculated row heights, time to actually resize them    
    $this->table_resize_rows($heights);

    // Update size of cells spanning several rows
    $this->table_fit_rowspans($heights);

    // Expand total table height, if needed
    $total_height = array_sum($heights);
    if ($total_height > $this->get_height()) {
      $hc = new HCConstraint(array($total_height, false),
                             array($total_height, false), 
                             array($total_height, false));
      $this->put_height_constraint($hc);
    };
  }

  function isBlockLevel() {
    return true;
  }
}
?>