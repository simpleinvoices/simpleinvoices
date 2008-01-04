<?php
// $Header: /cvsroot/html2ps/box.generic.php,v 1.58 2006/03/19 09:25:35 Konstantin Exp $

class GenericBox {
  function GenericBox() {
    $this->_left   = 0;
    $this->_top    = 0;

    $this->baseline = 0;
    $this->default_baseline = 0;

    // Generic CSS properties
    // Save CSS property values
    $base_font_size = get_base_font_size();

    // 'color'
    $handler = get_css_handler('color');
    $this->color = $handler->get();

    // 'font-size'
    $this->font_size = units2pt(get_font_size(), $base_font_size);
    
    // 'font-family'
    $this->family = get_font_family();

    // 'font-weight'
    $this->weight = get_font_weight();

    // 'font-style'
    $this->style  = get_font_style();

    // 'text-decoration'
    $handler = get_css_handler('text-decoration');
    $this->decoration = $handler->get();
  }

  function show(&$driver) {
    // If debugging mode is on, draw the box outline
    global $g_config;
    if ($g_config['debugbox']) {
      // Copy the border object of current box
      $driver->setlinewidth(0.1);
      $driver->setrgbcolor(0,0,0);
      $driver->rect($this->get_left(), $this->get_top(), $this->get_width(), -$this->get_height());
      $driver->stroke();
    }

    // Set current text color
    // Note that text color is used not only for text drawing (for example, list item markers 
    // are drawn with text color)
    $this->color->apply($driver);
  }

  function is_block() { return false; }
}
?>