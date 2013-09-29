<?php

require_once(HTML2PS_DIR.'filter.post.positioned.class.php');

class LayoutEngineDefault extends LayoutEngine {
  function process(&$box, &$media, &$driver, &$context) {
    // Calculate the size of text boxes
    if (is_null($box->reflow_text($driver))) {
      error_log("LayoutEngineDefault::process: reflow_text call failed");
      return null;
    };

    // Explicitly remove any height declarations from the BODY-generated box;
    // BODY should always fill last page completely. Percentage height of the BODY is meaningless 
    // on the paged media.
    $box->_height_constraint = new HCConstraint(null, null, null);

    $margin = $box->getCSSProperty(CSS_MARGIN);
    $margin->calcPercentages(mm2pt($media->width() - $media->margins['left'] - $media->margins['right']));
    $box->setCSSProperty(CSS_MARGIN, $margin);

    $box->width = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) - 
      $box->_get_hor_extra();
    $box->setCSSProperty(CSS_WIDTH, new WCConstant($box->width));

    $box->height = mm2pt($media->real_height()) - $box->_get_vert_extra();

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
    $context->push_viewport($viewport);

    $box->reflow($fake_parent, $context);

    // Make the top-level box competely fill the last page
    $page_real_height = mm2pt($media->real_height());
   
    // Note we cannot have less than 1 page in our doc; max() call
    // is required as we, in general, CAN have the content height strictly equal to 0.
    // In this case wi still render the very first page
    $pages = max(1,ceil($box->get_full_height() / $page_real_height));

    /**
     * Set body box height so it will fit the page exactly
     */
    $box->height = $pages * $page_real_height - $box->_get_vert_extra();

    $driver->set_expected_pages($pages);

    /**
     * Flow absolute-positioned boxes;
     * note that we should know the number of expected pages at this moment, unless
     * we will not be able to calculate positions for elements using 'bottom: ...' CSS property
     */
    for ($i=0, $num_positioned = count($context->absolute_positioned); $i < $num_positioned; $i++) {
      $context->push();
      $context->absolute_positioned[$i]->reflow_absolute($context);
      $context->pop();
    };
         
    // Flow fixed-positioned box
    for ($i=0, $num_positioned = count($context->fixed_positioned); $i < $num_positioned; $i++) {
      $context->push();
      $context->fixed_positioned[$i]->reflow_fixed($context);
      $context->pop();
    };

    $box->reflow_inline();

    return true;
  }
}
?>