<?php
// $Header: /cvsroot/html2ps/output.pdflib.1.6.class.php,v 1.2 2006/11/11 13:43:53 Konstantin Exp $

require_once(HTML2PS_DIR.'output.pdflib.class.php');

class PDFLIBForm {
  var $_name;

  function PDFLIBForm($name /*, $submit_action, $reset_action */) {
    $this->_name          = $name;
  }

  function name() {
    return $this->_name;
  }
}

class OutputDriverPdflib16 extends OutputDriverPdflib {
  function field_multiline_text($x, $y, $w, $h, $value, $name) { 
    $font = $this->_control_font();
    pdf_create_field($this->pdf,
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($name),
                     "textfield",
                     sprintf("currentvalue {%s} defaultvalue {%s} font {%s} fontsize {auto} multiline {true}", 
                             $value,
                             $value,
                             $font));    
  }

  function field_text($x, $y, $w, $h, $value, $name) {
    $font = $this->_control_font();
    pdf_create_field($this->pdf,
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($name),
                     "textfield",
                     sprintf("currentvalue {%s} defaultvalue {%s} font {%s} fontsize {auto}", 
                             $value, 
                             $value,
                             $font));
  }

  function field_password($x, $y, $w, $h, $value, $name) {
    $font = $this->_control_font();
    pdf_create_field($this->pdf,
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($name),
                     "textfield",
                     sprintf("currentvalue {%s} font {%s} fontsize {auto} password {true}", $value, $font));
  }

  function field_pushbutton($x, $y, $w, $h) {
    $font = $this->_control_font();
   
    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn(sprintf("___Button%s",md5(time().rand()))),
                     "pushbutton",
                     sprintf("font {%s} fontsize {auto} caption {%s}", 
                             $font, 
                             " "));
  }

  function field_pushbuttonimage($x, $y, $w, $h, $field_name, $value, $actionURL) {
    $font = $this->_control_font();

    $action = pdf_create_action($this->pdf,
                                "SubmitForm",
                                sprintf("exportmethod {html} url=%s", $actionURL));
    
    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($field_name),
                     "pushbutton",
                     sprintf("action {activate %s} font {%s} fontsize {auto} caption {%s}", 
                             $action, 
                             $font, 
                             " "));
  }

  function field_pushbuttonreset($x, $y, $w, $h) {
    $font = $this->_control_font();

    $action = pdf_create_action($this->pdf,
                                "ResetForm",
                                sprintf(""));
    
    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn(sprintf("___ResetButton%d",$action)),
                     "pushbutton",
                     sprintf("action {activate %s} font {%s} fontsize {auto} caption {%s}", 
                             $action, 
                             $font, 
                             " "));
  }

  function field_pushbuttonsubmit($x, $y, $w, $h, $field_name, $value, $actionURL) {
    $font = $this->_control_font();

    $action = pdf_create_action($this->pdf,
                                "SubmitForm",
                                sprintf("exportmethod {html} url=%s", $actionURL));
    
    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($field_name),
                     "pushbutton",
                     sprintf("action {activate %s} font {%s} fontsize {auto} caption {%s}", 
                             $action, 
                             $font, 
                             " "));
  }

  function field_checkbox($x, $y, $w, $h, $name, $value, $checked) {
    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($name),
                     "checkbox",
                     sprintf("buttonstyle {cross} currentvalue {%s} defaultvalue {%s} itemname {%s}", 
                             $checked ? $value : "Off",
                             $checked ? $value : "Off",
                             $value));    
  }

  function field_radio($x, $y, $w, $h, $groupname, $value, $checked) {
    $fqgn = $this->_fqn($groupname, true);

    if (!isset($this->_radiogroups[$fqgn])) {
      $this->_radiogroups[$fqgn] = pdf_create_fieldgroup($this->pdf, $fqgn, "fieldtype=radiobutton");
    };

    pdf_create_field($this->pdf, 
                     $x, $y, $x + $w, $y - $h,
                     sprintf("%s.%s",$fqgn,$value),
                     "radiobutton",
                     sprintf("buttonstyle {circle} currentvalue {%s} defaultvalue {%s} itemname {%s}", 
                             $checked ? $value : "Off",
                             $checked ? $value : "Off",
                             $value));    
  }

  function field_select($x, $y, $w, $h, $name, $value, $options) { 
    $items_str = "";
    $text_str  = "";
    foreach ($options as $option) {
      $items_str .= sprintf("%s ",$option[0]);
      $text_str  .= sprintf("%s ",$option[1]);
    };

    $font = $this->_control_font();
    pdf_create_field($this->pdf,
                     $x, $y, $x + $w, $y - $h,
                     $this->_fqn($name),
                     "combobox",
                     sprintf("currentvalue {%s} defaultvalue {%s} font {%s} fontsize {auto} itemnamelist {%s} itemtextlist {%s}", 
                             $value,
                             $value,
                             $font,
                             $items_str, 
                             $text_str));
  }

  function new_form($name) {
    $this->_forms[] = new PDFLIBForm($name);

    pdf_create_fieldgroup($this->pdf, $name, "fieldtype=mixed");
  }

  /* private routines */

  function _control_font() {
    return pdf_load_font($this->pdf, "Helvetica", "winansi", "embedding=true subsetting=false");
  }

  function _lastform() {
    if (count($this->_forms) == 0) {
      /**
       * Handle invalid HTML; if we've met an input control outside the form, 
       * generate a new form with random name
       */
      
      $name = sprintf("AnonymousFormObject_%u", md5(rand().time()));

      $this->_forms[] = new PDFLIBForm($name);
      pdf_create_fieldgroup($this->pdf, $name, "fieldtype=mixed");
      
      error_log(sprintf("Anonymous form generated with name %s; check your HTML for validity", 
                        $name));
    };

    return $this->_forms[count($this->_forms)-1];
  }

  function _valid_name($name) {
    if (empty($name)) { return false; };

    return true;
  }

  function _fqn($name, $allowexisting=false) {
    if (!$this->_valid_name($name)) {
      $name = uniqid("AnonymousFormFieldObject_");
      error_log(sprintf("Anonymous field generated with name %s; check your HTML for validity", 
                        $name));
    };

    $lastform = $this->_lastform();
    $fqn = sprintf("%s.%s",
                   $lastform->name(),
                   $name);

    if (array_search($fqn, $this->_field_names) === FALSE) {
      $this->_field_names[] = $fqn;
    } elseif (!$allowexisting) {
      error_log(sprintf("Interactive form '%s' already contains field named '%s'",
                        $lastform->name(),
                        $name));
      $fqn .= md5(rand().time());
    };

    return $fqn;
  }
}
?>