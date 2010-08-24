<?php

class FeatureWatermark {
  var $_text;

  function FeatureWatermark() {
    $this->set_text('');
  }

  function get_text() {
    return $this->_text;
  }

  function handle_after_page($params) {
    $pipeline =& $params['pipeline'];
    $document =& $params['document'];
    $pageno =& $params['pageno'];

    $pipeline->output_driver->_show_watermark($this->get_text());
  }

  function install(&$pipeline, $params) {
    $dispatcher =& $pipeline->get_dispatcher();
    $dispatcher->add_observer('after-page', array(&$this, 'handle_after_page'));

    $this->set_text($params['text']);
  }

  function set_text($text) {
    $this->_text = $text;
  }
}

?>