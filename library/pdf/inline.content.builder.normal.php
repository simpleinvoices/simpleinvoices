<?php

require_once(HTML2PS_DIR.'inline.content.builder.php');

class InlineContentBuilderNormal extends InlineContentBuilder {
  function InlineContentBuilderNormal() {
    $this->InlineContentBuilder();
  }

  /**
   * CSS 2.1 p.16.6
   * white-space: normal
   * This value directs user agents to collapse sequences of whitespace, and break lines as necessary to fill line boxes.
   */
  function build(&$box, $text, &$pipeline) {
    $text = $this->remove_leading_linefeeds($text);
    $text = $this->remove_trailing_linefeeds($text);

    $content = $this->collapse_whitespace($text);

    // Whitespace-only text nodes sill result on only one whitespace box
    if (trim($content) === '') {
      $whitespace =& WhitespaceBox::create($pipeline);
      $box->add_child($whitespace);
      return;
    }

    // Add leading whispace box, if content stars with a space
    if (preg_match('/ /u',substr($content,0,1))) {
      $whitespace =& WhitespaceBox::create($pipeline);
      $box->add_child($whitespace);
    }

    $words = $this->break_into_words($content);

    $size = count($words);
    $pos = 0;
    $last_whitespace = substr(strlen($content)-1, 1);
    foreach ($words as $word) {
      $box->process_word($word, $pipeline);
      $pos++;

      $is_last_word = ($pos == $size);
      if (!$is_last_word || $last_whitespace) {
        $whitespace =& WhitespaceBox::create($pipeline);
        $box->add_child($whitespace);
      };
    };
  }
}

?>