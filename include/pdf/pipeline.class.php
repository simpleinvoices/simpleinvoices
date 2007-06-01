<?php

require_once('config.inc.php');

require_once('utils_array.php');
require_once('utils_graphic.php');
require_once('utils_url.php');
require_once('utils_text.php');
require_once('utils_units.php');
require_once('utils_number.php');

require_once('color.php');

require_once('config.parse.php');
require_once('systemcheck.php');

require_once('flow_context.class.inc.php');
require_once('flow_viewport.class.inc.php');

require_once('output._interface.class.php');
require_once('output._generic.class.php');
require_once('output._generic.pdf.class.php');
require_once('output._generic.ps.class.php');
require_once('output.pdflib.class.php');
require_once('output.fpdf.class.php');
require_once('output.fastps.class.php');
require_once('output.fastps.l2.class.php');

require_once('stubs.common.inc.php');

require_once('media.layout.inc.php');

require_once('box.php');
require_once('box.generic.php');
require_once('box.generic.formatted.php');
require_once('box.container.php');
require_once('box.generic.inline.php');
require_once('box.inline.php');
require_once('box.inline.control.php');

require_once('font.class.php');
require_once('font_factory.class.php');

require_once('box.br.php');
require_once('box.block.php');
require_once('box.body.php');
require_once('box.block.inline.php');
require_once('box.button.php');
require_once('box.button.submit.php');
require_once('box.button.reset.php');
require_once('box.checkbutton.php');
require_once('box.form.php');
require_once('box.frame.php');
require_once('box.iframe.php');
require_once('box.input.text.php');
require_once('box.input.textarea.php');
require_once('box.input.password.php');
require_once('box.legend.php');
require_once('box.list-item.php');
require_once('box.null.php');
require_once('box.radiobutton.php');
require_once('box.select.php');
require_once('box.table.php');
require_once('box.table.cell.php');
require_once('box.table.row.php');
require_once('box.table.section.php');

require_once('box.text.php');
require_once('box.text.string.php');
require_once('box.field.pageno.php');
require_once('box.field.pages.php');

require_once('box.whitespace.php');

require_once('box.img.php'); // Inherited from the text box!
require_once('box.input.img.php');

require_once('box.utils.text-align.inc.php');

require_once('manager.encoding.php');
require_once('encoding.inc.php');
require_once('encoding.entities.inc.php');
require_once('encoding.glyphs.inc.php');
require_once('encoding.iso-8859-1.inc.php');
require_once('encoding.iso-8859-2.inc.php');
require_once('encoding.iso-8859-3.inc.php');
require_once('encoding.iso-8859-4.inc.php');
require_once('encoding.iso-8859-5.inc.php');
require_once('encoding.iso-8859-7.inc.php');
require_once('encoding.iso-8859-9.inc.php');
require_once('encoding.iso-8859-10.inc.php');
require_once('encoding.iso-8859-11.inc.php');
require_once('encoding.iso-8859-13.inc.php');
require_once('encoding.iso-8859-14.inc.php');
require_once('encoding.iso-8859-15.inc.php');
require_once('encoding.koi8-r.inc.php');
require_once('encoding.cp866.inc.php');
require_once('encoding.windows-1250.inc.php');
require_once('encoding.windows-1251.inc.php');
require_once('encoding.windows-1252.inc.php');
require_once('encoding.dingbats.inc.php');
require_once('encoding.symbol.inc.php');

require_once('ps.unicode.inc.php');
require_once('ps.utils.inc.php');
require_once('ps.whitespace.inc.php');
require_once('ps.text.inc.php');

require_once('ps.image.encoder.inc.php');
require_once('ps.image.encoder.simple.inc.php');
require_once('ps.l2.image.encoder.stream.inc.php');
require_once('ps.l3.image.encoder.stream.inc.php');

require_once('tag.body.inc.php');
require_once('tag.font.inc.php');
require_once('tag.frame.inc.php');
require_once('tag.input.inc.php');
require_once('tag.img.inc.php');
require_once('tag.select.inc.php');
require_once('tag.span.inc.php');
require_once('tag.table.inc.php');
require_once('tag.td.inc.php');
require_once('tag.utils.inc.php');
require_once('tag.ulol.inc.php');

require_once('tree.navigation.inc.php');

require_once('html.attrs.inc.php');
require_once('html.list.inc.php');

require_once('xhtml.autoclose.inc.php');
require_once('xhtml.utils.inc.php');
require_once('xhtml.tables.inc.php');
require_once('xhtml.p.inc.php');
require_once('xhtml.lists.inc.php');
require_once('xhtml.deflist.inc.php');
require_once('xhtml.script.inc.php');
require_once('xhtml.entities.inc.php');
require_once('xhtml.comments.inc.php');
require_once('xhtml.style.inc.php');
require_once('xhtml.selects.inc.php');

require_once('background.php');
require_once('background.image.php');
require_once('background.position.php');

require_once('list-style.image.php');

require_once('height.php');
require_once('width.php');

require_once('css.inc.php');
require_once('css.utils.inc.php');
require_once('css.parse.inc.php');
require_once('css.parse.media.inc.php');
require_once('css.apply.inc.php');

