<?php
class LayoutEngineDefault extends LayoutEngine {
  function process(&$box, &$media, &$driver) {
    // Calculate the size of text boxes
    if (is_null($box->reflow_text($driver))) {
      return null;
    };

    // Explicitly remove any height declarations from the BODY-generated box;
    // BODY should always fill last page completely. Percentage height of the BODY is meaningless 
    // on the paged media.
    $box->_height_constraint = new HCConstraint(null, null, null);

    // As BODY generated box have zero calculated width at the very moment,
    // and we need some box to use as a parameter to _calc_percentage_margins, 
    // we'll create a fake box having with equal to the viewport width.
    $media_box = new BlockBox();
    $media_box->width = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']);

    // Calculate actual margin values 
    $box->_calc_percentage_margins($media_box);

    $box->width = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) - 
      $box->_get_hor_extra();
    $box->_width_constraint = new WCConstant($box->width);

    $box->height = mm2pt($media->height() - $media->margins['top'] - $media->margins['bottom']) -
      $box->_get_vert_extra();

    $box->put_top(mm2pt($media->height() - 
                        $media->margins['top']) - 
                  $box->get_extra_top());

    $box->put_left(mm2pt($media->margins['left']) + 
                   $box->get_extra_left());

   
    $flag = false; 
    $whitespace_flag = false;
    $box->reflow_whitespace($flag, $whitespace_flag);

    $box->pre_reflow_images();

    $viewport = new FlowViewport();
    $viewport->left   = mm2pt($media->margins['left']);
    $viewport->top    = mm2pt($media->height() - $media->margins['top']);
    $viewport->width  = mm2pt($media->width()  - $media->margins['left'] - $media->margins['right']);
    $viewport->height = mm2pt($media->height() - $media->margins['top'] - $media->margins['bottom']);

    $fake_parent = null;
    $context = new FlowContext;
    $context->push_viewport($viewport);

    $box->reflow($fake_parent, $context);

    // Make the top-level box competely fill the last page
    $page_real_height = mm2pt($media->height() - $media->margins['top'] - $media->margins['bottom']);
    
    // Note we cannot have less than 1 page in our doc; max() call
    // is required as we, in general, CAN have the content height strictly equal to 0.
    // In this case wi still render the very first page
    $pages = max(1,ceil($box->get_full_height() / $page_real_height));

    $box->height = $pages * $page_real_height;
    $driver->set_expected_pages($pages);
    $driver->anchors = array();
    $box->reflow_anchors($driver, $driver->anchors);

    /**
     * Flow absolute-positioned boxes;
     * note that we should know the number of expected pages at this moment, unless
     * we will not be able to calculate positions for elements using 'bottom: ...' CSS property
     */
    for ($i=0; $i<count($context->absolute_positioned); $i++) {
      $context->push();
      $context->absolute_positioned[$i]->reflow_absolute($context);
      $context->pop();
    };
         
    // Flow fixed-positioned box
    for ($i=0; $i<count($context->fixed_positioned); $i++) {
      $context->push();
      $context->fixed_positioned[$i]->reflow_fixed($context);
      $context->pop();
    };

    $box->reflow_inline();

    return $context;
  }
}
?>