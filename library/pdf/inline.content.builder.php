<?php

require_once(HTML2PS_DIR.'error.php');

/**
 * See CSS 2.1 16.6.1 The 'white-space' processing model
 */
class InlineContentBuilder {
  function InlineContentBuilder() {
  }

  function add_line_break(&$box, &$pipeline) {
    $break_box =& new BRBox();
    $break_box->readCSS($pipeline->getCurrentCSSState());
    $box->add_child($break_box);
  }

  function build(&$box, $text, &$pipeline) {
    error_no_method('build', get_class($this));
  }

  function break_into_lines($content) {
    return preg_split('/[\r\n]/u', $content);
  }

  function break_into_words($content) {
    return preg_split('/ /u', $content);
  }

  function collapse_whitespace($content) {
    return preg_replace('/[\r\n\t ]+/u', ' ', $content);
  }

  function remove_leading_linefeeds($content) {
    return preg_replace('/^ *[\r\n]+/u', '', $content);
  }

  function remove_trailing_linefeeds($content) {
    return preg_replace('/[\r\n]+$/u', '', $content);
  }
}

?>