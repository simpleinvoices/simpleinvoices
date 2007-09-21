<?php

class StrategyPageBreakSimple {
  function StrategyPageBreakSimple() {
  }

  function run(&$pipeline, &$media, &$box) {
    $num_pages = ceil($box->get_height() / mm2pt($media->real_height()));
    $page_heights = array();
    for ($i=0; $i<$num_pages; $i++) {
      $page_heights[] = mm2pt($media->real_height());
    };    

    return $page_heights;
  }
}

?>