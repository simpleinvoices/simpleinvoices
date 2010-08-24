<?php

class InlineContentBuilderFactory {
  function &get($whitespace) {
    switch ($whitespace) {
    case WHITESPACE_NORMAL:
      require_once(HTML2PS_DIR.'inline.content.builder.normal.php');
      $builder =& new InlineContentBuilderNormal();
      break;
    case WHITESPACE_PRE:
      require_once(HTML2PS_DIR.'inline.content.builder.pre.php');
      $builder =& new InlineContentBuilderPre();
      break;
    case WHITESPACE_NOWRAP:
      require_once(HTML2PS_DIR.'inline.content.builder.nowrap.php');
      $builder =& new InlineContentBuilderNowrap();
      break;
    case WHITESPACE_PRE_WRAP:
      require_once(HTML2PS_DIR.'inline.content.builder.pre.wrap.php');
      $builder =& new InlineContentBuilderPreWrap();
      break;
    case WHITESPACE_PRE_LINE:
      require_once(HTML2PS_DIR.'inline.content.builder.pre.line.php');
      $builder =& new InlineContentBuilderPreLine();
      break;
    default:
      trigger_error('Internal error: unknown whitespace enumeration value', E_USER_ERROR);
    };

    return $builder;
  }
}

?>