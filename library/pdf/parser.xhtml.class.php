<?php
class ParserXHTML extends Parser {
  function &process($html, &$pipeline, &$media) {
    // Run the XML parser on the XHTML we've prepared
    $dom_tree = TreeBuilder::build($html);

    // Check if parser returned valid document
    if (is_null($dom_tree)) {
      readfile(HTML2PS_DIR.'/templates/cannot_parse.html');
      error_log(sprintf("Cannot parse document: %s", $pipeline->get_base_url()));
      die("HTML2PS Error");
    }

    /**
     * Detect the base URI for this document. 
     * 
     * According to the HTML 4.01 p. 12.4.1:
     * User agents must calculate the base URI according to the following precedences (highest priority to lowest):
     * 
     * 1. The base URI is set by the BASE element.
     * 2. The base URI is given by meta data discovered during a protocol interaction, such as an HTTP header (see [RFC2616]).
     * 3. By default, the base URI is that of the current document. Not all HTML documents have a base URI (e.g., a valid HTML document may appear in an email and may not be designated by a URI). Such HTML documents are considered erroneous if they contain relative URIs and rely on a default base URI.
     */

    /** 
     * Check if BASE element present; use its first occurrence
     */
    $this->_scan_base($dom_tree, $pipeline);

    /**
     * @todo fall back to the protocol metadata
     */

    /**
     * Parse STYLE / LINK nodes containing CSS references and definitions 
     * This should be done here, as the document body may include STYLE node 
     * (this violates HTML standard, but is rather often appears in Web)
     */
    $css =& $pipeline->getCurrentCSS();
    $css->scan_styles($dom_tree, $pipeline);

    if (!is_null($media)) {
      // Setup media size and margins
      $pipeline->get_page_media(1, $media);
      $pipeline->output_driver->update_media($media);
      $pipeline->_setupScales($media);
    };

    $body =& traverse_dom_tree_pdf($dom_tree);
    $box =& create_pdf_box($body, $pipeline);   

    return $box;
  }

  function _scan_base(&$root, &$pipeline) {
    switch ($root->node_type()) {
    case XML_ELEMENT_NODE:
      if ($root->tagname() === 'base') {
        /**
         * See HTML 4.01 p 12.4
         * href - this attribute specifies an absolute URI that acts as the base URI for resolving relative URIs.
         * 
         * At this moment pipeline object have current document URI on the top of the stack;
         * we should replace it with the value of 'href' attribute of the BASE tag
         *
         * To handle (possibly) incorrect values, we use 'guess_url' function; in this case
         * if 'href' attribute contains absolute value (is it SHOULD be), it will be used;
         * if it is missing or is relative, we'll get more of less usable value base on current
         * document URI.
         */
        $new_url = $pipeline->guess_url($root->get_attribute('href'));
        $pipeline->pop_base_url();
        $pipeline->push_base_url($new_url);

        return true;
      };

      // We continue processing here! 
    case XML_DOCUMENT_NODE:
      $child = $root->first_child();
      while ($child) {
        if ($this->_scan_base($child, $pipeline)) { return; };
        $child = $child->next_sibling();
      };

      return false;
    };  

    return false;
  }
}
?>