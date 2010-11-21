<?php

class StrategyWidthAbsolutePositioned {
  function StrategyWidthAbsolutePositioned() {
  }

  /**
   * See also  CSS 2.1,  p 10.3.7 Absolutely  positioned, non-replaced
   * elements
   */
  function apply(&$box, &$context) {   
    $containing_block =& $box->_get_containing_block();
    $containing_block_width = $containing_block['right'] - $containing_block['left'];

    $right =& $box->getCSSProperty(CSS_RIGHT);
    $left =& $box->getCSSProperty(CSS_LEFT);
    $wc =& $box->getCSSProperty(CSS_WIDTH);

    // For the purposes of this section and the next, the term "static
    // position" (of  an element) refers, roughly, to  the position an
    // element would have had in the normal flow. More precisely:
    //
    // * The static position for 'left'  is the distance from the left
    //   edge of  the containing  block to the  left margin edge  of a
    //   hypothetical box  that would have  been the first box  of the
    //   element  if its  'position'  property had  been 'static'  and
    //   'float'  had  been  'none'.  The  value is  negative  if  the
    //   hypothetical box is to the left of the containing block.
    //
    // * The  static position  for 'right'  is the  distance  from the
    //   right edge of  the containing block to the  right margin edge
    //   of the same hypothetical box  as above. The value is positive
    //   if  the hypothetical  box is  to the  left of  the containing
    //   block's edge.
    //
    // For  the  purposes  of  calculating the  static  position,  the
    // containing block  of fixed  positioned elements is  the initial
    // containing block  instead of  the viewport, and  all scrollable
    // boxes should be assumed to be scrolled to their origin.
    //
    // @todo: implement
    $static_left = 0;
    $static_right = 0;

    // Calculation   of  the   shrink-to-fit  width   is   similar  to
    // calculating the width of a table cell using the automatic table
    // layout  algorithm. Roughly:  calculate the  preferred  width by
    // formatting the content without  breaking lines other than where
    // explicit line  breaks occur,  and also calculate  the preferred
    // minimum width,  e.g., by trying  all possible line  breaks. CSS
    // 2.1 does not define the exact algorithm. Thirdly, calculate the
    // available  width: this is  found by  solving for  'width' after
    // setting 'left' (in case 1) or 'right' (in case 3) to 0.
    //
    // Then  the  shrink-to-fit  width is:  min(max(preferred  minimum
    // width, available width), preferred width).
    $preferred_minimum_width = $box->get_preferred_minimum_width($context);
    $available_width = $containing_block_width - 
      ($left->isAuto() ? 0 : $left->getPoints($containing_block_width)) - 
      ($right->isAuto() ? 0 : $right->getPoints($containing_block_width)) - 
      $box->_get_hor_extra();
    $preferred_width = $box->get_preferred_width($context);

    $shrink_to_fit_width = min(max($preferred_minimum_width, 
                                   $available_width), 
                               $preferred_width);

    // The  constraint  that  determines  the used  values  for  these
    // elements is:
    // 
    // 'left' + 'margin-left' + 'border-left-width' + 'padding-left' +
    // 'width'    +   'padding-right'    +    'border-right-width'   +
    // 'margin-right' + 'right' + scrollbar  width (if any) = width of
    // containing block

    // If all three of 'left',  'width', and 'right' are 'auto': First
    // set any  'auto' values for 'margin-left'  and 'margin-right' to
    // 0. Then, if the 'direction' property of the containing block is
    // 'ltr' set 'left'  to the static position and  apply rule number
    // three below; otherwise, set  'right' to the static position and
    // apply rule number one below.
    if ($left->isAuto() && $right->isAuto() && $wc->isNull()) {
      // @todo: support 'direction' property for the containing block
      $box->setCSSProperty(CSS_LEFT, ValueLeft::fromString('0'));
    };

    // If  none of  the three  is  'auto': If  both 'margin-left'  and
    // 'margin-right' are  'auto', solve the equation  under the extra
    // constraint that  the two margins get equal  values, unless this
    // would make them  negative, in which case when  direction of the
    // containing   block   is   'ltr'  ('rtl'),   set   'margin-left'
    // ('margin-right')   to  zero   and   solve  for   'margin-right'
    // ('margin-left'). If  one of 'margin-left'  or 'margin-right' is
    // 'auto', solve  the equation for  that value. If the  values are
    // over-constrained,  ignore the  value  for 'left'  (in case  the
    // 'direction'  property  of the  containing  block  is 'rtl')  or
    // 'right'  (in case  'direction'  is 'ltr')  and  solve for  that
    // value.
    if (!$left->isAuto() && !$right->isAuto() && !$wc->isNull()) {
      // @todo: implement
      $box->put_width($wc->apply($box->get_width(), 
                                 $containing_block_width));
    };

    // Otherwise,   set   'auto'    values   for   'margin-left'   and
    // 'margin-right'  to 0,  and pick  the one  of the  following six
    // rules that applies.

    // Case  1 ('left'  and  'width'  are 'auto'  and  'right' is  not
    // 'auto', then the width is shrink-to-fit. Then solve for 'left')
    if ($left->isAuto() && !$right->isAuto() && $wc->isNull()) {
      $box->put_width($shrink_to_fit_width);
    };

    // Case  2 ('left'  and  'right'  are 'auto'  and  'width' is  not
    // 'auto',  then if  the  'direction' property  of the  containing
    // block is 'ltr' set 'left' to the static position, otherwise set
    // 'right'  to the  static  position. Then  solve  for 'left'  (if
    // 'direction is 'rtl') or 'right' (if 'direction' is 'ltr').)
    if ($left->isAuto() && $right->isAuto() && !$wc->isNull()) {
      // @todo: implement 'direction' support
      $box->put_width($wc->apply($box->get_width(), 
                                 $containing_block_width));
    };

    // Case  3 ('width'  and  'right'  are 'auto'  and  'left' is  not
    // 'auto',  then  the width  is  shrink-to-fit  .  Then solve  for
    // 'right')
    if (!$left->isAuto() && $right->isAuto() && $wc->isNull()) {
      $box->put_width($shrink_to_fit_width);
    };

    // Case 4 ('left'  is 'auto', 'width' and 'right'  are not 'auto',
    // then solve for 'left')
    if ($left->isAuto() && !$right->isAuto() && !$wc->isNull()) {
      $box->put_width($wc->apply($box->get_width(), 
                                 $containing_block_width));
    };

    // Case 5 ('width'  is 'auto', 'left' and 'right'  are not 'auto',
    // then solve for 'width')
    if (!$left->isAuto() && !$right->isAuto() && $wc->isNull()) {
      $box->put_width($containing_block_width - 
                      $left->getPoints($containing_block_width) -
                      $right->getPoints($containing_block_width));
    };

    // Case 6 ('right'  is 'auto', 'left' and 'width'  are not 'auto',
    // then solve for 'right')
    if (!$left->isAuto() && $right->isAuto() && !$wc->isNull()) {
      $box->put_width($wc->apply($box->get_width(), 
                                 $containing_block_width));
    };

    /**
     * After this we should remove width constraints or we may encounter problem 
     * in future when we'll try to call get_..._width functions for this box
     *
     * @todo Update the family of get_..._width function so that they would apply constraint
     * using the containing block width, not "real" parent width
     */
    $box->setCSSProperty(CSS_WIDTH, new WCConstant($box->get_width()));
  }
}

?>