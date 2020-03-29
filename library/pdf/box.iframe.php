<?php
// $Header: /cvsroot/html2ps/box.iframe.php,v 1.14 2006/12/18 19:44:21 Konstantin Exp $

class IFrameBox extends InlineBlockBox {
  function &create(&$root, &$pipeline) {
    $box =& new IFrameBox($root, $pipeline);
    $box->readCSS($pipeline->getCurrentCSSState());
    return $box;
  }

  // Note that IFRAME width is NOT determined by its content, thus we need to override 'get_min_width' and
  // 'get_max_width'; they should return the constrained frame width.
  function get_min_width(&$context) { 
    return $this->get_max_width($context);
  } 

  function get_max_width(&$context) {
    return $this->get_width();
  }

  function IFrameBox(&$root, $pipeline) {
    $this->InlineBlockBox();

    // If NO src attribute specified, just return.
    if (!$root->has_attribute('src') || 
        trim($root->get_attribute('src')) == '') { 
      return; 
    };

    // Determine the fullly qualified URL of the frame content
    $src = $root->get_attribute('src');
    $url = $pipeline->guess_url($src);
    $data = $pipeline->fetch($url);

    /**
     * If framed page could not be fetched return immediately
     */
    if (is_null($data)) { return; };

    /**
     * Render only iframes containing HTML only
     *
     * Note that content-type header may contain additional information after the ';' sign
     */
    $content_type = $data->get_additional_data('Content-Type');
    $content_type_array = explode(';', $content_type);
    if ($content_type_array[0] != "text/html") { return; };

    $html = $data->get_content();
      
    // Remove control symbols if any
    $html = preg_replace('/[\x00-\x07]/', "", $html);
    $converter = Converter::create();
    $html = $converter->to_utf8($html, $data->detect_encoding());
    $html = html2xhtml($html);
    $tree = TreeBuilder::build($html);
        
    // Save current stylesheet, as each frame may load its own stylesheets
    //
    $pipeline->pushCSS();
    $css =& $pipeline->getCurrentCSS();
    $css->scan_styles($tree, $pipeline);

    $frame_root = traverse_dom_tree_pdf($tree);
    $box_child =& create_pdf_box($frame_root, $pipeline);
    $this->add_child($box_child);

    // Restore old stylesheet
    //
    $pipeline->popCSS();

    $pipeline->pop_base_url();
  }
}

?>