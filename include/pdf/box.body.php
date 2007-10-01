<?php

class BodyBox extends BlockBox {
  function &create(&$root, &$pipeline) {
    $box = new BodyBox();
    $box->create_content($root, $pipeline);
    return $box;
  }

  function reflow(&$parent, &$context) {
    parent::reflow($parent, $context);
    
    // Extend the body height to fit all contained floats
    $float_bottom = $context->float_bottom();
    if ($float_bottom !== null) {
      $this->extend_height($float_bottom);
    };
  }

  function get_left_background()   { return $this->get_left_margin();   }
  function get_right_background()  { return $this->get_right_margin();  }
  function get_top_background()    { return $this->get_top_margin();    }
  function get_bottom_background() { return $this->get_bottom_margin(); }
}

?>