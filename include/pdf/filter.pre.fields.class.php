<?php
class PreTreeFilterHTML2PSFields extends PreTreeFilter {
  var $filename;
  var $filesize;
  var $_timestamp;

  function PreTreeFilterHTML2PSFields($filename=null, $filesize=null, $timestamp=null) {
    $this->filename  = $filename;
    $this->filesize  = $filesize;

    if (is_null($timestamp)) {
      $this->_timestamp = date("Y-m-d H:s");
    } else {
      $this->_timestamp = $timestamp;
    };
  }

  function process(&$tree, $data, &$pipeline) {
    if (is_a($tree, 'TextBox')) {
      switch ($tree->word) {
      case '##PAGE##':
        $parent =& $tree->parent;
        $field  = BoxTextFieldPageNo::from_box($tree);

        $parent->insertBefore($field, $tree);

        $parent->remove($tree);
        break;
      case '##PAGES##':
        $parent =& $tree->parent;
        $field  = BoxTextFieldPages::from_box($tree);
        $parent->insertBefore($field, $tree);
        $parent->remove($tree);
        break;
      case '##FILENAME##':
        if (is_null($this->filename)) {
          $tree->word = $data->get_uri();
        } else {
          $tree->word = $this->filename;
        };
        break;
      case '##FILESIZE##':
        if (is_null($this->filesize)) {
          $tree->word = strlen($data->get_content());
        } else {
          $tree->word = $this->filesize;
        };
        break;
      case '##TIMESTAMP##':
        $tree->word = $this->_timestamp;
        break;
      };
    } elseif (is_a($tree, 'GenericContainerBox')) {
      for ($i=0; $i<count($tree->content); $i++) {
        $this->process($tree->content[$i], $data, $pipeline);
      };
    };
  }
}
?>