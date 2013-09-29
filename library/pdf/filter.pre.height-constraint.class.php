<?php

/**
 * This is an internal HTML2PS filter; you never need to use it.
 */

class PreTreeFilterHeightConstraint extends PreTreeFilter {
  function process(&$tree, $data, &$pipeline) {
    if (!is_a($tree, 'GenericFormattedBox')) {
      return;
    };

    /**
     * In non-quirks mode, percentage height should be ignored for children of boxes having
     * non-constrained height
     */
    global $g_config;
    if ($g_config['mode'] != 'quirks') {
      if (!is_null($tree->parent)) {
        $parent_hc = $tree->parent->get_height_constraint();
        $hc        = $tree->get_height_constraint();

        if (is_null($parent_hc->constant) &&
            $hc->constant[1]) {
          $hc->constant = null;
          $tree->put_height_constraint($hc);
        };
      };
    };

    /**
     * Set box height to constrained value
     */
    $hc     = $tree->get_height_constraint();
    $height = $tree->get_height();

    $tree->height = $hc->apply($height, $tree);

    /**
     * Proceed to this box children
     */
    if (is_a($tree, 'GenericContainerBox')) {
      for ($i=0, $size = count($tree->content); $i<$size; $i++) {
        $this->process($tree->content[$i], $data, $pipeline);
      };
    };
  }
}
?>