require_once('css.background.color.inc.php');
require_once('css.background.image.inc.php');
require_once('css.background.repeat.inc.php');
require_once('css.background.position.inc.php');
require_once('css.background.inc.php');

require_once('css.border.inc.php');
require_once('css.border.style.inc.php');
require_once('css.border.collapse.inc.php');
require_once('css.bottom.inc.php');
require_once('css.clear.inc.php');
require_once('css.color.inc.php');
require_once('css.colors.inc.php');
require_once('css.content.inc.php');
require_once('css.display.inc.php');
require_once('css.float.inc.php');
require_once('css.font.inc.php');
require_once('css.height.inc.php');
require_once('css.left.inc.php');
require_once('css.line-height.inc.php');

require_once('css.list-style-image.inc.php');
require_once('css.list-style-position.inc.php');
require_once('css.list-style-type.inc.php');
require_once('css.list-style.inc.php');

require_once('css.margin.inc.php');
require_once('css.overflow.inc.php');
require_once('css.padding.inc.php');

require_once('css.page-break.inc.php');
require_once('css.page-break-after.inc.php');

require_once('css.position.inc.php');
require_once('css.right.inc.php');
require_once('css.rules.inc.php');
require_once('css.selectors.inc.php');
require_once('css.text-align.inc.php');
require_once('css.text-decoration.inc.php');
require_once('css.text-indent.inc.php');
require_once('css.top.inc.php');
require_once('css.vertical-align.inc.php');
require_once('css.visibility.inc.php');
require_once('css.white-space.inc.php');
require_once('css.width.inc.php');
require_once('css.z-index.inc.php');

require_once('css.pseudo.add.margin.inc.php');
require_once('css.pseudo.align.inc.php');
require_once('css.pseudo.cellspacing.inc.php');
require_once('css.pseudo.cellpadding.inc.php');
require_once('css.pseudo.form.action.inc.php');
require_once('css.pseudo.form.radiogroup.inc.php');
require_once('css.pseudo.link.destination.inc.php');
require_once('css.pseudo.link.target.inc.php');
require_once('css.pseudo.listcounter.inc.php');
require_once('css.pseudo.localalign.inc.php');
require_once('css.pseudo.nowrap.inc.php');
require_once('css.pseudo.table.border.inc.php');

// After all CSS utilities and constants have been initialized, load the default (precomiled) CSS stylesheet
require_once('css.defaults.inc.php');

require_once('localalign.inc.php');

require_once('converter.class.php');

require_once('treebuilder.class.php');
require_once('image.class.php');

require_once('fetched_data._interface.class.php');
require_once('fetched_data._html.class.php');
require_once('fetched_data.url.class.php');
require_once('fetched_data.file.class.php');

require_once('fetcher._interface.class.php');
require_once('fetcher.url.class.php');
require_once('fetcher.local.class.php');

require_once('filter.data._interface.class.php');
require_once('filter.data.doctype.class.php');
require_once('filter.data.utf8.class.php');
require_once('filter.data.html2xhtml.class.php');
require_once('filter.data.xhtml2xhtml.class.php');

require_once('parser._interface.class.php');
require_once('parser.xhtml.class.php');

require_once('filter.pre._interface.class.php');
require_once('filter.pre.fields.class.php');
require_once('filter.pre.headfoot.class.php');
require_once('filter.pre.height-constraint.class.php');

require_once('layout._interface.class.php');
require_once('layout.default.class.php');

require_once('filter.post._interface.class.php');

require_once('filter.output._interface.class.php');
require_once('filter.output.ps2pdf.class.php');
require_once('filter.output.gzip.class.php');

require_once('destination._interface.class.php');
require_once('destination._http.class.php');
require_once('destination.browser.class.php');
require_once('destination.download.class.php');
require_once('destination.file.class.php');

require_once('dom.php5.inc.php');
require_once('dom.activelink.inc.php');

require_once('xml.validation.inc.php');

require_once('content_type.class.php');

class Pipeline {
  var $fetchers;
  var $data_filters;
  var $error_message;
  var $parser;
  var $pre_tree_filters;
  var $layout_engine;
  var $post_tree_filters;
  var $output_driver;
  var $output_filters;
  var $destination;

  var $_base_url;

  function Pipeline() {
    $this->_base_url = array("");
  }

