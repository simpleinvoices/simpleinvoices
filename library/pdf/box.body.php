<?php
class BodyBox extends BlockBox {

    public function __construct() {
        parent::__construct();
    }

    public function &create(&$root, &$pipeline) {
        $box = new BodyBox();
        $box->readCSS($pipeline->getCurrentCSSState());
        $box->create_content($root, $pipeline);
        return $box;
    }

    public function get_bottom_background() {
        return $this->get_bottom_margin();
    }

    public function get_left_background() {
        return $this->get_left_margin();
    }

    public function get_right_background() {
        return $this->get_right_margin();
    }

    public function get_top_background() {
        return $this->get_top_margin();
    }

    public function reflow(&$parent, &$context) {
        parent::reflow($parent, $context);

        // Extend the body height to fit all contained floats
        $float_bottom = $context->float_bottom();
        if (!is_null($float_bottom)) {
            $this->extend_height($float_bottom);
        }
    }
}
