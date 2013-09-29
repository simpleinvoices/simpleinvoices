<?php
// $Header: /cvsroot/html2ps/box.table.section.php,v 1.14 2006/10/28 12:24:16 Konstantin Exp $

class TableSectionBox extends GenericContainerBox {
  function &create(&$root, &$pipeline) {
    $state =& $pipeline->getCurrentCSSState();
    $box =& new TableSectionBox();
    $box->readCSS($state);

    // Automatically create at least one table row
    $row = new TableRowBox();
    $row->readCSS($state);
    $box->add_child($row);

    // Parse table contents
    $child = $root->first_child();
    while ($child) {
      $child_box =& create_pdf_box($child, $pipeline);
      $box->add_child($child_box);
      $child = $child->next_sibling();
    };

    return $box;
  }
  
  function TableSectionBox() {
    $this->GenericContainerBox();
  }

  // Overrides default 'add_child' in GenericFormattedBox
  function add_child(&$item) {
    // Check if we're trying to add table cell to current table directly, without any table-rows
    if ($item->isCell()) {
      // Add cell to the last row
      $last_row =& $this->content[count($this->content)-1];
      $last_row->add_child($item);

    } elseif ($item->isTableRow()) {
      // If previous row is empty, remove it (get rid of automatically generated table row in constructor)
      if (count($this->content) > 0) {
        if (count($this->content[count($this->content)-1]->content) == 0) {
          array_pop($this->content);
        }
      };
      
      // Just add passed row 
      $this->content[] =& $item;
    };
  }

  function isTableSection() {
    return true;
  }
}
?>