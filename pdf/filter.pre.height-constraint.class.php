<?php

/**
 * This is an internal HTML2PS filter; you never need to use it.
 */

class PreTreeFilterHeightConstraint extends PreTreeFilter {
  function process(&$tree, $data, &$pipeline) {
    $tree->height = $tree->_height_constraint->apply($tree->height, $tree);

    if (is_a($tree, 'GenericContainerBox')) {
      for ($i=0; $i<count($tree->content); $i++) {
        $this->process($tree->content[$i], $data, $pipeline);
      };
    };
  }
}
?>