<?php
// $Header: /cvsroot/html2ps/box.list-item.php,v 1.34 2006/09/07 18:38:12 Konstantin Exp $

class ListItemBox extends BlockBox {
  var $size;

  function &create(&$root, &$pipeline) {
    $box = new ListItemBox($root, $pipeline);
    $box->readCSS($pipeline->getCurrentCSSState());

    /**
     * Create text box containing item number
     */
    $css_state =& $pipeline->getCurrentCSSState();
    $css_state->pushState();
    $css_state->setProperty(CSS_COLOR, CSSColor::parse('transparent'));

    $list_style = $css_state->getProperty(CSS_LIST_STYLE);
    $box->str_number_box = TextBox::create(CSSListStyleType::format_number($list_style->type,
                                                                           $css_state->getProperty(CSS_HTML2PS_LIST_COUNTER)).". ", 
                                            'iso-8859-1', 
                                            $pipeline);
    $box->str_number_box->baseline = $box->str_number_box->default_baseline;

    $css_state->popState();

    /**
     * Create nested items
     */
    $box->create_content($root, $pipeline);

    return $box;
  }

  function readCSS(&$state) {
    parent::readCSS($state);
    
    $this->_readCSS($state,
                    array(CSS_LIST_STYLE));

    // Pseudo-CSS properties
    // '-list-counter'

    // increase counter value
    $value = $state->getProperty(CSS_HTML2PS_LIST_COUNTER) + 1;
    $state->setProperty(CSS_HTML2PS_LIST_COUNTER, $value);
    $state->setPropertyOnLevel(CSS_HTML2PS_LIST_COUNTER, CSS_PROPERTY_LEVEL_PARENT, $value);

    // open the marker image if specified
    $list_style = $this->getCSSProperty(CSS_LIST_STYLE);

    if (!$list_style->image->is_default()) {
      $this->marker_image = new ImgBox($list_style->image->_image);
      $state->pushDefaultState();
      $this->marker_image->readCSS($state);
      $state->popState();
      $this->marker_image->_setupSize();
    } else {
      $this->marker_image = null;
    };
  }

  function ListItemBox(&$root, &$pipeline) {
    // Call parent constructor
    $this->BlockBox($root);
  }

  function reflow(&$parent, &$context) {
    $list_style = $this->getCSSProperty(CSS_LIST_STYLE);

    // If list-style-position is inside, we'll need to move marker box inside the 
    // list-item box and offset all content by its size;
    if ($list_style->position === LSP_INSIDE) {
      // Add marker box width to text-indent value
      $this->_additional_text_indent = $this->get_marker_box_width();
    };

    // Procees with normal block box flow algorithm
    BlockBox::reflow($parent, $context);
  }

  function reflow_text(&$driver) {
    if (is_null($this->str_number_box->reflow_text($driver))) {
      return null;
    };

    return GenericContainerBox::reflow_text($driver);
  }
  
  function show(&$viewport) {
    // draw generic block box
    if (is_null(BlockBox::show($viewport))) {
      return null;
    };

    // Draw marker 
    /**
     * Determine the marker box base X coordinate 
     * If possible, the marker box should be drawn immediately to the left of the first word in this 
     * box; this means that marker should be tied to the first text box, not to the left 
     * edge of the list block box
     */
    $child = $this->get_first_data();
    if (is_null($child)) {
      $x = $this->get_left(); 

      $list_style = $this->getCSSProperty(CSS_LIST_STYLE);

      // If list-style-position is inside, we'll need to move marker box inside the 
      // list-item box and offset all content by its size;
      if ($list_style->position === LSP_INSIDE) {
        $x += $this->get_marker_box_width();
      };
    } else {
      $x = $child->get_left();
    };

    // Determine the base Y coordinate of marker box
    $element = $this->get_first_data();

    if ($element) {
      $y = $element->get_top() - $element->default_baseline;
    } else {
      $y = $this->get_top();
    }

    if (!is_null($this->marker_image)) {
      $this->mb_image($viewport, $x, $y);
    } else {
      $list_style = $this->getCSSProperty(CSS_LIST_STYLE);

      switch ($list_style->type) {
      case LST_NONE:
        // No marker at all
        break;
      case LST_DISC:
        $this->mb_disc($viewport, $x, $y);
        break;
      case LST_CIRCLE:
        $this->mb_circle($viewport, $x, $y);
        break;
      case LST_SQUARE:
        $this->mb_square($viewport, $x, $y);
        break;
      default:
        $this->mb_string($viewport, $x, $y);
        break;
      }
    };

    return true;
  }

  function get_marker_box_width() {
    $list_style = $this->getCSSProperty(CSS_LIST_STYLE);
    
    switch ($list_style->type) {
    case LST_NONE:
      // no marker box will be rendered at all
      return 0;
    case LST_DISC:
    case LST_CIRCLE:
    case LST_SQUARE:
      //  simple graphic marker
      $font = $this->getCSSProperty(CSS_FONT);
      return $font->size->getPoints();
    default:
      // string marker. Return the width of the marker text
      return $this->str_number_box->get_full_width();
    };
  }

  function mb_string(&$viewport, $x, $y) {
    $this->str_number_box->put_top($y + $this->str_number_box->default_baseline);
    $this->str_number_box->put_left($x - $this->str_number_box->get_full_width());

    $this->str_number_box->show($viewport);
  }

  function mb_disc(&$viewport, $x, $y) {
    $color = $this->getCSSProperty(CSS_COLOR);
    $color->apply($viewport);

    $font = $this->getCSSProperty(CSS_FONT);
    
    $viewport->circle( $x - $font->size->getPoints()*0.5, $y + $font->size->getPoints()*0.4*HEIGHT_KOEFF, $font->size->getPoints() * BULLET_SIZE_KOEFF);
    $viewport->fill();
  }
  
  function mb_circle(&$viewport, $x, $y) {
    $color = $this->getCSSProperty(CSS_COLOR);
    $color->apply($viewport);

    $viewport->setlinewidth(0.1);

    $font = $this->getCSSProperty(CSS_FONT);
    $viewport->circle( $x - $font->size->getPoints()*0.5, $y + $font->size->getPoints()*0.4*HEIGHT_KOEFF, $font->size->getPoints() * BULLET_SIZE_KOEFF);
    $viewport->stroke();
  }

  function mb_square(&$viewport, $x, $y) {
    $color = $this->getCSSProperty(CSS_COLOR);
    $color->apply($viewport);

    $font = $this->getCSSProperty(CSS_FONT);
    $viewport->rect($x - $font->size->getPoints()*0.512, $y + $font->size->getPoints()*0.3*HEIGHT_KOEFF, $font->size->getPoints() * 0.25, $font->size->getPoints() * 0.25);
    $viewport->fill();
  }

  function mb_image(&$viewport, $x, $y) {
    $font = $this->getCSSProperty(CSS_FONT);

    $imagebox =& $this->marker_image;
    $imagebox->moveto($x - $font->size->getPoints()*0.5 - $imagebox->get_width()/2, 
                      $y + $font->size->getPoints()*0.4*HEIGHT_KOEFF + $imagebox->get_height()/2);
    $imagebox->show($viewport);
  }

  function isBlockLevel() {
    return true;
  }
}

?>