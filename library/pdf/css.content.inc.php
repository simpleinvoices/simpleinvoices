<?php
// $Header: /cvsroot/html2ps/css.content.inc.php,v 1.8 2007/03/15 18:37:30 Konstantin Exp $

require_once(HTML2PS_DIR.'value.content.php');

/**
 * Handles 'content' CSS property (
 *
 * 'content'
 *  Value:   normal   |   [   <string>   |   <uri>   |   <counter>   |
 *  attr(<identifier>)  | open-quote |  close-quote |  no-open-quote |
 *  no-close-quote ]+ | inherit
 *  Initial:  	normal
 *  Applies to:  	:before and :after pseudo-elements
 *  Inherited:  	no
 *  Percentages:  	N/A
 *  Media:  	all
 *  Computed  value: for  URI  values, the  absolute  URI; for  attr()
 *  values, the resulting string; otherwise as specified
 *
 * This property  is used with the :before  and :after pseudo-elements
 * to  generate  content in  a  document.  Values  have the  following
 * meanings:
 *
 * normal
 *    The pseudo-element is not generated.
 * <string>
 *    Text content (see the section on strings). 
 * <uri>
 *    The value  is a URI that  designates an external  resource. If a
 *    user  agent cannot  support the  resource because  of  the media
 *    types it supports, it must ignore the resource.
 * <counter>
 *    Counters  may   be  specified  with   two  different  functions:
 *    'counter()'   or  'counters()'.  The   former  has   two  forms:
 *    'counter(name)' or 'counter(name, style)'. The generated text is
 *    the value of  the named counter at this  point in the formatting
 *    structure; it is formatted  in the indicated style ('decimal' by
 *    default).   The   latter    function   also   has   two   forms:
 *    'counters(name, string)' or 'counters(name, string, style)'. The
 *    generated text is the value  of all counters with the given name
 *    at  this point  in the  formatting structure,  separated  by the
 *    specified  string. The  counters are  rendered in  the indicated
 *    style  ('decimal'  by default).  See  the  section on  automatic
 *    counters and numbering for more information.
 * open-quote and close-quote
 *    These  values are replaced  by the  appropriate string  from the
 *    'quotes' property.
 * no-open-quote and no-close-quote
 *    Same as 'none', but increments (decrements) the level of nesting
 *    for quotes.
 * attr(X)
 *    This function returns  as a string the value  of attribute X for
 *    the subject of the selector. The string is not parsed by the CSS
 *    processor.  If  the subject  of  the  selector  doesn't have  an
 *    attribute X,  an empty string is  returned. The case-sensitivity
 *    of attribute  names depends on  the document language.  Note. In
 *    CSS 2.1,  it is  not possible to  refer to attribute  values for
 *    other elements than the subject of the selector.
 */
class CSSContent extends CSSPropertyHandler {
  function CSSContent() { 
    $this->CSSPropertyHandler(false, false); 
  }

  function &default_value() { 
    $data =& new ValueContent();
    return $data;
  }

  // CSS 2.1 p 12.2: 
  // Value: [ <string> | <uri> | <counter> | attr(X) | open-quote | close-quote | no-open-quote | no-close-quote ]+ | inherit
  //
  // TODO: process values other than <string>
  //
  function &parse($value) {
    if ($value == 'inherit') {
      return CSS_PROPERTY_INHERIT;
    };

    $value_obj =& ValueContent::parse($value);
    return $value_obj;
  }

  function getPropertyCode() {
    return CSS_CONTENT;
  }

  function getPropertyName() {
    return 'content';
  }
}

CSS::register_css_property(new CSSContent);

?>