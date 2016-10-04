<?php
class LayoutEnginePS extends LayoutEngine {
  function process(&$box, &$media, &$driver) {
    // Calculate the size of text boxes
    $box->reflow_text($driver);    

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

    $box->height = mm2pt($media->height() - $media->margins['top'] - $media->margins['bottom']) -
      $box->_get_vert_extra();

    $box->put_top(mm2pt($media->height() - 
                        $media->margins['top']) - 
                  $box->get_extra_top());

    $box->put_left(mm2pt($media->margins['left']) + 
                   $box->get_extra_left());

    $box->to_ps($driver);
  }
};
?>