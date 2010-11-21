<?php

class StrategyPageBreakSmart {
  function StrategyPageBreakSmart() {
  }

  function run(&$pipeline, &$media, &$box) {
    $page_heights = PageBreakLocator::getPages($box, 
                                               mm2pt($media->real_height()), 
                                               mm2pt($media->height() - $media->margins['top']));

    return $page_heights;
  }
}

?>