<?php
class HTML2PS_XMLUtils {
  function valid_attribute_name($name) {
    // Note that, technically, it is not correct, as XML standard treats as letters 
    // characters other than a-z too.. Nevertheless, this simple variant 
    // will do for XHTML/HTML

    return preg_match("/[a-z_:][a-z0-9._:.]*/i",$name);
  }
}
?>