  function _process_item($data_id, &$media, $offset=0) {
    $g_css = array();

    $data = $this->fetch($data_id);
    if ($data == null) { return null; };

    // Run raw data filters
    for ($i=0; $i<count($this->data_filters); $i++) {
      $data = $this->data_filters[$i]->process($data);
    };

    // Parse the raw data
    $box =& $this->parser->process($data->get_content(), $this);

    // Run the height-constraint processing filter;
    // it is obligatory
    $filter = new PreTreeFilterHeightConstraint();
    $filter->process($box, $data, $this);

    // Run pre-layout tree filters
    for ($i=0; $i<count($this->pre_tree_filters); $i++) {
      $this->pre_tree_filters[$i]->process($box, $data, $this);
    };

    $context = $this->layout_engine->process($box, $media, $this->output_driver);
    if (is_null($context)) { return null; };

    // Run post-layout tree filters
    for ($i=0; $i<count($this->post_tree_filters); $i++) {
      $this->post_tree_filters[$i]->process($box);
    };

    $context->sort_absolute_positioned_by_z_index();

    // Make batch-processing offset
    $box->offset(0, $offset);
   
    // Output PDF pages using chosen PDF driver
    for ($i=0; $i<$this->output_driver->get_expected_pages(); $i++) {
      $this->output_driver->save();
      $this->output_driver->setup_clip();

      if (is_null($box->show($this->output_driver))) { 
        return null; 
      };

      // Absolute positioned boxes should be shown after all other boxes, because 
      // they're placed higher in the stack-order
      for ($j=0; $j<count($context->absolute_positioned); $j++) {
        if ($context->absolute_positioned[$j]->visibility === VISIBILITY_VISIBLE) {
          if (is_null($context->absolute_positioned[$j]->show($this->output_driver))) {
            return null;
          };
        };
      };

      $this->output_driver->restore();

      for ($j=0; $j<count($context->fixed_positioned); $j++) {
        if ($context->fixed_positioned[$j]->visibility === VISIBILITY_VISIBLE) {
          if (is_null($context->fixed_positioned[$j]->show_fixed($this->output_driver))) { 
            return null;
          };
        };
      };

      global $g_config;
      if ($g_config['draw_page_border']) { 
        $this->output_driver->draw_page_border(); 
      };

      // Add page if currently rendered page is not last
      if ($i < $this->output_driver->get_expected_pages()-1) { 
        $this->output_driver->next_page(); 
      };
    };

    // Clear CSS for this item 
    return true;
  }

  function _output() {
    $filename = $this->output_driver->get_filename();

    for ($i=0; $i<count($this->output_filters); $i++) {
      $filename = $this->output_filters[$i]->process($filename);
    };

    // Determine the content type of the result
    $content_type = null;
    $i = count($this->output_filters)-1;
    while (($i >= 0) && (is_null($content_type))) {
      $content_type = $this->output_filters[$i]->content_type();
      $i--;
    };

    if (is_null($content_type)) {
      $content_type = $this->output_driver->content_type();
    };

    $this->destination->process($filename, $content_type);
  }

  function fetch($data_id) {
    if (count($this->fetchers) == 0) { 
      ob_start();
      include(HTML2PS_DIR.'/templates/error._no_fetchers.tpl');
      $this->error_message = ob_get_contents();
      ob_end_clean();

      return null; 
    };

    // Fetch data
    for ($i=0; $i<count($this->fetchers); $i++) {
      $data = $this->fetchers[$i]->get_data($data_id);

      if ($data != null) {
        $this->push_base_url($this->fetchers[$i]->get_base_url());
        
        return $data;
      };
    };

    return null;
  }
  
  function process($data_id, &$media) {
    global $g_css;

    global $g_media;
    $g_media = $media;

    $this->output_driver->reset($media);
    if (!$this->_process_item($data_id, $media)) {
      print($this->error_message());
      die();
    };
                                                          
    $this->output_driver->close();
    $this->_output();
    $this->output_driver->release();   

    // Non HTML-specific cleanup
    //
    Image::clear_cache();

    global $g_css;
    $g_css = array();
    global $g_css_obj;
    $g_css_obj = new CSSObject();

    return true;
  }

  /**
   * Processes an set of URLs ot once; every URL is rendered on the separate page and 
   * merged to one PDF file.
   *
   * Note: to reduce peak memory requirement, URLs are processed one-after-one.
   *
   * @param Array $data_id_array Array of page identifiers to be processed (usually URLs or files paths)
   * @param Media $media Object describing the media to render for (size, margins, orientaiton & resolution)
   */
  function process_batch($data_id_array, &$media) {
    $this->output_driver->reset($media);

    $i = 0;
    $offset = 0;
    foreach ($data_id_array as $data_id) {      
      $this->_process_item($data_id, $media, $offset);

      $i++;
      if ($i<count($data_id_array)) {
        $this->output_driver->next_page();
        $offset = $this->output_driver->offset;
      };
    };
    $this->output_driver->close();
    $this->_output();
    $this->output_driver->release();   

    // Non HTML-specific cleanup
    //
    Image::clear_cache();

    return true;
  }

  function error_message() {
    $message = file_get_contents(HTML2PS_DIR.'/templates/error._header.tpl');

    $message .= $this->error_message;

    for ($i=0; $i<count($this->fetchers); $i++) {
      $message .= $this->fetchers[$i]->error_message();
    };

    $message .= $this->output_driver->error_message();
    
    $message .= file_get_contents(HTML2PS_DIR.'/templates/error._footer.tpl');
    return $message;
  }

  function push_base_url($url) {
    array_unshift($this->_base_url, $url);
  }

  function pop_base_url() {
    array_shift($this->_base_url);
  }

  function get_base_url() {
    return $this->_base_url[0];
  }

  function guess_url($src) {
    return guess_url($src, $this->get_base_url());
  }
}

?>