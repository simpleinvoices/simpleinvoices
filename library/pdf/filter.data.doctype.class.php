<?php
class DataFilterDoctype extends DataFilter {
  function DataFilterDoctype() { }

  function process(&$data) {
    $html = $data->get_content();

    $xml_declaration = "<\?.*?\?>";
    $doctype         = "<!DOCTYPE.*?>";

    /**
     * DOCTYPE declaration should be at the very beginning of the document 
     * (with the only exception of XML declaration).
     *
     * XML declaration is optional; XML declaration may be surrounded with whitespace
     */

    if (preg_match("#^(?:\s*$xml_declaration\s*)?($doctype)#", $html, $matches)) {
      $doctype_match = $matches[1];
      
      /**
       * remove extra spaces from doctype text; also, DOCTYPE may contain
       * \n and \r character in its whitespace parts. Here, we replace them 
       * with one single space, converting it to the "normalized" form.
       */
      $doctype_match = preg_replace("/\s+/"," ",$doctype_match);


      /**
       * Match doctype agaist standard doctypes
       */
      switch ($doctype_match) {
      case '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">':
      case '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">':
      case '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">':
        $GLOBALS['g_config']['mode'] = 'html';
        return $data;
      case '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">':
      case '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">':
      case '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">':
        $GLOBALS['g_config']['mode'] = 'xhtml';
        return $data;
      };
      
    };

    /**
     * No DOCTYPE found; fall back to quirks mode
     */

    $GLOBALS['g_config']['mode'] = 'quirks';
    return $data;
  }
}
?>