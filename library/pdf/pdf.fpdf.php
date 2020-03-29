<?php
/*******************************************************************************
 * Software: FPDF                                                               *
 * Version:  1.53                                                               *
 * Date:     2004-12-31                                                         *
 * Author:   Olivier PLATHEY                                                    *
 * License:  Freeware                                                           *
 *                                                                              *
 * You may use, modify and redistribute this software as you wish.              *
 *******************************************************************************/

/**
 * Heavily patched to adapt to the HTML2PS/HTML2PDF script requirements by 
 * Konstantin Bournayev (bkon@bkon.ru)
 *
 * Note: this FPDF variant assumes that magic_quotes_runtime are disabled;
 * the reason is that HTML2PS/PDF explicitly disables them during pipeline 
 * processing, thus all calls to FPDF API are "safe"
 */

if (!class_exists('FPDF')) {
  define('FPDF_VERSION','1.53');

  /**
   * FPDF state flags
   */
  define('FPDF_STATE_UNINITIALIZED',    0);
  define('FPDF_STATE_DOCUMENT_STARTED', 1);
  define('FPDF_STATE_PAGE_STARTED',     2);
  define('FPDF_STATE_COMPLETED',        3);

  /**
   * See PDF Reference 1.6 p.664 for explanation of flags specific to submit form action
   */
  define('PDF_SUBMIT_FORM_HTML',        1 << 2); // 1 - HTML, 0 - FDF
  define('PDF_SUBMIT_FORM_COORDINATES', 1 << 4);

  /**
   * See PDF Reference 1.6 p.656 for explanation of flags specific to choice fields
   */
  define('PDF_FIELD_CHOICE_COMBO', 1 << 17);

  /**
   * See PDF Reference 1.6 p.573 for explanation of flag specific to annotations
   */
  define('PDF_ANNOTATION_INVISIBLE',    1 << 0);
  define('PDF_ANNOTATION_HIDDEN',       1 << 1);
  define('PDF_ANNOTATION_PRINTABLE',    1 << 2);
  define('PDF_ANNOTATION_NOZOOM',       1 << 3);
  define('PDF_ANNOTATION_NOROTATE',     1 << 4);
  define('PDF_ANNOTATION_NOVIEW',       1 << 5);
  define('PDF_ANNOTATION_READONLY',     1 << 6);
  define('PDF_ANNOTATION_LOCKED',       1 << 7);
  define('PDF_ANNOTATION_TOGGLENOVIEW', 1 << 8);

  /**
   * See PDF Reference 1.6 p.653 for explanation of flags specific to text fields
   */
  define('PDF_FIELD_TEXT_MULTILINE',1 << 12); 
  define('PDF_FIELD_TEXT_PASSWORD', 1 << 13); 
  define('PDF_FIELD_TEXT_FILE',     1 << 20);

  /**
   * See PDF Reference 1.6 p.663 for examplanation of flags specific to for submit actions
   */
  define("PDF_FORM_SUBMIT_EXCLUDE", 1 << 0);
  define("PDF_FORM_SUBMIT_NOVALUE", 1 << 1);
  define("PDF_FORM_SUBMIT_EFORMAT", 1 << 2);
  define("PDF_FORM_SUBMIT_GET",     1 << 3);
  
  class PDFIndirectObject {
    var $object_id;
    var $generation_id;
    
    function get_object_id() { 
      return $this->object_id;
    }

    function get_generation_id() {
      return $this->generation_id;
    }

    /**
     * Outputs the PDF indirect object to PDF file.
     * 
     * To pervent infinite loop on circular references, this method checks 
     * if current object have been already written to the file.
     *
     * Note that, in general, nested objects should be written to PDF file
     * here too; this task is accomplished by calling _out_nested method,
     * which should be overridden by children classes.
     *
     * @param FPDF $handler PDF file wrapper (FPDF object)
     * 
     * @final
     *
     * @see FPDF::is_object_written
     * @see PDFIndirectObject::_out_nested
     */
    function out(&$handler) {
      if (!$handler->is_object_written($this->get_object_id())) {
        $handler->offsets[$this->get_object_id()] = strlen($handler->buffer);
        $handler->_out($handler->_indirect_object($this));

        $this->_out_nested($handler);
      };
    }

    /**
     * Writes all nested objects to the PDF file. Should be overridden by 
     * PDFIndirectObject descendants.
     *
     * @param FPDF $handler PDF file wrapper (FPDF object)
     *
     * @see PDFIndirectObject::out
     */
    function _out_nested(&$handler) {
      return true;
    }

    function PDFIndirectObject(&$handler,
                               $object_id, 
                               $generation_id) {
      $this->object_id = $object_id;
      $this->generation_id = $generation_id;
    }

    function pdf(&$handler) {
      return $handler->_dictionary($this->_dict($handler));
    }

    function _dict() {
      return array();
    }
  }

  class PDFCMap extends PDFIndirectObject {
    var $_content;

    function PDFCMap($mapping, &$handler, $object_id, $generation_id) {
      $this->PDFIndirectObject($handler,
                               $object_id, 
                               $generation_id);

      $num_chars = count($mapping);

      $chars     = "";
      foreach ($mapping as $code => $utf) {
        $chars .= sprintf("<%02X> <%04X> \n", $code, $utf);
      };

      $this->_content = <<<EOF
/CIDInit /ProcSet findresource begin
12 dict begin
begincmap
CIDSystemInfo
<< /Registry (Adobe)
/Ordering (UCS) /Supplement 0 >> def
/CMapName /Adobe-Identity-UCS def
/CMapType 2 def
1 begincodespacerange
<0000> <FFFF>
endcodespacerange
${num_chars} beginbfchar
${chars}
endbfchar
endcmap CMapName currentdict /CMap defineresource pop end end
EOF
;
    }

    function pdf(&$handler) {
      $dict_content   = array(
                              'Length'   => strlen($this->_content)
                              );

      $content = $handler->_dictionary($dict_content);
      $content .= "\n";
      $content .= $handler->_stream($this->_content);

      return $content;
    }
  }

  class PDFPage extends PDFIndirectObject {
    var $annotations;
    var $_width;
    var $_height;

    function PDFPage(&$handler, 
                     $width, 
                     $height,
                     $object_id, 
                     $generation_id) {
      $this->PDFIndirectObject($handler, 
                               $object_id, 
                               $generation_id);

      $this->set_width($width);
      $this->set_height($height);
    }

    function add_annotation(&$annotation) {
      $this->annotations[] =& $annotation;
    }

    function _annotations(&$handler) {
      return $handler->_reference_array($this->annotations);
    }

    function get_height() {
      return $this->_height;
    }
    
    function get_width() {
      return $this->_width;
    }

    function set_height($height) {
      $this->_height = $height;
    }

    function set_width($width) {
      $this->_width = $width;
    }
  }

  class PDFAppearanceStream extends PDFIndirectObject {
    var $_content;

    function PDFAppearanceStream(&$handler, 
                                 $object_id, 
                                 $generation_id,
                                 $content) {
      $this->PDFIndirectObject($handler, 
                               $object_id, 
                               $generation_id);

      $this->_content = $content;
    }

    function pdf(&$handler) {
      $dict_content   = array(
                              'Type'     => "/XObject",
                              'Subtype'  => "/Form",
                              'FormType' => "1",
                              'BBox'     => "[0 0 100 100]",
                              'Matrix'   => "[1 0 0 1 0 0]",
                              'Resources'=> "2 0 R",
                              'Length'   => strlen($this->_content)
                              );

      $content = $handler->_dictionary($dict_content);
      $content .= "\n";
      $content .= $handler->_stream($this->_content);

      return $content;
    }
  }

  class PDFAnnotation extends PDFIndirectObject {
    function PDFAnnotation(&$handler,
                           $object_id, 
                           $generation_id) {
      $this->PDFIndirectObject($handler,
                               $object_id, 
                               $generation_id);
    }

    function _dict(&$handler) {
      return array_merge(parent::_dict($handler), 
                         array("Type" => $handler->_name("Annot")));
    }
  }

  class PDFRect {
    var $x;
    var $y;
    var $w;
    var $h;

    function PDFRect($x,$y,$w,$h) {
      $this->x = $x;
      $this->y = $y;
      $this->w = $w;
      $this->h = $h;
    }

    function left(&$handler) {
      return $handler->x_coord($this->x);
    }

    function right(&$handler) {
      return $handler->x_coord($this->x+$this->w);
    }

    function top(&$handler) {
      return $handler->y_coord($this->y);
    }

    function bottom(&$handler) {
      return $handler->y_coord($this->y+$this->h);
    }

    function pdf(&$handler) {
      return $handler->_array(sprintf("%.2f %.2f %.2f %.2f",
                                      $this->left($handler),
                                      $this->top($handler),
                                      $this->right($handler),
                                      $this->bottom($handler)));
    }
  }

  class PDFAnnotationExternalLink extends PDFAnnotation {
    var $rect;
    var $link;

    function PDFAnnotationExternalLink(&$handler,
                                       $object_id, 
                                       $generation_id,
                                       $rect,
                                       $link) {
      $this->PDFAnnotation($handler, 
                           $object_id,
                           $generation_id);

      $this->rect = $rect;
      $this->link = $link;
    }

    function _dict(&$handler) {
      return array_merge(parent::_dict($handler),
                         array(
                               'Subtype' => "/Link",
                               'Rect'    => $this->rect->pdf($handler),
                               'Border'  => "[0 0 0]",
                               'A'       => "<</S /URI /URI ".$handler->_textstring($this->link).">>"
                               ));
    }
  }

  class PDFAnnotationInternalLink extends PDFAnnotation {
    var $rect;
    var $link;

    function PDFAnnotationInternalLink(&$handler,
                                       $object_id, 
                                       $generation_id,
                                       $rect,
                                       $link) {
      $this->PDFAnnotation($handler, 
                           $object_id, 
                           $generation_id);

      $this->rect = $rect;
      $this->link = $link;
    }

    function pdf(&$handler) {
      if ($handler->DefOrientation=='P') {
        $wPt=$handler->fwPt;
        $hPt=$handler->fhPt;
      } else {
        $wPt=$handler->fhPt;
        $hPt=$handler->fwPt;
      };
      $l = $handler->links[$this->link];
      $h = $hPt;

      /**
       * Sometimes hyperlinks may refer to pages NOT present in PDF document
       * Example: a very long frame content; it it trimmed to one page, as 
       * framesets newer take more than one frame. A link targe which should be rendered
       * on third page without frames will be never rendered at all. 
       * 
       * In this case we should disable link at all to prevent error from appearing
       */

      if (!isset($handler->_pages[$l[0]-1])) {
        return "";
      }

      $content = $handler->_dictionary(array(
                                             'Type'    => "/Annot",
                                             'Subtype' => "/Link",
                                             'Rect'    => $this->rect->pdf($handler),
                                             'Border'  => "[0 0 0]",
                                             'Dest'    => sprintf("[%s /XYZ 0 %.2f null]",
                                                                  $handler->_reference($handler->_pages[$l[0]-1]),
                                                                  $h-$l[1]*$handler->k)
                                             ));
      return $content;
    }
  }

  class PDFAnnotationWidget extends PDFAnnotation {
    var $_rect;

    function PDFAnnotationWidget(&$handler,
                                 $object_id, 
                                 $generation_id,
                                 $rect) {
      $this->PDFAnnotation($handler, 
                           $object_id, 
                           $generation_id);

      $this->_rect = $rect;
    }

    function _dict(&$handler) {
      return array_merge(parent::_dict($handler),
                         array("Subtype" => $handler->_name("Widget"),
                               'Rect'    => $this->_rect->pdf($handler)));
    }
  }

  /**
   * Generic PDF Form
   */
  class PDFFieldGroup extends PDFIndirectObject {
    var $_kids;
    var $_group_name;

    function PDFFieldGroup(&$handler, 
                           $object_id, 
                           $generation_id,
                           $group_name) {
      $this->PDFIndirectObject($handler, 
                               $object_id, 
                               $generation_id);

      /** 
       * Generate default group name, if needed 
       */
      if (is_null($group_name) || $group_name == "") {
        $group_name = sprintf("FieldGroup%d", $this->get_object_id());
      };
      $this->_group_name = $group_name;

      $this->_kids = array();
    }

    function _check_field_name($field) {
      /**
       * Check if field name is empty
       */
      if (trim($field->get_field_name()) == "") {
        error_log(sprintf("Found form field with empty name"));
        return false;
      };

      /**
       * Check if field name is unique inside this form! If we will not do it, 
       * some widgets may become inactive (ignored by PDF Reader)
       */
      foreach ($this->_kids as $kid) {
        if ($kid->get_field_name() == $field->get_field_name()) {
          error_log(sprintf("Interactive form '%s' already contains field named '%s'",
                            $this->_group_name,
                            $kid->get_field_name()));
          return false;
        }
      };

      return true;
    }

    function add_field(&$field) {
      if (!$this->_check_field_name($field)) { 
        /**
         * Field name is not unique; replace it with automatically-generated one
         */
        $field->set_field_name(sprintf("%s_FieldObject%d",
                                       $field->get_field_name(),
                                       $field->get_object_id()));
      };

      $this->_kids[] =& $field;
      $field->set_parent($this);
    }

    function _dict(&$handler) {
      return array_merge(parent::_dict($handler),
                         array("Kids" => $handler->_reference_array($this->_kids),
                               "T"    => $handler->_textstring($this->_group_name)));
      return $content;
    }

    function _out_nested(&$handler) {
      parent::_out_nested($handler);

      foreach ($this->_kids as $field) {
        $field->out($handler);
      }
    }
  }

  /**
   * Generic superclass for all PDF interactive field widgets
   */
  class PDFField extends PDFAnnotationWidget {
    /**
     * @var string Partial field name (see PDF Specification 1.6 p.638 for explanation on "partial" and 
     * "fully qualified" field names
     * @access private
     */
    var $_field_name;

    /**
     * @var PDFFieldGroup REference to a containing form object
     * @access private
     */
    var $_parent;

    function PDFField(&$handler,
                      $object_id, 
                      $generation_id, 
                      $rect, 
                      $field_name) {
      $this->PDFAnnotationWidget($handler, 
                                 $object_id, 
                                 $generation_id, 
                                 $rect);

      /**
       * Generate default field name, if needed 
       * @TODO: validate field_name contents
       */
      if (is_null($field_name) || $field_name == "") {
        $field_name = sprintf("FieldObject%d", $this->get_object_id());
      };

      $this->_field_name = $field_name;
    }

    function get_field_name() {
      if ($this->_field_name) {
        return $this->_field_name;
      } else {
        return sprintf("FormObject%d", $this->get_object_id());
      };
    }

    function _dict(&$handler) {
      return array_merge(parent::_dict($handler),
                         array("Parent" => $handler->_reference($this->_parent),
                               "T"      => $handler->_textstring($this->get_field_name()),
                               'F'      => PDF_ANNOTATION_PRINTABLE));
    }

    function pdf(&$handler) {
      return $handler->_dictionary($this->_dict($handler));
    }

    function set_field_name($value) {
      $this->_field_name = $value;
    }

    function set_parent(&$form) {
      $this->_parent =& $form;
    }

    function get_parent() {
      return $this->_parent;
    }
  }

  /**
   * Checkbox interactive form widget
   */
  class PDFFieldCheckBox extends PDFField {
    var $_value;
    var $_appearance_on;
    var $_appearance_off;
    var $_checked;

    function PDFFieldCheckBox(&$handler,
                              $object_id, 
                              $generation_id,
                              $rect, 
                              $field_name, 
                              $value,
                              $checked) {
      $this->PDFField($handler,
                      $object_id, 
                      $generation_id,
                      $rect, 
                      $field_name);

      $this->_value = $value;
      $this->_checked = $checked;

      $this->_appearance_on = new PDFAppearanceStream($handler,
                                                      $handler->_generate_new_object_number(), 
                                                      $generation_id,
                                                      "Q 0 0 1 rg BT /F1 10 Tf 0 0 Td (8) Tj ET q");
      
      $this->_appearance_off = new PDFAppearanceStream($handler,
                                                       $handler->_generate_new_object_number(), 
                                                       $generation_id, 
                                                       "Q 0 0 1 rg BT /F1 10 Tf 0 0 Td (8) Tj ET q");
    }

    function _dict(&$handler) {
      return array_merge(parent::_dict($handler),
                         array(
                               'FT'      => '/Btn',
                               'Ff'      => sprintf("%d", 0),
                               'TU'      => "<FEFF>",
                               'MK'      => "<< /CA (3) >>",
                               'DV'      => $this->_checked ? $handler->_name($this->_value) : "/Off",
                               'V'       => $this->_checked ? $handler->_name($this->_value) : "/Off",
                               'AP'      => sprintf("<< /N << /%s %s /Off %s >> >>",
                                                    $this->_value,
                                                    $handler->_reference($this->_appearance_on),
                                                    $handler->_reference($this->_appearance_off))
                               )
                         );
    }

    function _out_nested(&$handler) {
      parent::_out_nested($handler);

      $this->_appearance_on->out($handler);
      $this->_appearance_off->out($handler);
    }
  }

  class PDFFieldPushButton extends PDFField {
    var $_appearance;
    var $fontindex;
    var $fontsize;

    function _out_nested(&$handler) {
      parent::_out_nested($handler);

      $this->_appearance->out($handler);
    }

    function PDFFieldPushButton(&$handler,
                                $object_id, 
                                $generation_id,
                                $rect, 
                                $fontindex, 
                                $fontsize) {
      $this->PDFField($handler,
                      $object_id, 
                      $generation_id,
                      $rect,
                      null);
      $this->fontindex = $fontindex;
      $this->fontsize  = $fontsize;

      $this->_appearance = new PDFAppearanceStream($handler,
                                                   $handler->_generate_new_object_number(), 
                                                   $generation_id, 
                                                   "Q 0 0 1 rg BT /F1 10 Tf 0 0 Td (8) Tj ET q");
    }

    function _action(&$handler) {
      return "<< >>";
    }
    
    function _dict(&$handler) {
      return array_merge(parent::_dict($handler),
                         array(
                               'FT'      => '/Btn',
                               'Ff'      => sprintf("%d", 1 << 16),
                               'TU'      => "<FEFF>",
                               'DR'      => "2 0 R",
                               'DA'      => sprintf("(0 0 0 rg /F%d %.2f Tf)", 
                                                    $this->fontindex,
                                                    $this->fontsize),
                               'AP'      => "<< /N ".$handler->_reference($this->_appearance)." >>",
                               'AA'      => $this->_action($handler)
                               ));
    }
  }

  class PDFFieldPushButtonImage extends PDFFieldPushButton {
    var $_link;

    function PDFFieldPushButtonImage(&$handler,
                                      $object_id, 
                                      $generation_id,
                                      $rect, 
                                      $fontindex, 
                                      $fontsize, 
                                      $field_name,
                                      $value, 
                                      $link) {
      $this->PDFFieldPushButton($handler,
                                $object_id, 
                                $generation_id, 
                                $rect, 
                                $fontindex, 
                                $fontsize);
      
      $this->_link  = $link;
      $this->set_field_name($field_name);
    }

    function _action(&$handler) {
      $action = $handler->_dictionary(array(
                                            'S'     => "/SubmitForm",
                                            'F'     => $handler->_textstring($this->_link),
                                            'Fields'=> $handler->_reference_array(array($this->get_parent())),
                                            'Flags' => PDF_SUBMIT_FORM_HTML | PDF_SUBMIT_FORM_COORDINATES
                                            )
                                      );
      return $handler->_dictionary(array('U' => $action));
    }
  }

  class PDFFieldPushButtonSubmit extends PDFFieldPushButton {
    var $_link;
    var $_caption;

    function PDFFieldPushButtonSubmit(&$handler,
                                      $object_id, 
                                      $generation_id,
                                      $rect, 
                                      $fontindex, 
                                      $fontsize, 
                                      $field_name,
                                      $value, 
                                      $link) {
      $this->PDFFieldPushButton($handler,
                                $object_id, 
                                $generation_id, 
                                $rect, 
                                $fontindex, 
                                $fontsize);
      
      $this->_link    = $link;
      $this->_caption = $value;
      $this->set_field_name($field_name);
    }

    function _action(&$handler) {
      $action = $handler->_dictionary(array(
                                            'S'     => "/SubmitForm",
                                            'F'     => $handler->_textstring($this->_link),
                                            'Fields'=> $handler->_reference_array(array($this->get_parent())),
                                            'Flags' => PDF_SUBMIT_FORM_HTML
                                            )
                                      );
      return $handler->_dictionary(array('U' => $action));
    }
  }

  class PDFFieldPushButtonReset extends PDFFieldPushButton {
    function PDFFieldPushButtonReset(&$handler,
                                     $object_id, 
                                     $generation_id,
                                     $rect, 
                                     $fontindex, 
                                     $fontsize) {
      $this->PDFFieldPushButton($handler,
                                $object_id, 
                                $generation_id,
                                $rect, 
                                $fontindex, 
                                $fontsize);
    }

    function _action(&$handler) {
      $action = $handler->_dictionary(array('S' => "/ResetForm"));
      return $handler->_dictionary(array('U' => $action));
    }
  }

  /**
   * Radio button inside the group.
   * 
   * Note that radio button is not a field itself; only a group of radio buttons
   * should have name.
   */
  class PDFFieldRadio extends PDFAnnotationWidget {
    /**
     * @var PDFFieldRadioGroup reference to a radio button group
     * @access private
     */
    var $_parent;

    /**
     * @var String value of this radio button
     * @access private
     */
    var $_value;

    var $_appearance_on;
    var $_appearance_off;

    function PDFFieldRadio(&$handler,
                           $object_id, 
                           $generation_id,
                           $rect, 
                           $value) {
      $this->PDFAnnotationWidget($handler,
                                 $object_id, 
                                 $generation_id,
                                 $rect);
      
      $this->_value = $value;

      $this->_appearance_on = new PDFAppearanceStream($handler,
                                                      $handler->_generate_new_object_number(), 
                                                      $generation_id, 
                                                      "Q 0 0 1 rg BT /F1 10 Tf 0 0 Td (8) Tj ET q");

      $this->_appearance_off = new PDFAppearanceStream($handler,
                                                       $handler->_generate_new_object_number(), 
                                                       $generation_id, 
                                                       "Q 0 0 1 rg BT /F1 10 Tf 0 0 Td (8) Tj ET q");
    }

    function _dict(&$handler) {
      return array_merge(parent::_dict($handler),
                         array(
                               'MK'      => "<< /CA (l) >>",
                               'Parent'  => $handler->_reference($this->_parent),
                               'AP'      => sprintf("<< /N << /%s %s /Off %s >> >>",
                                                    $this->_value,
                                                    $handler->_reference($this->_appearance_on),
                                                    $handler->_reference($this->_appearance_off))
                               ));
    }

    function _out_nested(&$handler) {
      parent::_out_nested($handler);

      $this->_appearance_on->out($handler);
      $this->_appearance_off->out($handler);
    }

    /**
     * Set a reference to the radio button group containing this group
     *
     * @param PDFFieldRadioGroup $parent reference to a group object
     */
    function set_parent(&$parent) {
      $this->_parent =& $parent;
    }
  }

  /**
   * Create new group of radio buttons
   */
  class PDFFieldRadioGroup extends PDFFieldGroup {
    var $_parent;
    var $_checked;

    function _dict($handler) {
      return array_merge(parent::_dict($handler),
                         array(
                               'DV'      => $this->_checked ? $handler->_name($this->_checked) : "/Off",
                               'V'       => $this->_checked ? $handler->_name($this->_checked) : "/Off",
                               "FT"      => $handler->_name('Btn'),
                               "Ff"      => sprintf("%d", 1 << 15),
                               "Parent"  => $handler->_reference($this->_parent)
                               ));
    }

    function _check_field_name($field) {
      /**
       * As radio buttons always have same field name, no checking should be made here
       */

      return true;
    }
    
    function PDFFieldRadioGroup(&$handler,
                                $object_id,
                                $generation_id, 
                                $group_name) {
      $this->PDFFieldGroup($handler,
                           $object_id, 
                           $generation_id,
                           $group_name);

      $this->_checked = null;
    }

    /**
     * @return String name of the radio group
     */
    function get_field_name() {
      return $this->_group_name;
    }

    function set_checked($value) {
      $this->_checked = $value;
    }

    function set_parent(&$parent) {
      $this->_parent =& $parent;
    }
  }

  class PDFFieldSelect extends PDFField {
    var $_options;
    var $_value;

    function _dict(&$handler) {
      $options = array();
      foreach ($this->_options as $arr) {       
        $options[] = $handler->_array(sprintf("%s %s", 
                                              $handler->_textstring($arr[0]),
                                              $handler->_textstring($arr[1])));
      };

      $options_str = $handler->_array(implode(" ",$options));

      return array_merge(parent::_dict($handler),
                         array('FT'      => '/Ch',
                               'Ff'      => PDF_FIELD_CHOICE_COMBO,
                               'V'       => $handler->_textstring($this->_value), // Current value
                               'DV'      => $handler->_textstring($this->_value), // Default value
                               'DR'      => "2 0 R",
                               'Opt'     => $options_str));
    }

    function PDFFieldSelect(&$handler,
                            $object_id, 
                            $generation_id,
                            $rect, 
                            $field_name,
                            $value,
                            $options) {
      $this->PDFField($handler,
                      $object_id, 
                      $generation_id,
                      $rect, 
                      $field_name);

      $this->_options = $options;
      $this->_value   = $value;
    }
  }

  /**
   * Interactive text input
   */
  class PDFFieldText extends PDFField {
    var $fontindex;
    var $fontsize;

    var $_appearance;

    /**
     * @var String contains the default value of this text field
     * @access private
     */
    var $_value;

    function _dict(&$handler) {
      return array_merge(parent::_dict($handler),
                         array(
                               'FT'      => '/Tx',
                               'V'       => $handler->_textstring($this->_value), // Current value
                               'DV'      => $handler->_textstring($this->_value), // Default value
                               'DR'      => "2 0 R",
                               // @TODO fix font references
                               'DA'      => sprintf("(0 0 0 rg /FF%d %.2f Tf)", 
                                                    $this->fontindex,
                                                    $this->fontsize),
//                                'AP'      => $handler->_dictionary(array("N" => $handler->_reference($this->_appearance))),
                               ));
    }

    function _out_nested(&$handler) {
      //      $this->_appearance->out($handler);
    }

    function PDFFieldText(&$handler,
                          $object_id, 
                          $generation_id,
                          $rect, 
                          $field_name,
                          $value,
                          $fontindex, 
                          $fontsize) {
      $this->PDFField($handler,
                      $object_id, 
                      $generation_id,
                      $rect, 
                      $field_name);

      $this->fontindex = $fontindex;
      $this->fontsize  = $fontsize;
      $this->_value = $value;

//       $this->_appearance = new PDFAppearanceStream($handler,
//                                                    $handler->_generate_new_object_number(), 
//                                                    $generation_id,
//                                                    "/Tx BMC EMC");
    }
  }

  class PDFFieldMultilineText extends PDFFieldText {
    function _dict(&$handler) {
      return array_merge(parent::_dict($handler),
                         array('Ff'      => PDF_FIELD_TEXT_MULTILINE));
    }
  }

  /**
   * "Password" text input field
   */
  class PDFFieldPassword extends PDFFieldText {
    function PDFFieldPassword(&$handler, 
                              $object_id,
                              $generation_id,
                              $rect,
                              $field_name,
                              $value,
                              $fontindex,
                              $fontsize) {
      $this->PDFFieldText($handler,
                          $object_id,
                          $generation_id,
                          $rect,
                          $field_name,
                          $value,
                          $fontindex,
                          $fontsize);
    }

    function _dict(&$handler) {
      return array_merge(parent::_dict($handler),
                         array('Ff'      => PDF_FIELD_TEXT_PASSWORD));
    }
  }

  class FPDF {
    //Private properties
    
    var $page;               //current page number
    var $n;                  //current object number
    var $offsets;            //array of object offsets
    var $buffer;             //buffer holding in-memory PDF
    var $pages;              //array containing pages
    var $state;              //current document state
    var $compress;           //compression flag
    var $DefOrientation;     //default orientation
    var $k;                  //scale factor (number of points in user unit)
    var $fwPt,$fhPt;         //dimensions of page format in points
    var $fw,$fh;             //dimensions of page format in user unit
    var $wPt,$hPt;           //current dimensions of page in points
    var $w,$h;               //current dimensions of page in user unit
    var $x,$y;               //current position in user unit for cell positioning
    var $lasth;              //height of last cell printed
    var $LineWidth;          //line width in user unit
    var $fonts;              //array of used fonts
    var $FontFiles;          //array of font files

    var $diffs;              //array of encoding differences
    var $cmaps;              // List of ToUnicode 

    var $images;             //array of used images
    //    var $PageLinks;          //array of links in pages
    var $links;              //array of internal links
    var $FontFamily;         //current font family

    var $underline;          //underlining flag
    var $overline;
    var $strikeout;

    var $CurrentFont;        //current font info
    var $FontSizePt;         //current font size in points
    var $FontSize;           //current font size in user unit
    var $DrawColor;          //commands for drawing color
    var $FillColor;          //commands for filling color
    var $TextColor;          //commands for text color

    var $ColorFlag;          //indicates whether fill and text colors are different

    var $ws;                 //word spacing
    var $ZoomMode;           //zoom display mode
    var $LayoutMode;         //layout display mode
    var $title;              //title
    var $subject;            //subject
    var $author;             //author
    var $keywords;           //keywords
    var $creator;            //creator
    var $PDFVersion;         //PDF version number

    var $_forms;
    var $_form_radios;
    var $_pages;

    function moveto($x, $y) {
      $this->_out(sprintf("%.2f %.2f m", 
                          $this->x_coord($x), 
                          $this->y_coord($y)));
    }

    function lineto($x, $y) {
      $this->_out(sprintf("%.2f %.2f l", 
                          $this->x_coord($x), 
                          $this->y_coord($y)));
    }

    function closepath() {
      $this->_out("h");
    }

    function stroke() {
      $this->_out("S");
    }

    function is_object_written($id) {
      return isset($this->offsets[$id]);
    }

    function x_coord($x) {
      return $x * $this->k;
    }

    function y_coord($y) {
      return ($this->h - $y)*$this->k;
    }

    // PDF specs:
    // 3.2.9 Indirect Objects
    // Any object in a PDF file may be labeled as an indirect object. This gives the object
    // a unique object identifier by which other objects can refer to it (for example, as an
    // element of an array or as the value of a dictionary entry). The object identifier
    // consists of two parts:
    // * A positive integer object number. Indirect objects are often numbered sequentially
    //   within a PDF file, but this is not required; object numbers may be
    //   assigned in any arbitrary order.
    // * A non-negative integer generation number. In a newly created file, all indirect
    //   objects have generation numbers of 0. Nonzero generation numbers may be introduced
    //   when the file is later updated; see Sections 3.4.3, Cross-Reference
    //   Table, and 3.4.5, Incremental Updates.
    // Together, the combination of an object number and a generation number uniquely
    // identifies an indirect object. The object retains the same object number and
    // generation number throughout its existence, even if its value is modified.
    //
    function _indirect_object($object) {
      $object_number = $object->get_object_id();
      $generation_number = $object->get_generation_id();
      $object_string = $object->pdf($this);

      $this->offsets[$object_number] = strlen($this->buffer);

      return "$object_number $generation_number obj\n${object_string}\nendobj";
    }

    function _stream($content) {
      return "stream\n".$content."\nendstream";
    }

    /**
     * @TODO check name for validity
     */
    function _name($name) {
      return sprintf("/%s", $name);
    }
    
    function _dictionary($dict) {
      $content = "";
      foreach ($dict as $key => $value) {
        $content .= "/$key $value\n";
      };
      return "<<\n".$content."\n>>";
    }

    function _array($array_str) {
      return "[$array_str]";
    }

    function _reference(&$object) {
      $object_id     = $object->get_object_id();
      $generation_id = $object->get_generation_id();
      return "$object_id $generation_id R";
    }

    function _reference_array($object_array) {
      $array_str = "";
      for ($i=0; $i<count($object_array); $i++) {
        $array_str .= $this->_reference($object_array[$i])." ";
      };
      return $this->_array($array_str);
    }

    function _generate_new_object_number() {
      $this->n++;
      return $this->n;
    }

    function add_form($name) {
      $form = new PDFFieldGroup($this,
                                $this->_generate_new_object_number(),    // Object identifier
                                0,
                                $name);
      $this->_forms[] =& $form;
    }

    function add_field_select($x, $y, $w, $h, $name, $value, $options) {
      $field =& new PDFFieldSelect($this,
                                   $this->_generate_new_object_number(),    // Object identifier
                                   0,                                       // Generation
                                   new PDFRect($x, $y, $w, $h),             // Annotation rectangle
                                   $name,                                   // Field name
                                   $value,
                                   $options);

      $current_form =& $this->current_form();
      $current_form->add_field($field);

      $this->_pages[count($this->_pages)-1]->add_annotation($field); 
    }

    /**
     * Create new checkbox field object
     *
     * @param $x Integer Left coordinate of the widget bounding bog
     * @param $y Integer Upper coordinate of the widget bounding bog
     * @param $w Integer Widget width
     * @param $h Integer Widget height
     * @param $name String name of the field to be created
     * @param $value String value to be posted for this checkbox
     *
     * @TODO check if fully qualified field name will be unique in PDF file
     */
    function add_field_checkbox($x, $y, $w, $h, $name, $value, $checked) {
      $field =& new PDFFieldCheckBox($this,
                                     $this->_generate_new_object_number(),    // Object identifier
                                     0,                                       // Generation
                                     new PDFRect($x, $y, $w, $h),             // Annotation rectangle
                                     $name,                                   // Field name
                                     $value, $checked);                                 // Checkbox "on" value

      $current_form =& $this->current_form();
      $current_form->add_field($field);

      $this->_pages[count($this->_pages)-1]->add_annotation($field); 
    }

    function &current_form() {
      if (count($this->_forms) == 0) {
        /**
         * Handle invalid HTML; if we've met an input control outside the form, 
         * generate a new form with random name
         */

        $id   = $this->_generate_new_object_number();
        $name = sprintf("AnonymousFormObject_%u", $id);

        error_log(sprintf("Anonymous form generated with name %s; check your HTML for validity", 
                          $name));

        $form = new PDFFieldGroup($this,
                                  $id,    // Object identifier
                                  0,
                                  $name);
        $this->_forms[] =& $form;
      };

      return $this->_forms[count($this->_forms)-1];
    }

    function add_field_radio($x, $y, $w, $h, $group_name, $value, $checked) {
      if (isset($this->_form_radios[$group_name])) {
        $field =& $this->_form_radios[$group_name];
      } else {
        $field =& new PDFFieldRadioGroup($this, 
                                         $this->_generate_new_object_number(),
                                         0,
                                         $group_name);
        
        $current_form =& $this->current_form();
        $current_form->add_field($field);

        $this->_form_radios[$group_name] =& $field;
      };

      $radio =& new PDFFieldRadio($this, 
                                  $this->_generate_new_object_number(),
                                  0,
                                  new PDFRect($x, $y, $w, $h),
                                  $value);
      $field->add_field($radio);
      if ($checked) { $field->set_checked($value); };

      $this->_pages[count($this->_pages)-1]->add_annotation($radio);
    }

    /**
     * Create a new interactive text form
     *
     * @param $x Left coordinate of the widget bounding box
     * @param $y Top coordinate of the widget bounding box
     * @param $w Widget width
     * @param $h Widget height
     * @param $value Default widget value
     * @param $field_name Field name
     *
     * @return Field number
     */
    function add_field_text($x, $y, $w, $h, $value, $field_name) {
      $field =& new PDFFieldText($this, 
                                 $this->_generate_new_object_number(),
                                 0,
                                 new PDFRect($x, $y, $w, $h), 
                                 $field_name,
                                 $value,
                                 $this->CurrentFont['i'], 
                                 $this->FontSizePt);

      $current_form =& $this->current_form();
      $current_form->add_field($field);

      $this->_pages[count($this->_pages)-1]->add_annotation($field);    
    }

    function add_field_multiline_text($x, $y, $w, $h, $value, $field_name) {
      $field =& new PDFFieldMultilineText($this, 
                                          $this->_generate_new_object_number(),
                                          0,
                                          new PDFRect($x, $y, $w, $h), 
                                          $field_name,
                                          $value,
                                          $this->CurrentFont['i'], 
                                          $this->FontSizePt);
      
      $current_form =& $this->current_form();
      $current_form->add_field($field);

      $this->_pages[count($this->_pages)-1]->add_annotation($field);    
    }

    /**
     * Create a new interactive password input field
     *
     * @param $x Left coordinate of the widget bounding box
     * @param $y Top coordinate of the widget bounding box
     * @param $w Widget width
     * @param $h Widget height
     * @param $value Default widget value
     * @param $field_name Field name
     *
     * @return Field number
     */
    function add_field_password($x, $y, $w, $h, $value, $field_name) {
      $field =& new PDFFieldPassword($this,
                                     $this->_generate_new_object_number(),
                                     0,
                                     new PDFRect($x, $y, $w, $h),
                                     $field_name,
                                     $value,
                                     $this->CurrentFont['i'], 
                                     $this->FontSizePt);

      $current_form =& $this->current_form();
      $current_form->add_field($field);

      $this->_pages[count($this->_pages)-1]->add_annotation($field);
    }

    function add_field_pushbuttonimage($x, $y, $w, $h, $field_name, $value, $actionURL) {
      $field =& new PDFFieldPushButtonImage($this,
                                            $this->_generate_new_object_number(),
                                            0,
                                            new PDFRect($x, $y, $w, $h),
                                            $this->CurrentFont['i'], 
                                            $this->FontSizePt,
                                            $field_name,
                                            $value,
                                            $actionURL);
      
      $current_form =& $this->current_form();
      $current_form->add_field($field);

      $this->_pages[count($this->_pages)-1]->add_annotation($field);    
    }

    function add_field_pushbuttonsubmit($x, $y, $w, $h, $field_name, $value, $actionURL) {
      $field =& new PDFFieldPushButtonSubmit($this,
                                             $this->_generate_new_object_number(),
                                             0,
                                             new PDFRect($x, $y, $w, $h),
                                             $this->CurrentFont['i'], 
                                             $this->FontSizePt,
                                             $field_name,
                                             $value,
                                             $actionURL);

      $current_form =& $this->current_form();
      $current_form->add_field($field);

      $this->_pages[count($this->_pages)-1]->add_annotation($field);    
    }

    function add_field_pushbuttonreset($x, $y, $w, $h) {
      $field =& new PDFFieldPushButtonReset($this,
                                            $this->_generate_new_object_number(),
                                            0,
                                            new PDFRect($x, $y, $w, $h),
                                            null,
                                            $this->CurrentFont['i'], 
                                            $this->FontSizePt);

      $current_form =& $this->current_form();
      $current_form->add_field($field);

      $this->_pages[count($this->_pages)-1]->add_annotation($field);    
    }

    function add_field_pushbutton($x, $y, $w, $h) {
      $field =& new PDFFieldPushButton($this,
                                       $this->_generate_new_object_number(),
                                       0,
                                       new PDFRect($x, $y, $w, $h),
                                       null,
                                       $this->CurrentFont['i'], 
                                       $this->FontSizePt);

      $current_form =& $this->current_form();
      $current_form->add_field($field);

      $this->_pages[count($this->_pages)-1]->add_annotation($field);    
    }


    function SetDash($x, $y) {
      $x = (int)$x;
      $y = (int)$y;
      $this->_out(sprintf("[%d %d] 0 d", $x*2, $y*2));      
    }

    function _GetFontBBox() {
      return preg_split("/[\[\]\s]+/", $this->CurrentFont['desc']['FontBBox']);
    }

    function _dounderline($x,$y,$txt) {
      //Underline text
      $up=$this->CurrentFont['up'];
      $ut=$this->CurrentFont['ut'];
      $w=$this->GetStringWidth($txt)+$this->ws*substr_count($txt,' ');

      $content = sprintf('%.2f %.2f %.2f %.2f re f',
                         $x*$this->k,
                         ($this->h-($y-$up/1000*$this->FontSize))*$this->k,
                         $w*$this->k,
                         -$ut/1000*$this->FontSizePt);

      return $content;
    }

    function _dooverline($x,$y,$txt) {
      $bbox = $this->_GetFontBBox();
      $up = round($bbox[3] * 0.8);
     
      $ut=$this->CurrentFont['ut'];

      $w=$this->GetStringWidth($txt)+$this->ws*substr_count($txt,' ');
      return sprintf('%.2f %.2f %.2f %.2f re f',
                     $x*$this->k,
                     ($this->h-($y-$up/1000*$this->FontSize))*$this->k,
                     $w*$this->k,
                     -$ut/1000*$this->FontSizePt);
    }

    function _dostrikeout($x,$y,$txt) {
      $bbox = $this->_GetFontBBox();
      $up = round($bbox[3] * 0.25);

      $ut=$this->CurrentFont['ut'];
      $w=$this->GetStringWidth($txt)+$this->ws*substr_count($txt,' ');
      return sprintf('%.2f %.2f %.2f %.2f re f',
                     $x*$this->k,
                     ($this->h-($y-$up/1000*$this->FontSize))*$this->k,
                     $w*$this->k,
                     -$ut/1000*$this->FontSizePt);
    }

    function SetDecoration($underline, $overline, $strikeout) {
      $this->underline = $underline;
      $this->overline  = $overline;
      $this->strikeout = $strikeout;
    }

    function ClipPath($path) {
      if (count($path) < 3) { 
        die("Attempt to clip on the path containing less than three points"); 
      };

      $this->MakePath($path);
      $this->Clip();
    }

    function Clip() {
      $this->_out("W n");
    }

    // TODO: more graceful custom encoding processing
    function _LoadFont($fontkey, $family, $encoding) {
      if (!isset($this->fonts[$fontkey])) {
        global $g_font_resolver_pdf;
        $file = $g_font_resolver_pdf->ttf_mappings[$family];

        $embed = $g_font_resolver_pdf->embed[$family];
        
        // Remove the '.ttf' suffix
        $file = substr($file, 0, strlen($file) - 4);
          
        // Generate (if required) PHP font description files
        if (!file_exists($this->_getfontpath().$fontkey.'.php') || $encoding == 'custom') {
          // As MakeFont squeaks a lot, we'll need to capture and discard its output
          MakeFont(TTF_FONTS_REPOSITORY.$file.'.ttf',
                   TTF_FONTS_REPOSITORY.$file.'.afm',
                   $this->_getfontpath(),
                   $fontkey.'.php',
                   $encoding);
        };

        $this->AddFont($fontkey, $family, $encoding, $fontkey.'.php', $embed); 
      };
    }

    function _MakeFontKey($family, $encoding) {
      return $family.'-'.$encoding;
    }

    function GetFontAscender($name, $encoding) {
      $fontkey = $this->_MakeFontKey($name, $encoding);
      $this->_LoadFont($fontkey, $name, $encoding, '');
      return $this->fonts[$fontkey]['desc']['Ascent'] / 1000;
    }

    function GetFontDescender($name, $encoding) {
      $fontkey = $this->_MakeFontKey($name, $encoding);
      $this->_LoadFont($fontkey, $name, $encoding, '');
      return -$this->fonts[$fontkey]['desc']['Descent'] / 1000;
    }

    // Note that FPDF do some caching, which can conflict with "save/restore" pairs
    function Save() { 
      $this->_out("q"); 
    }

    function Restore() { 
      $this->_out("Q"); 
    }

    function Translate($dx, $dy) {
      $this->_out(sprintf("1 0 0 1 %.2f %.2f cm", $dx, $dy));
    }

    function Rotate($alpha) {
      $this->_out(sprintf("%.2f %.2f %.2f %.2f 0 0 cm", 
                          cos($alpha/180*pi()),
                          sin($alpha/180*pi()),
                          -sin($alpha/180*pi()),
                          cos($alpha/180*pi())
                          ));
    }

    function SetTextRendering($mode) {
      $this->_out(sprintf("%d Tr", $mode));
    }

    function MakePath($path) {
      $this->_out(sprintf("%.2f %.2f m", $path[0]['x'], $path[0]['y']));

      for ($i=1; $i<count($path); $i++) {
        $this->_out(sprintf("%.2f %.2f l", $path[$i]['x'], $path[$i]['y']));
      };
    }

    function FillPath($path) {
      if (count($path) < 3) { 
        die("Attempt to fill path containing less than three points"); 
      };

      $this->_out($this->FillColor);
      $this->MakePath($path);
      $this->Fill();
    }

    function Fill() {
      $this->_out("f");
    }

    /**
     * Thanks G. Adam Stanislav for information about approximation circle using bezier curves
     * http://www.whizkidtech.redprince.net/bezier/circle/
     */
    function Circle($x, $y, $r) {
      $kappa = (sqrt(2) - 1) / 3 * 4;
      $l = $kappa * $r;

      $this->_out(sprintf("%.2f %.f2 m", $x + $r, $y));
      $this->_out(sprintf("%.2f %.f2 %.2f %.2f %.2f %.2f c", 
                          $x + $r, $y + $l, 
                          $x + $l, $y + $r,
                          $x, $y + $r));      
      $this->_out(sprintf("%.2f %.f2 %.2f %.2f %.2f %.2f c", 
                          $x - $l, $y + $r,
                          $x - $r, $y + $l, 
                          $x - $r, $y));      
      $this->_out(sprintf("%.2f %.f2 %.2f %.2f %.2f %.2f c", 
                          $x - $r, $y - $l, 
                          $x - $l, $y - $r,
                          $x, $y - $r));      
      $this->_out(sprintf("%.2f %.f2 %.2f %.2f %.2f %.2f c", 
                          $x + $l, $y - $r,
                          $x + $r, $y - $l, 
                          $x + $r, $y));      
    }

    /*******************************************************************************
     *                                                                              *
     *                               Public methods                                 *
     *                                                                              *
     *******************************************************************************/
    function FPDF($orientation='P', $unit='mm', $format='A4') {
      $this->_forms = array();
      $this->_form_radios = array();
      $this->_pages = array();

      //Some checks
      $this->_dochecks();

      //Initialization of properties
      $this->page=0;

      $this->n=2;

      $this->buffer='';
      $this->pages=array();
      $this->state = FPDF_STATE_UNINITIALIZED;
      $this->fonts=array();
      $this->FontFiles=array();
      $this->diffs  = array();
      $this->images = array();
      $this->links  = array();
      $this->lasth=0;
      $this->FontFamily='';
      $this->FontSizePt=12;

      $this->underline = false;
      $this->overline  = false;
      $this->strikeout = false;

      $this->DrawColor='0 G';
      $this->FillColor='0 g';
      $this->TextColor='0 g';
      $this->ColorFlag=false;
      $this->ws=0;

      //Scale factor
      switch ($unit) {
      case 'pt':
        $this->k = 1; break;
      case 'mm':
        $this->k = 72/25.4; break;
      case 'cm':
        $this->k = 72/2.54; break;
      case 'in':
        $this->k = 72;
      default:
        $this->Error('Incorrect unit: '.$unit);
      };

      $this->setup_format($format[0], $format[1]);

      //Line width (0.2 mm)
      $this->LineWidth=.567/$this->k;

      //Full width display mode
      $this->SetDisplayMode('fullwidth');

      //Enable compression
      $this->SetCompression(true);

      //Set default PDF version number
      $this->PDFVersion='1.3';
    }

    function setup_format($width, $height) {
      $this->fwPt = $width * $this->k;
      $this->fhPt = $height * $this->k;
      $this->wPt = $this->fwPt;
      $this->hPt = $this->fhPt;

      $this->fw = $width;
      $this->fh = $height;
      $this->w = $this->fw;
      $this->h = $this->fh;

      $this->DefOrientation='P';
    }

    function SetDisplayMode($zoom,$layout='continuous') {
      //Set display mode in viewer
      if($zoom=='fullpage' || $zoom=='fullwidth' || $zoom=='real' || $zoom=='default' || !is_string($zoom))
        $this->ZoomMode=$zoom;
      else
        $this->Error('Incorrect zoom display mode: '.$zoom);
      if($layout=='single' || $layout=='continuous' || $layout=='two' || $layout=='default')
        $this->LayoutMode=$layout;
      else
        $this->Error('Incorrect layout display mode: '.$layout);
    }

    /**
     * @param $compress Boolean indicates whether compression is enabled
     */
    function SetCompression($compress) {
      if (function_exists('gzcompress')) {
        $this->compress=$compress;
      } else {
        $this->compress=false;
      };
    }

    function SetTitle($title) {
      //Title of document
      $this->title=$title;
    }

    function SetSubject($subject) {
      //Subject of document
      $this->subject=$subject;
    }

    function SetAuthor($author) {
      //Author of document
      $this->author=$author;
    }

    function SetKeywords($keywords) {
      //Keywords of document
      $this->keywords=$keywords;
    }

    function SetCreator($creator) {
      //Creator of document
      $this->creator=$creator;
    }

    function Error($msg) {
      //Fatal error
      die('<B>FPDF error: </B>'.$msg);
    }

    function Open() {
      //Begin document
      $this->state = FPDF_STATE_DOCUMENT_STARTED;
    }

    function Close() {
      //Terminate document
      if ($this->state == FPDF_STATE_COMPLETED) {
        return;
      };

      if ($this->page==0) {
        $this->AddPage();
      };

      //Close page
      $this->_endpage();
      //Close document
      $this->_enddoc();
    }

    function AddPage($width = null, $height = null) {
      if (!$width) {
        $width = $this->fwPt;
      };

      if (!$height) {
        $height = $this->fhPt;
      };

      $this->setup_format($width, $height);

      $this->_pages[] =& new PDFPage($this, $width, $height, $this->_generate_new_object_number(), 0);

      //Start a new page
      if ($this->state == FPDF_STATE_UNINITIALIZED) {
        $this->Open();
      };

      $family=$this->FontFamily;
      $size=$this->FontSizePt;
      $lw=$this->LineWidth;
      $dc=$this->DrawColor;
      $fc=$this->FillColor;
      $tc=$this->TextColor;
      $cf=$this->ColorFlag;
      if ($this->page>0) {
        //Close page
        $this->_endpage();
      }
      
      //Start new page
      $this->_beginpage();
      //Set line cap style to square
      $this->_out('2 J');
      //Set line width
      $this->LineWidth=$lw;
      $this->_out(sprintf('%.2f w',$lw*$this->k));

      //Set colors
      $this->DrawColor=$dc;
      if ($dc!='0 G') {
        $this->_out($dc);
      };

      $this->FillColor=$fc;
      if ($fc!='0 g') {
        $this->_out($fc);
      };

      $this->TextColor=$tc;
      $this->ColorFlag=$cf;

      //Restore line width
      if ($this->LineWidth!=$lw) {
        $this->LineWidth=$lw;
        $this->_out(sprintf('%.2f w',$lw*$this->k));
      }

      //Restore colors
      if ($this->DrawColor!=$dc) {
        $this->DrawColor=$dc;
        $this->_out($dc);
      }
      if ($this->FillColor!=$fc) {
        $this->FillColor=$fc;
        $this->_out($fc);
      }
      $this->TextColor=$tc;
      $this->ColorFlag=$cf;

      if (!is_null($this->CurrentFont)) {
        $this->_out(sprintf('BT /F%d %.2f Tf ET',$this->CurrentFont['i'],$this->FontSizePt));
      };
    }

    function SetDrawColor($r,$g=-1,$b=-1) {
      // Set color for all stroking operations
      if (($r==0 && $g==0 && $b==0) || $g==-1) {
        $new_color = sprintf('%.3f G',$r/255);
      } else {
        $new_color = sprintf('%.3f %.3f %.3f RG',$r/255,$g/255,$b/255);
      };

      if ($this->page > 0 /*&& $this->DrawColor != $new_color*/) {
        $this->DrawColor = $new_color;
        $this->_out($this->DrawColor);
      };
    }

    function SetFillColor($r,$g=-1,$b=-1) {
      // Set color for all filling operations
      if (($r==0 && $g==0 && $b==0) || $g==-1) {
        $new_color = sprintf('%.3f g',$r/255);
      } else { 
        $new_color = sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
      };

      if ($this->page>0 /*&& $this->FillColor != $new_color*/) {
        $this->FillColor = $new_color;
        $this->ColorFlag = ($this->FillColor!=$this->TextColor);
        $this->_out($this->FillColor);
      };
    }

    function SetTextColor($r,$g=-1,$b=-1) {
      //Set color for text
      if (($r==0 && $g==0 && $b==0) || $g==-1) {
        $this->TextColor=sprintf('%.3f g',$r/255);
      } else {
        $this->TextColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
      };
      
      $this->ColorFlag=($this->FillColor!=$this->TextColor);
    }

    function GetStringWidth($s) {
      //Get width of a string in the current font
      $s=(string)$s;
      $cw = &$this->CurrentFont['cw'];
      $w=0;

      $l=strlen($s);
      for ($i=0; $i<$l; $i++) {
        $w+=$cw[$s{$i}];
      };

      return $w*$this->FontSize/1000;
    }

    /**
     * Set line width
     */
    function SetLineWidth($width) {
      $this->LineWidth = $width;
      if ($this->page > 0) {
        $this->_out(sprintf('%.2f w',$width*$this->k));
      };
    }

    /**
     * Draw a line
     */
    function Line($x1,$y1,$x2,$y2) {
      $this->_out(sprintf('%.2f %.2f m %.2f %.2f l S',$x1*$this->k,($this->h-$y1)*$this->k,$x2*$this->k,($this->h-$y2)*$this->k));
    }

    /**
     * Add a TrueType or Type1 font
     */
    function AddFont($fontkey, $family, $encoding, $file, $bEmbed) {
      if(isset($this->fonts[$fontkey])) {
        $this->Error('Font already added: '.$family);
      };

      $filepath = $this->_getfontpath().$file;
      include($filepath);

      // After we've executed 'include' the $file variable
      // have been overwritten by $file declared in font definition file; if we do not want 
      // to embed the font in the PDF file, we should set to empty string
      if (!$bEmbed) { $file = ''; };

      if(!isset($name)) {
        $this->Error("Could not include font definition file: $filepath");
      };

      $i=count($this->fonts)+1;
      $this->fonts[$fontkey]=array('i'    =>$i,
                                   'type' =>$type,
                                   'name' =>$name,
                                   'desc' =>$desc,
                                   'up'   =>$up,
                                   'ut'   =>$ut,
                                   'cw'   =>$cw,
                                   'enc'  =>$enc,
                                   'file' =>$file);

      if ($diff) {
        //Search existing encodings
        $d=0;
        $nb=count($this->diffs);
        for ($i=1; $i<=$nb; $i++) {
          if ($this->diffs[$i] == $diff) {
            $d=$i;
            break;
          }
        }
        if ($d==0) {
          $d=$nb+1;
          $this->diffs[$d] = $diff;

          /**
           * TODO
           * Add CMAP for this font
           */
          $this->cmaps[$d] = new PDFCMap($cmap,
                                         $handler, 
                                         $this->_generate_new_object_number(),
                                         0);
        }
        $this->fonts[$fontkey]['diff']=$d;
      }

      if ($file) {
        if ($type=='TrueType') {
          $this->FontFiles[$file]=array('length1'=>$originalsize);
        } else {
          $this->FontFiles[$file]=array('length1'=>$size1,'length2'=>$size2);
        };
      }
    }

    /**
     * Select a font; size given in points
     */
    function SetFont($family, $encoding, $size) {
      global $fpdf_charwidths;

      $fontkey = $this->_MakeFontKey($family, $encoding);
      $this->_LoadFont($fontkey, $family, $encoding);

      $this->FontFamily  = $family;
      $this->FontSizePt  = $size;
      $this->FontSize    = $size/$this->k;
      $this->CurrentFont = &$this->fonts[$fontkey];

      if ($this->page > 0) {
        $this->_out(sprintf('BT /F%d %.2f Tf ET',$this->CurrentFont['i'],$this->FontSizePt));
      };
    }

    /**
     * Create a new internal link
     */
    function AddLink() {
      $n=count($this->links)+1;
      $this->links[$n]=array(0,0);
      return $n;
    }

    /**
     * Set destination of internal link
     */
    function SetLink($link,$y,$page) {
      $this->links[$link]=array($page,$y);
    }

    /**
     * Add an external hyperlink on the page (an rectangular area). It is not bound to any other PDF element,
     * like text. It is the task of layout engine to draw the appropriate text inside this area.
     * 
     * @param Float $x X-coordinate of the upper-left corner of the link area
     * @param Float $y Y-coordinate of the upper-left corner of the link area
     * @param Float $w link area width 
     * @param Float $h link area height 
     * @param String $link Link URL
     */
    function add_link_external($x, $y, $w, $h, $link) {
      $link = new PDFAnnotationExternalLink($this,
                                            $this->_generate_new_object_number(),
                                            0,
                                            new PDFRect($x, $y, $w, $h),
                                            $link);
      $this->_pages[count($this->_pages)-1]->add_annotation($link);
    }

    /**
     * Add an internal hyperlink on the page (an rectangular area). It is not bound to any other PDF element,
     * like text. It is the task of layout engine to draw the appropriate text inside this area.
     * 
     * @param Float $x X-coordinate of the upper-left corner of the link area
     * @param Float $y Y-coordinate of the upper-left corner of the link area
     * @param Float $w link area width 
     * @param Float $h link area height 
     * @param Integer $link Internal Link identifier
     */
    function add_link_internal($x, $y, $w, $h, $link) {
      $link = new PDFAnnotationInternalLink($this,
                                            $this->_generate_new_object_number(),
                                            0,
                                            new PDFRect($x, $y, $w, $h),
                                            $link);
      $this->_pages[count($this->_pages)-1]->add_annotation($link);      
    }    

    function Text($x, $y, $txt) {
      //Output a string
      $s = sprintf('BT %.2f %.2f Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));

      if ($this->underline && $txt!='') {
        $s.=' '.$this->_dounderline($x,$y,$txt);
      }

      if ($this->overline && $txt!='') {
        $s.=' '.$this->_dooverline($x,$y,$txt);
      }

      if ($this->strikeout && $txt!='') {
        $s.=' '.$this->_dostrikeout($x,$y,$txt);
      }

      if ($this->ColorFlag) {
        $s='q '.$this->TextColor.' '.$s.' Q';
      };
      $this->_out($s);
    }

    /**
     * Accepts PNG images only
     */
    function Image($file, $x, $y, $w, $h) {
      // Image used first time, parse input file
      if (!isset($this->images[$file])) {
        $info = $this->_parsepng($file);
        $info['i'] = count($this->images) + 1;
        $this->images[$file] = $info;
      };

      $info = $this->images[$file];
      $this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',
                          $w*$this->k,
                          $h*$this->k,
                          $x*$this->k,
                          ($this->h-($y+$h))*$this->k,
                          $info['i']));
    }

    /**
     * @param $name String file to save generated PDF in
     */
    function Output($name) {
      //Finish document if necessary
      if ($this->state != FPDF_STATE_COMPLETED) {
        $this->Close();
      };

      $f=fopen($name,'wb');
      if (!$f) {
        $this->Error('Unable to create output file: '.$name);
      };
      fwrite($f,$this->buffer,strlen($this->buffer));
      fclose($f);
    }

    /********************************************************************************
     *                                                                              *
     *                              Protected methods                               *
     *                                                                              *
     *******************************************************************************/
    function _dochecks() {
      // Check for locale-related bug
      if (1.1==1) {
        $this->Error('Don\'t alter the locale before including class file');
      };

      // Check for decimal separator
      if (sprintf('%.1f',1.0)!='1.0') {
        setlocale(LC_NUMERIC,'C');
      };
    }

    function _getfontpath() {
      return CACHE_DIR;
    }

    function _putpages() {
      $nb=$this->page;

      if ($this->DefOrientation=='P') {
        $wPt=$this->fwPt;
        $hPt=$this->fhPt;
      } else {
        $wPt=$this->fhPt;
        $hPt=$this->fwPt;
      };

      $filter=($this->compress) ? '/Filter /FlateDecode ' : '';

      $pages_start_obj_number = $this->n+1;

      for ($n=1; $n<=$nb; $n++) {
        //Page

        $page = $this->_pages[$n-1];
        $this->offsets[$page->get_object_id()] = strlen($this->buffer);
        $this->_out(sprintf("%u %u obj",$page->object_id, $page->generation_id));
        
        $this->_out('<</Type /Page');
        $this->_out('/Parent 1 0 R');
        $this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',
                            $page->get_width(),
                            $page->get_height()));
        $this->_out("/Annots ".$this->_pages[$n-1]->_annotations($this));
        $this->_out('/Resources 2 0 R');

        $this->_out('/Contents '.($this->n+1).' 0 R>>');
        $this->_out('endobj');
        //Page content
        $p=($this->compress) ? gzcompress($this->pages[$n]) : $this->pages[$n];
        $this->_newobj();
        $this->_out('<<'.$filter.'/Length '.strlen($p).'>>');
        $this->_putstream($p);
        $this->_out('endobj');

        // Output annotation object for this page
        $annotations = $this->_pages[$n-1]->annotations;
        $size = count($annotations);

        for ($j=0; $j<$size; $j++) {
          $annotations[$j]->out($this);
        };
      }

      //Pages root
      $this->offsets[1] = strlen($this->buffer);
      $this->_out('1 0 obj');
      $this->_out('<</Type /Pages');

      $this->_out('/Kids '.$this->_reference_array($this->_pages));

      $this->_out('/Count '.$nb);
      $this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$wPt,$hPt));
      $this->_out('>>');
      $this->_out('endobj');

      return $pages_start_obj_number;
    }

    function _putfonts() {
      $nf=$this->n;

      $num_diffs = count($this->diffs);
      for ($i=1; $i<=$num_diffs; $i++) {
        $diff = $this->diffs[$i];
        $cmap = $this->cmaps[$i];

        //Encodings
        $this->_newobj();
        $this->_out($this->_dictionary(array("Type"         => "/Encoding",
                                             "BaseEncoding" => "/WinAnsiEncoding",
                                             "Differences"  => $this->_array($diff))));
        $this->_out('endobj');

        $cmap->out($this);
      }
      
      foreach ($this->FontFiles as $file=>$info) {
        //Font file embedding
        $this->_newobj();
        $this->FontFiles[$file]['n'] = $this->n;
        $font='';
        $f=fopen($this->_getfontpath().$file,'rb',1);
        if (!$f) {
          $this->Error('Font file not found');
        };

        while (!feof($f)) { $font.=fread($f,8192); };

        fclose($f);
        $compressed=(substr($file,-2)=='.z');
        if (!$compressed && isset($info['length2'])) {
          $header=(ord($font{0})==128);
          if($header) {
            //Strip first binary header
            $font=substr($font,6);
          }
          if($header && ord($font{$info['length1']})==128) {
            //Strip second binary header
            $font=substr($font,0,$info['length1']).substr($font,$info['length1']+6);
          }
        }
        $this->_out('<</Length '.strlen($font));

        if ($compressed) {
          $this->_out('/Filter /FlateDecode');
        };

        $this->_out('/Length1 '.$info['length1']);
        if(isset($info['length2'])) {
          $this->_out('/Length2 '.$info['length2'].' /Length3 0');
        };
        $this->_out('>>');
        $this->_putstream($font);
        $this->_out('endobj');
      }

      foreach ($this->fonts as $k=>$font) {
        //Font objects
        $this->fonts[$k]['n'] = $this->n+1;
        $type=$font['type'];
        $name=$font['name'];

        if ($type=='Type1' || $type=='TrueType') {
          //Additional Type1 or TrueType font
          $this->_newobj();
          $this->_out('<</Type /Font');
          $this->_out('/BaseFont /'.$name);
          $this->_out('/Subtype /'.$type);
          $this->_out('/FirstChar 32 /LastChar 255');
          $this->_out('/Widths '.($this->n+1).' 0 R');
          $this->_out('/FontDescriptor '.($this->n+2).' 0 R');
          if ($font['enc']) {
            if(isset($font['diff'])) {
              $this->_out('/Encoding '.($nf+$font['diff']).' 0 R');
              $this->_out('/ToUnicode '.($this->_reference($this->cmaps[$font['diff']])));
            } else {
              $this->_out('/Encoding /WinAnsiEncoding');
            };
          }
          $this->_out('>>');
          $this->_out('endobj');
            
          //Widths
          $this->_newobj();
          $cw = &$font['cw'];
          $s='[';
          for ($i=32;$i<=255;$i++) {
            $s.=$cw[chr($i)].' ';
          };
          $this->_out($s.']');
          $this->_out('endobj');

          /**
           * Font descriptor
           */
          $this->_newobj();
          $fontDescriptor = array('Type'        => '/FontDescriptor',
                                  'FontName'    => '/'.$name,
                                  'Flags'       => $font['desc']['Flags'],
                                  'FontBBox'    => $font['desc']['FontBBox'],
                                  'ItalicAngle' => $font['desc']['ItalicAngle'],
                                  'Ascent'      => $font['desc']['Ascent'],
                                  'Descent'     => $font['desc']['Descent'],
                                  'CapHeight'   => $font['desc']['CapHeight'],
                                  'StemV'       => $font['desc']['StemV']
                                  );
          if ($font['file'] != "") {
            $fontDescriptor['FontFile'.($type=='Type1' ? '' : '2')] = 
              $this->FontFiles[$font['file']]['n'].' 0 R';
          };
          $this->_out($this->_dictionary($fontDescriptor));
          $this->_out('endobj');

        } else {
          //Allow for additional types
          $mtd='_put'.strtolower($type);
          if(!method_exists($this,$mtd))
            $this->Error('Unsupported font type: '.$type);
          $this->$mtd($font);
        }
      }
    }

    function _putimages() {
      $filter=($this->compress) ? '/Filter /FlateDecode ' : '';
      reset($this->images);
      while (list($file,$info) = each($this->images)) {
        $this->_newobj();
        $this->images[$file]['n']=$this->n;
        $this->_out('<</Type /XObject');
        $this->_out('/Subtype /Image');
        $this->_out('/Width '.$info['w']);
        $this->_out('/Height '.$info['h']);
        if ($info['cs']=='Indexed') {
          $this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
        } else {
          $this->_out('/ColorSpace /'.$info['cs']);
          if($info['cs']=='DeviceCMYK') {
            $this->_out('/Decode [1 0 1 0 1 0 1 0]');
          };
        }
        $this->_out('/BitsPerComponent '.$info['bpc']);
        if (isset($info['f'])) {
          $this->_out('/Filter /'.$info['f']);
        };

        if(isset($info['parms'])) {
          $this->_out($info['parms']);
        };

        if(isset($info['trns']) && is_array($info['trns'])) {
          $trns='';
          for ($i=0;$i<count($info['trns']);$i++) {
            $trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
          };
          $this->_out('/Mask ['.$trns.']');
        };

        $this->_out('/Length '.strlen($info['data']).'>>');
        $this->_putstream($info['data']);
        unset($this->images[$file]['data']);
        $this->_out('endobj');

        // Palette
        if ($info['cs']=='Indexed') {
          $this->_newobj();
          $pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
          $this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
          $this->_putstream($pal);
          $this->_out('endobj');
        };
      }
    }

    function _putxobjectdict() {
      foreach ($this->images as $image) {
        $this->_out('/I'.$image['i'].' '.$image['n'].' 0 R');
      };
    }

    function _putresourcedict() {
      $this->_out('/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
      $this->_out('/Font <<');
      foreach ($this->fonts as $font) {
        $this->_out('/F'.$font['i'].' '.$font['n'].' 0 R');
      };
      $this->_out('>>');
      $this->_out('/XObject <<');
      $this->_putxobjectdict();
      $this->_out('>>');
    }

    function _putresources() {
      $this->_putfonts();
      $this->_putimages();

      //Resource dictionary
      $this->offsets[2]=strlen($this->buffer);
      $this->_out('2 0 obj');
      $this->_out('<<');
      $this->_putresourcedict();
      $this->_out('>>');
      $this->_out('endobj');
    }

    function _putinfo() {
      $this->_out('/Producer '.$this->_textstring('FPDF '.FPDF_VERSION));

      if (!empty($this->title)) {
        $this->_out('/Title '.$this->_textstring($this->title));
      };

      if (!empty($this->subject)) {
        $this->_out('/Subject '.$this->_textstring($this->subject));
      };

      if (!empty($this->author)) {
        $this->_out('/Author '.$this->_textstring($this->author));
      };

      if (!empty($this->keywords)) {
        $this->_out('/Keywords '.$this->_textstring($this->keywords));
      };

      if (!empty($this->creator)) {
        $this->_out('/Creator '.$this->_textstring($this->creator));
      };

      $this->_out('/CreationDate '.$this->_textstring('D:'.date('YmdHis')));
    }

    // Generate the document catalog entry of PDF file
    function _putcatalog($pages_start_obj_number) {
      $this->_out('/Type /Catalog');

      $this->_out('/Pages 1 0 R');
      if ($this->ZoomMode=='fullpage') {
        $this->_out("/OpenAction [$pages_start_obj_number 0 R /Fit]");
      } elseif ($this->ZoomMode=='fullwidth') {
        $this->_out("/OpenAction [$pages_start_obj_number 0 R /FitH null]");
      } elseif ($this->ZoomMode=='real') {
        $this->_out("/OpenAction [$pages_start_obj_number 0 R /XYZ null null 1]");
      } elseif (!is_string($this->ZoomMode)) {
        $this->_out("/OpenAction [$pages_start_obj_number 0 R /XYZ null null ".($this->ZoomMode/100).']');
      };

      if ($this->LayoutMode=='single') {
        $this->_out('/PageLayout /SinglePage');
      } elseif ($this->LayoutMode=='continuous') {
        $this->_out('/PageLayout /OneColumn');
      } elseif ($this->LayoutMode=='two') {
        $this->_out('/PageLayout /TwoColumnLeft');
      };

      if (count($this->_forms) > 0) {
        $this->_out('/AcroForm <<');
        $this->_out('/Fields '.$this->_reference_array($this->_forms));
        $this->_out('/DR 2 0 R');
        $this->_out('/NeedAppearances true');
        $this->_out('>>');
      };
    }
    
    function _putheader() {
      $this->_out('%PDF-'.$this->PDFVersion);
    }

    function _puttrailer() {
      $this->_out('/Size '.($this->n+1));
      $this->_out('/Root '.$this->n.' 0 R');
      $this->_out('/Info '.($this->n-1).' 0 R');
    }

    function _enddoc() {
      $this->_putheader();
      $pages_start_obj_number = $this->_putpages();

      $this->_putresources();

      //Info
      $this->_newobj();
      $this->_out('<<');
      $this->_putinfo();
      $this->_out('>>');
      $this->_out('endobj');

      // Form fields
      for ($i=0; $i<count($this->_forms); $i++) {
        $form =& $this->_forms[$i];

        $form->out($this);
      };

      //Catalog
      $this->_newobj();
      $this->_out('<<');
      $this->_putcatalog($pages_start_obj_number);
      $this->_out('>>');
      $this->_out('endobj');

      //Cross-ref
      $o=strlen($this->buffer);
      $this->_out('xref');
      $this->_out('0 '.($this->n+1));
      $this->_out('0000000000 65535 f ');

      for ($i=1; $i<=$this->n; $i++) {
        $this->_out(sprintf('%010d 00000 n ',$this->offsets[$i]));
      };

      //Trailer
      $this->_out('trailer');
      $this->_out('<<');
      $this->_puttrailer();
      $this->_out('>>');
      $this->_out('startxref');
      $this->_out($o);
      $this->_out('%%EOF');
      $this->state = FPDF_STATE_COMPLETED;
    }

    function _beginpage() {
      $this->page++;
      $this->pages[$this->page]='';
      $this->state = FPDF_STATE_PAGE_STARTED;
      $this->FontFamily='';
    }

    /**
     * End of page contents
     */
    function _endpage() {
      $this->state = FPDF_STATE_DOCUMENT_STARTED;
    }

    /**
     * Start a new indirect object
     */
    function _newobj() {
      $num = $this->_generate_new_object_number();
      $this->offsets[$num]=strlen($this->buffer);
      $this->_out($num.' 0 obj');
    }

    // Extract info from a JPEG file
    function _parsejpg($file) {
      $size_info = GetImageSize($file);
      if (!$size_info) {
        $this->Error('Missing or incorrect image file: '.$file);
      };

      if ($size_info[2]!=2) {
        $this->Error('Not a JPEG file: '.$file);
      };

      if (!isset($size_info['channels']) || $size_info['channels']==3) {
        $colspace='DeviceRGB';
      } elseif($size_info['channels']==4) {
        $colspace='DeviceCMYK';
      } else {
        $colspace='DeviceGray';
      };

      $bpc = isset($size_info['bits']) ? $size_info['bits'] : 8;

      //Read whole file
      $f=fopen($file,'rb');
      $data='';
      while (!feof($f)) {
        $data .= fread($f, 4096);
      };
      fclose($f);

      return array('w' => $size_info[0],
                   'h' => $size_info[1],
                   'cs' => $colspace,
                   'bpc' => $bpc,
                   'f' => 'DCTDecode',
                   'data' => $data);
    }

    // Extract info from a PNG file
    function _parsepng($file) {
      $f = fopen($file,'rb');
      if (!$f) {
        $this->Error('Can\'t open image file: '.$file);
      };

      //Check signature
      if (fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10)) {
        $this->Error('Not a PNG file: '.$file);
      };

      //Read header chunk
      fread($f,4);
      if (fread($f,4)!='IHDR') {
        $this->Error('Incorrect PNG file: '.$file);
      };

      $w = $this->_freadint($f);
      $h = $this->_freadint($f);
      $bpc = ord(fread($f,1));

      if ($bpc>8) {
        $this->Error('16-bit depth not supported: '.$file);
      };

      $ct=ord(fread($f,1));
      if ($ct==0) {
        $colspace='DeviceGray';
      } elseif($ct==2) {
        $colspace='DeviceRGB';
      } elseif($ct==3) {
        $colspace='Indexed';
      } else {
        $this->Error('Alpha channel not supported: '.$file);
      };

      if (ord(fread($f,1))!=0) {
        $this->Error('Unknown compression method: '.$file);
      };

      if (ord(fread($f,1))!=0) {
        $this->Error('Unknown filter method: '.$file);
      };

      if (ord(fread($f,1))!=0) {
        $this->Error('Interlacing not supported: '.$file);
      };

      fread($f,4);
      $parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';

      //Scan chunks looking for palette, transparency and image data
      $pal='';
      $trns='';
      $data='';
      do {
        $n=$this->_freadint($f);
        $type=fread($f,4);
        if ($type=='PLTE') {
          //Read palette
          $pal=fread($f,$n);
          fread($f,4);
        } elseif($type=='tRNS') {
          //Read transparency info
          $t=fread($f,$n);
          if ($ct==0) {
            $trns=array(ord(substr($t,1,1)));
          } elseif($ct==2) {
            $trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
          } else {
            $pos=strpos($t,chr(0));
            if ($pos!==false) {
              $trns=array($pos);
            }
          }
          fread($f,4);
        } elseif ($type=='IDAT') {
          //Read image data block
          $data.=fread($f,$n);
          fread($f,4);
        } elseif ($type=='IEND') {
          break;
        } else {
          fread($f,$n+4);
        };
      } while($n);

      if ($colspace=='Indexed' && empty($pal)) {
        $this->Error('Missing palette in '.$file);
      };
      fclose($f);
      return array('w'     => $w,
                   'h'     => $h,
                   'cs'    => $colspace,
                   'bpc'   => $bpc,
                   'f'     => 'FlateDecode',
                   'parms' => $parms,
                   'pal'   => $pal,
                   'trns'  => $trns,
                   'data'  => $data);
    }

    /**
     * Read a 4-byte integer from file
     */
    function _freadint($f) {
      $a=unpack('Ni',fread($f,4));
      return $a['i'];
    }

    /**
     * Format a text string
     */
    function _textstring($s) {
      return '('.$this->_escape($s).')';
    }

    /**
     * Add \ before \, ( and )
     */
    function _escape($s) {
      return str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$s)));
    }

    function _putstream($s) {
      $this->_out('stream');
      $this->_out($s);
      $this->_out('endstream');
    }

    /**
     * Add a line to the document
     */
    function _out($s) {
      if ($this->state == FPDF_STATE_PAGE_STARTED) {
        $this->pages[$this->page].=$s."\n";
      } else {
        $this->buffer.=$s."\n";
      }
    }
  }
}
?>
