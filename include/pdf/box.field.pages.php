<?php
/**
 * Handles the '##PAGES##' text field.
 *
 */
class BoxTextFieldPages extends TextBoxString {
  function BoxTextFieldPages() { }

  function from_box(&$box) {
    $field = new BoxTextFieldPages;

    $field->copy_style($box);

    $field->word       = "000";

    $field->encoding   = $box->encoding;
    $field->src_encoding = $box->src_encoding;

    $field->size       = $box->size;
    $field->decoration = $box->decoration;
    $field->family     = $box->family;
    $field->weight     = $box->weight;
    $field->style      = $box->style;

    $field->_left      = $box->_left;
    $field->_top       = $box->_top;
    $field->_width_constraint  = $box->_width_constraint;
    $field->_height_constraint = $box->_height_constraint;
    $field->baseline   = $box->baseline;

    return $field;
  }

  function show(&$viewport) {
    $this->word = sprintf("%d", $viewport->expected_pages);

    $field_width = $this->width;
    $field_left  = $this->_left;

    if ($this->font_size > 0) {
      $value_width = $viewport->stringwidth($this->word, $this->_get_font_name($viewport), $this->encoding, $this->font_size);
      if (is_null($value_width)) {
        return null;
      };
    } else {
      $value_width = 0;
    };
    $this->width  = $value_width;
    $this->_left += ($field_width - $value_width) / 2;

    if (is_null(TextBoxString::show($viewport))) {
      return null;
    };

    $this->width = $field_width;
    $this->_left = $field_left;

    return true;
  }

  function show_fixed(&$viewport) {
    $this->word = sprintf("%d", $viewport->expected_pages);

    $field_width = $this->width;
    $field_left  = $this->_left;

    if ($this->font_size > 0) {
      $value_width = $viewport->stringwidth($this->word, $this->_get_font_name($viewport), $this->encoding, $this->font_size);
      if (is_null($value_width)) {
        return null;
      };
    } else {
      $value_width = 0;
    };
    $this->width  = $value_width;
    $this->_left += ($field_width - $value_width) / 2;

    if (is_null(TextBoxString::show_fixed($viewport))) {
      return null;
    };

    $this->width = $field_width;
    $this->_left = $field_left;

    return true;
  }
}
?>