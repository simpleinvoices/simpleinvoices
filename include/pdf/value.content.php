<?php

require_once(HTML2PS_DIR.'value.content.item.php');

class ValueContent {
  var $_items;

  function ValueContent() {
    $this->set_items(array());
  }

  function add_item(&$item) {
    $this->_items[] =& $item;
  }

  function &copy() {
    $copy =& new ValueContent();

    foreach ($this->_items as $item) {
      $copy->add_item($item->copy());
    };

    return $copy;
  }

  function doInherit(&$state) {
    
  }

  function &parse($string) {
    $value =& new ValueContent();

    while ($string !== '') {
      $result = ValueContentItem::parse($string);
      $item =& $result['item'];
      $rest = $result['rest'];

      $string = $rest;

      if (is_null($item)) {
        break;
      };

      $value->add_item($item);
    };

    return $value;
  }

  function render(&$counters) {
    $content = array();
    foreach ($this->_items as $item) {
      $content[] = $item->render($counters);
    };
    return join('', $content);
  }

  function set_items($value) {
    $this->_items = $value;
  }
}

?>