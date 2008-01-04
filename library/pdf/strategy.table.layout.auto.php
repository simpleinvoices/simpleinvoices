<?php

class StrategyTableLayoutAuto {
  function StrategyTableLayoutAuto() {
  }

  function apply($table, &$context) {
    $width = $table->get_width();
    return $this->table_columns_fit($table, $width, $context);
  }

  function use_colspans(&$table, $widths, &$context, $width_fun, $minwc, $maxwc) {
    $colspans = $table->get_colspans();
    
    foreach ($colspans as $colspan) {
      $cell = $table->content[$colspan->row]->content[$colspan->column];

      // apply colspans to the corresponsing colspanned-cell dimension
      //
      $cell_width = $cell->$width_fun($context);

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

  /**
   * Fit table columns to the given width
   */
  function table_columns_fit(&$table, $width, &$context) {
    $minw = $table->get_table_columns_min_widths($context);
    $maxw = $table->get_table_columns_max_widths($context);

    $minw = $this->use_colspans($table, $minw, $context, 'get_min_width', $minw, $maxw);
    $maxw = $this->use_colspans($table, $maxw, $context, 'get_max_width', $minw, $maxw);

    // Store number of columns
    $columns = count($minw);

    // Apply column width constraints
    $minwc = array();
    $maxwc = array();

    $cellpadding = $table->getCSSProperty(CSS_HTML2PS_CELLPADDING);
    $cellspacing = $table->getCSSProperty(CSS_HTML2PS_CELLSPACING);

    for ($i=0; $i<count($minw); $i++) {
      $cwc = $table->get_cwc($i);
      
      // Do not allow constrained max width be less than min width
      // Do not allow constrained min width be less than min width
      //
      $table_width = $table->get_width();
      
      $extra = 2*$cellpadding->getPoints() + $cellspacing->getPoints();

      $minwc[$i] = max($minw[$i], $cwc->apply($minw[$i]-$extra, $table_width) + $extra);
      $maxwc[$i] = max($minw[$i], $cwc->apply($maxw[$i]-$extra, $table_width) + $extra);
    };

    $minwc = $table->normalize_min_widths($width, $minw, $minwc);
    $minwc = $table->_table_apply_colspans($minwc, $context, 'get_min_width', $minwc, $maxwc);
   
    // We need to normalize widths for the case of colspans width is too big; for example:
    // <table><tr><td width="100">
    // <table><tr><td width="150">TEXT<td>TEXT<tr><td colspan="2" width="200">
    // in this case table SHOULD NOT be expanded over the 100px!
    //
    // $minwc = $table->normalize_min_widths($width, $minw, $minwc);
    $maxwc = $table->_table_apply_colspans($maxwc, $context, 'get_max_width', $minwc, $maxwc);

    // Calculate actual widths
    $widths = array();
    // Calculate widths for all constrained columns
    for ($i=0; $i < $columns; $i++) {
      if ($table->is_constrained_column($i)) {
        $widths[$i] = $minwc[$i];
      }
    }

    // Quick fix for overconstrained tables: if table have width attribute AND its value is less than sum
    // of constrained columns widths plus minimal widths of uncostrained columns, then we'll expand the width of table
    // to fit all columns
    // 1. calculate sum of constrained column widths
    // 2. calculate sum of unconstrained column minimal widths
    $sum_cw = 0;
    $sum_ucw = 0;
    for ($i=0; $i < $columns; $i++) {
      if ($table->is_constrained_column($i)) {
        $sum_cw += $widths[$i];
      } else {
        $sum_ucw += $minwc[$i];
      }
    }

    // 3. compare these widths with the table width and choose the maximal value
    $width = max($width, $sum_cw + $sum_ucw);

    // Second pass - disctribute the rest of the width

    // Explanation of the stuff below (I've really had problems with this small piece of code, especially
    // when I was trying to fix "bugs" inside it)
    // 
    // First of all, no column can be narrower than it minimal width (determined by its content)
    // Note that constrained columns have their widths distributed above, so we can exclude them for now 
    // (just throw them out and imagine that table does not contain any width-constrained cols)
    //
    // Second, the relative widths of columns will have _appoximately_ the same ratio as
    // their maximal content widths. (In exception of cases where the first rule will take place - 
    // say for the table containing two columns with the VERY long text in the first and one or two words
    // in the second)
    //
    // In general, this approach can be inoptimal in case of _very_ different font sizes
    // inside the cells, of, say big images; nevertheless, it will give a good approximate
    // AND still fast enough (unlike fully correct methods involving evaluation of the content height of the cell)
    // 
    // Thus, we do the following:
    // - calculate the ratio of current column MAXIMAL ($current_max) width to the sum of MAXIMAL widths of all columns left
    //   (inluding current) second rule applied. Note that we need remember about column spans and select 
    //   maxw or maxwc in order.
    // - then check if the rest of width will be too small for other columns to fit and decrease current columns
    //   width (see MIN function call)
    // - then check again if our width will be too small for current column to fit (and expand if nesessary) - 
    //   MAX function call
    for ($i=0; $i < $columns; $i++) {
      if (!$table->is_constrained_column($i)) {
        // Get undistributed width (total table width - width of constrained columns)
        $rest = $width - array_sum($widths);
        // get max width of column being processed
        // If width is equal to zero, use max constrained width, as this column could be covered by colspan;
        // If not, we lose nothing, because all constrained columns are already processed earlier, and no more
        // columns except these two types can have different constrained and raw widths
        $current_max = max($maxw[$i], $maxwc[$i]);

        // Get sum of maximal constrained widths of unplaced columns
        $sum_max_cw = 0;
        $sum_min_cw = 0;
        for ($j=0; $j<$columns; $j++) {
          if (!isset($widths[$j])) { 
            $sum_max_cw += max($maxw[$j], $maxwc[$j]);
            $sum_min_cw += max($minw[$j], $minwc[$j]);
          };
        };

        // If some unplaced columns have maximal (constrained width) greater zero
        if ($sum_max_cw > 0) {
          $current_max = min($current_max * $rest / $sum_max_cw, $rest - $sum_min_cw + max($minwc[$i], $minw[$i]));
        };

        // Check for minimal width (either unconstrained or constrained) of current column
        $current_max = max($current_max, $minw[$i] == 0 ? $minwc[$i] : $minw[$i]);
        // Store calculated width
        $widths[$i] = $current_max;
      }
    }

    // Process the case of a lone empty table cell (used, for example, for its background color)
    // as we're using floating point numbers, we cannot use equals sign
    if (array_sum($widths) < EPSILON) {
      for ($i=0; $i<count($widths); $i++) {
        $widths[$i] = 0.01;
      };
    };
    
    // now - the last attempt; if total width is less than box width, then we have a situation when either 
    // all columns AND table are width constrained or the HTML similar to the following:
    // 
    // <table cellpadding="0" width="100%" bgcolor="green"><tbody><tr>
    // <td colspan="2" bgcolor="yellow"></td>
    // <td style="width: 100px;" bgcolor="cyan">TEXT
    //
    // e.g. empty column (with zero width) and fixed-width column.
    //
    $wc = $table->getCSSProperty(CSS_WIDTH);
    if (!$wc->isNull()) {
      if (array_sum($widths) < $width) {     
        // Let's make zero-width columns
        // non-zero width (so that they columd be expanded) and re-try expanding columns
        //
        for ($i=0; $i<count($widths); $i++) {
          if ($widths[$i] == 0) { $widths[$i] = EPSILON; };
        };
        
        // Now, if there's at least one non-costrained columns, try expanding them again
        $flags = $table->get_non_constrained_width_flags();
        if (!any_flag_set($flags)) {
          $flags = $table->get_non_constant_constrained_width_flags();
          if (!any_flag_set($flags)) {
            $flags = $table->get_non_image_constrained_width_flags();
            if (!any_flag_set($flags)) {
              for ($i=0; $i<count($flags); $i++) { $flags[$i] = true; };
            };
          };
        };
        
        $widths = expand_to_with_flags($width, 
                                       $widths,
                                       $flags);
      };

      // in case of overconstrained table (e.g. two columns with 20% widths), expand them
      $widths = expand_to($width, $widths);
    };
    
    $table->put_full_width(array_sum($widths));

    // Now we need to sort array by key keeping key-value associations in order for array_slice to work correctly
    ksort($widths, SORT_NUMERIC);

    return $widths;
  }
}

?>