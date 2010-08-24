<?php

require_once(HTML2PS_DIR.'inline.content.builder.php');

class InlineContentBuilderNowrap extends InlineContentBuilder {
  function InlineContentBuilderNowrap() {
    $this->InlineContentBuilder();
  }

  /**
   * CSS 2.1, p 16.6
   * white-space: nowrap
   * This value collapses whitespace as for 'normal', but suppresses line breaks within text
   */
  function build(&$box, $raw_content, &$pipeline) {
    $raw_content = $this->remove_leading_linefeeds($raw_content);
    $raw_content = $this->remove_trailing_linefeeds($raw_content);
    $box->process_word($this->collapse_whitespace($raw_content), $pipeline);
  }
}

?>