<?php

class StrategyTableLayoutFixed {
  function StrategyTableLayoutFixed() {
  }

  function apply($table, &$context) {
    $width = $table->get_width();
    $widths = array();
    for ($i = 0, $size = $table->cols_count(); $i < $size; $i++) {
      $cwc =& $table->get_cwc($i);
      $widths[] = $cwc->apply(0, $table->parent->get_width());
    };
    return $widths;
  }
}

?>