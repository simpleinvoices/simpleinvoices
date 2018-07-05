<?php
/* Commented out 03/09/2018 by RCR as the refernced class StackingLevel doesn't exist
 * and the class RenderStackingContext is not referenced.
 *
class RenderStackingContext {
  var $_stacking_levels;

  function RenderStackingContext() {
    $this->set_stacking_levels(array());

    $level =  new StackingLevel('in-flow-non-inline');
    $this->add_stacking_level($level);

    $level =  new StackingLevel('in-flow-floats');
    $this->add_stacking_level($level);

    $level =  new StackingLevel('in-flow-inline');
    $this->add_stacking_level($level);
  }

  function get_stacking_levels() {
    return $this->_stacking_levels;
  }

  function set_stacking_levels($levels) {
    $this->_stacking_levels = $levels;
  }
}
*/
