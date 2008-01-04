<?php
// $Header: /cvsroot/html2ps/box.table.section.php,v 1.11 2006/03/19 09:25:35 Konstantin Exp $

class TableSectionBox extends GenericContainerBox {
  function &create(&$root, &$pipeline) {
    $box =& new TableSectionBox($root, $pipeline);
    return $box;
  }
  
  function TableSectionBox(&$root, &$pipeline) {
    $this->GenericContainerBox();

    // Automatically create at least one table row
    if (count($this->content) == 0) {
      $this->content[] =& new TableRowBox($root);
    }

    // Parse table contents
    $child = $root->first_child();
    while ($child) {
      $child_box =& create_pdf_box($child, $pipeline);
      $this->add_child($child_box);
      $child = $child->next_sibling();
    };
  }

  // Overrides default 'add_child' in GenericFormattedBox
  function add_child(&$item) {
    // Check if we're trying to add table cell to current table directly, without any table-rows
    if (!is_a($item,"TableRowBox")) {
      // Add cell to the last row
      $last_row =& $this->content[count($this->content)-1];
      $last_row->add_child($item);
    } else {
      // If previous row is empty, remove it (get rid of automatically generated table row in constructor)
      if (count($this->content[count($this->content)-1]->content) == 0) {
        array_pop($this->content);
      }
      
      // Just add passed row 
      $this->content[] =& $item;
    };
  }
}
?>