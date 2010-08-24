<?php

class StrategyLinkRenderingNormal {
  function StrategyLinkRenderingNormal() {
  }

  function apply(&$box, &$driver) {
    $link_target = $box->getCSSProperty(CSS_HTML2PS_LINK_TARGET);

    if (CSSPseudoLinkTarget::is_external_link($link_target)) {
      $driver->add_link($box->get_left(), 
                        $box->get_top(), 
                        $box->get_width(), 
                        $box->get_height(), 
                        $link_target);
    } elseif (CSSPseudoLinkTarget::is_local_link($link_target)) {
      if (isset($driver->anchors[substr($link_target,1)])) {
        $anchor = $driver->anchors[substr($link_target,1)];
        $driver->add_local_link($box->get_left(), 
                                $box->get_top(), 
                                $box->get_width(), 
                                $box->get_height(), 
                                $anchor);
      };
    };
  }
}
