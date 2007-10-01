<?php
class PreTreeFilterHeaderFooter extends PreTreeFilter {
  var $header_html;
  var $footer_html;

  function PreTreeFilterHeaderFooter($header_html, $footer_html) {
    if (trim($header_html) != "") {
      $this->header_html    = "<body style='position: fixed; background-color: red; margin: 0; padding: 0; width: 100%; left: 0; top: -1.5em; text-align: center;'>".trim($header_html)."</body>";
    };

    if (trim($footer_html) != "") {
      $this->footer_html    = "<body style='position: fixed; margin: 0; padding: 0; width: 100%; left: 0; bottom: -1.5em; text-align: center;'>".trim($footer_html)."</body>";
    };
  }

  function process(&$tree, $data, &$pipeline) {
    $parser = new ParserXHTML();

    if ($this->header_html) {
      $box =& $parser->process($this->header_html, $pipeline);
      $tree->add_child($box);
    };

    if ($this->footer_html) {
      $box =& $parser->process($this->footer_html, $pipeline);
      $tree->add_child($box);
    };
  }
}
?>