<?php

require_once(HTML2PS_DIR.'utils_array.php');
require_once(HTML2PS_DIR.'utils_graphic.php');
require_once(HTML2PS_DIR.'utils_url.php');
require_once(HTML2PS_DIR.'utils_text.php');
require_once(HTML2PS_DIR.'utils_units.php');
require_once(HTML2PS_DIR.'utils_number.php');

require_once(HTML2PS_DIR.'value.color.php');

require_once(HTML2PS_DIR.'config.parse.php');

require_once(HTML2PS_DIR.'flow_context.class.inc.php');
require_once(HTML2PS_DIR.'flow_viewport.class.inc.php');

require_once(HTML2PS_DIR.'output._interface.class.php');
require_once(HTML2PS_DIR.'output._generic.class.php');
require_once(HTML2PS_DIR.'output._generic.pdf.class.php');
require_once(HTML2PS_DIR.'output._generic.ps.class.php');
require_once(HTML2PS_DIR.'output.pdflib.old.class.php');
require_once(HTML2PS_DIR.'output.pdflib.1.6.class.php');
require_once(HTML2PS_DIR.'output.fpdf.class.php');
require_once(HTML2PS_DIR.'output.fastps.class.php');
require_once(HTML2PS_DIR.'output.fastps.l2.class.php');
require_once(HTML2PS_DIR.'output.png.class.php');
// require_once(HTML2PS_DIR.'output.pcl.class.php');

require_once(HTML2PS_DIR.'stubs.common.inc.php');

require_once(HTML2PS_DIR.'media.layout.inc.php');

require_once(HTML2PS_DIR.'box.php');
require_once(HTML2PS_DIR.'box.generic.php');
require_once(HTML2PS_DIR.'box.generic.formatted.php');
require_once(HTML2PS_DIR.'box.container.php');
require_once(HTML2PS_DIR.'box.generic.inline.php');
require_once(HTML2PS_DIR.'box.inline.php');
require_once(HTML2PS_DIR.'box.inline.control.php');

require_once(HTML2PS_DIR.'font.class.php');
require_once(HTML2PS_DIR.'font_factory.class.php');

require_once(HTML2PS_DIR.'box.br.php');
require_once(HTML2PS_DIR.'box.block.php');
require_once(HTML2PS_DIR.'box.page.php');
require_once(HTML2PS_DIR.'box.page.margin.class.php');
require_once(HTML2PS_DIR.'box.body.php');
require_once(HTML2PS_DIR.'box.block.inline.php');
require_once(HTML2PS_DIR.'box.button.php');
require_once(HTML2PS_DIR.'box.button.submit.php');
require_once(HTML2PS_DIR.'box.button.reset.php');
require_once(HTML2PS_DIR.'box.checkbutton.php');
require_once(HTML2PS_DIR.'box.form.php');
require_once(HTML2PS_DIR.'box.frame.php');
require_once(HTML2PS_DIR.'box.iframe.php');
require_once(HTML2PS_DIR.'box.input.text.php');
require_once(HTML2PS_DIR.'box.input.textarea.php');
require_once(HTML2PS_DIR.'box.input.password.php');
require_once(HTML2PS_DIR.'box.legend.php');
require_once(HTML2PS_DIR.'box.list-item.php');
require_once(HTML2PS_DIR.'box.null.php');
require_once(HTML2PS_DIR.'box.radiobutton.php');
require_once(HTML2PS_DIR.'box.select.php');
require_once(HTML2PS_DIR.'box.table.php');
require_once(HTML2PS_DIR.'box.table.cell.php');
require_once(HTML2PS_DIR.'box.table.cell.fake.php');
require_once(HTML2PS_DIR.'box.table.row.php');
require_once(HTML2PS_DIR.'box.table.section.php');

require_once(HTML2PS_DIR.'box.text.php');
require_once(HTML2PS_DIR.'box.text.string.php');
require_once(HTML2PS_DIR.'box.field.pageno.php');
require_once(HTML2PS_DIR.'box.field.pages.php');

require_once(HTML2PS_DIR.'box.whitespace.php');

require_once(HTML2PS_DIR.'box.img.php'); // Inherited from the text box!
require_once(HTML2PS_DIR.'box.input.img.php');

require_once(HTML2PS_DIR.'box.utils.text-align.inc.php');

require_once(HTML2PS_DIR.'manager.encoding.php');

require_once(HTML2PS_DIR.'ps.unicode.inc.php');
require_once(HTML2PS_DIR.'ps.utils.inc.php');
require_once(HTML2PS_DIR.'ps.whitespace.inc.php');

require_once(HTML2PS_DIR.'ps.image.encoder.inc.php');
require_once(HTML2PS_DIR.'ps.image.encoder.simple.inc.php');
require_once(HTML2PS_DIR.'ps.l2.image.encoder.stream.inc.php');
require_once(HTML2PS_DIR.'ps.l3.image.encoder.stream.inc.php');

require_once(HTML2PS_DIR.'tag.body.inc.php');
require_once(HTML2PS_DIR.'tag.font.inc.php');
require_once(HTML2PS_DIR.'tag.frame.inc.php');
require_once(HTML2PS_DIR.'tag.input.inc.php');
require_once(HTML2PS_DIR.'tag.img.inc.php');
require_once(HTML2PS_DIR.'tag.select.inc.php');
require_once(HTML2PS_DIR.'tag.span.inc.php');
require_once(HTML2PS_DIR.'tag.table.inc.php');
require_once(HTML2PS_DIR.'tag.td.inc.php');
require_once(HTML2PS_DIR.'tag.utils.inc.php');

require_once(HTML2PS_DIR.'tree.navigation.inc.php');

require_once(HTML2PS_DIR.'html.attrs.inc.php');

require_once(HTML2PS_DIR.'xhtml.autoclose.inc.php');
require_once(HTML2PS_DIR.'xhtml.utils.inc.php');
require_once(HTML2PS_DIR.'xhtml.tables.inc.php');
require_once(HTML2PS_DIR.'xhtml.p.inc.php');
require_once(HTML2PS_DIR.'xhtml.lists.inc.php');
require_once(HTML2PS_DIR.'xhtml.deflist.inc.php');
require_once(HTML2PS_DIR.'xhtml.script.inc.php');
require_once(HTML2PS_DIR.'xhtml.entities.inc.php');
require_once(HTML2PS_DIR.'xhtml.comments.inc.php');
require_once(HTML2PS_DIR.'xhtml.style.inc.php');
require_once(HTML2PS_DIR.'xhtml.selects.inc.php');

require_once(HTML2PS_DIR.'background.image.php');
require_once(HTML2PS_DIR.'background.position.php');

require_once(HTML2PS_DIR.'list-style.image.php');

require_once(HTML2PS_DIR.'height.php');
require_once(HTML2PS_DIR.'width.php');

require_once(HTML2PS_DIR.'css.counter.php');
require_once(HTML2PS_DIR.'css.counter.collection.php');

require_once(HTML2PS_DIR.'css.colors.inc.php');

require_once(HTML2PS_DIR.'css.constants.inc.php');
require_once(HTML2PS_DIR.'css.inc.php');
require_once(HTML2PS_DIR.'css.state.class.php');
require_once(HTML2PS_DIR.'css.cache.class.php');
require_once(HTML2PS_DIR.'css.property.handler.class.php');
require_once(HTML2PS_DIR.'css.property.stringset.class.php');
require_once(HTML2PS_DIR.'css.property.sub.class.php');
require_once(HTML2PS_DIR.'css.property.sub.field.class.php');
require_once(HTML2PS_DIR.'css.utils.inc.php');
require_once(HTML2PS_DIR.'css.parse.inc.php');
require_once(HTML2PS_DIR.'css.parse.media.inc.php');

require_once(HTML2PS_DIR.'css.background.attachment.inc.php');
require_once(HTML2PS_DIR.'css.background.color.inc.php');
require_once(HTML2PS_DIR.'css.background.image.inc.php');
require_once(HTML2PS_DIR.'css.background.repeat.inc.php');
require_once(HTML2PS_DIR.'css.background.position.inc.php');
require_once(HTML2PS_DIR.'css.background.inc.php');

require_once(HTML2PS_DIR.'css.border.inc.php');
require_once(HTML2PS_DIR.'css.border.style.inc.php');
require_once(HTML2PS_DIR.'css.border.collapse.inc.php');
require_once(HTML2PS_DIR.'css.bottom.inc.php');
require_once(HTML2PS_DIR.'css.clear.inc.php');
require_once(HTML2PS_DIR.'css.color.inc.php');
require_once(HTML2PS_DIR.'css.direction.inc.php');
require_once(HTML2PS_DIR.'css.html2ps.html.content.inc.php');
require_once(HTML2PS_DIR.'css.html2ps.pseudoelements.inc.php');
require_once(HTML2PS_DIR.'css.html2ps.pixels.php');
require_once(HTML2PS_DIR.'css.content.inc.php');
require_once(HTML2PS_DIR.'css.display.inc.php');
require_once(HTML2PS_DIR.'css.float.inc.php');
require_once(HTML2PS_DIR.'css.font.inc.php');
require_once(HTML2PS_DIR.'css.height.inc.php');
require_once(HTML2PS_DIR.'css.min-height.inc.php');
require_once(HTML2PS_DIR.'css.max-height.inc.php');
require_once(HTML2PS_DIR.'css.left.inc.php');
require_once(HTML2PS_DIR.'css.letter-spacing.inc.php');

require_once(HTML2PS_DIR.'css.list-style-image.inc.php');
require_once(HTML2PS_DIR.'css.list-style-position.inc.php');
require_once(HTML2PS_DIR.'css.list-style-type.inc.php');
require_once(HTML2PS_DIR.'css.list-style.inc.php');

require_once(HTML2PS_DIR.'css.margin.inc.php');
require_once(HTML2PS_DIR.'css.overflow.inc.php');
require_once(HTML2PS_DIR.'css.padding.inc.php');

require_once(HTML2PS_DIR.'css.page.inc.php');
require_once(HTML2PS_DIR.'css.page-break.inc.php');
require_once(HTML2PS_DIR.'css.page-break-after.inc.php');
require_once(HTML2PS_DIR.'css.page-break-before.inc.php');
require_once(HTML2PS_DIR.'css.page-break-inside.inc.php');
require_once(HTML2PS_DIR.'css.orphans.inc.php');
require_once(HTML2PS_DIR.'css.size.inc.php');
require_once(HTML2PS_DIR.'css.widows.inc.php');

require_once(HTML2PS_DIR.'css.position.inc.php');
require_once(HTML2PS_DIR.'css.right.inc.php');
require_once(HTML2PS_DIR.'css.property.declaration.php');
require_once(HTML2PS_DIR.'css.rules.inc.php');
require_once(HTML2PS_DIR.'css.ruleset.class.php');
require_once(HTML2PS_DIR.'css.selectors.inc.php');
require_once(HTML2PS_DIR.'css.table-layout.inc.php');
require_once(HTML2PS_DIR.'css.text-align.inc.php');
require_once(HTML2PS_DIR.'css.text-decoration.inc.php');
require_once(HTML2PS_DIR.'css.text-transform.inc.php');
require_once(HTML2PS_DIR.'css.text-indent.inc.php');
require_once(HTML2PS_DIR.'css.top.inc.php');
require_once(HTML2PS_DIR.'css.vertical-align.inc.php');
require_once(HTML2PS_DIR.'css.visibility.inc.php');
require_once(HTML2PS_DIR.'css.white-space.inc.php');
require_once(HTML2PS_DIR.'css.width.inc.php');
require_once(HTML2PS_DIR.'css.word-spacing.inc.php');
require_once(HTML2PS_DIR.'css.z-index.inc.php');

require_once(HTML2PS_DIR.'css.pseudo.add.margin.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.align.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.cellspacing.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.cellpadding.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.form.action.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.form.radiogroup.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.link.destination.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.link.target.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.listcounter.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.localalign.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.nowrap.inc.php');
require_once(HTML2PS_DIR.'css.pseudo.table.border.inc.php');

// After all CSS utilities and constants have been initialized, load the default (precomiled) CSS stylesheet
require_once(HTML2PS_DIR.'converter.class.php');
require_once(HTML2PS_DIR.'treebuilder.class.php');
require_once(HTML2PS_DIR.'image.class.php');

require_once(HTML2PS_DIR.'fetched_data._interface.class.php');
require_once(HTML2PS_DIR.'fetched_data._html.class.php');
require_once(HTML2PS_DIR.'fetched_data.url.class.php');
require_once(HTML2PS_DIR.'fetched_data.file.class.php');

require_once(HTML2PS_DIR.'filter.data._interface.class.php');
require_once(HTML2PS_DIR.'filter.data.doctype.class.php');

require_once(HTML2PS_DIR.'filter.data.utf8.class.php');
require_once(HTML2PS_DIR.'filter.data.ucs2.class.php');

require_once(HTML2PS_DIR.'filter.data.html2xhtml.class.php');
require_once(HTML2PS_DIR.'filter.data.xhtml2xhtml.class.php');

require_once(HTML2PS_DIR.'parser._interface.class.php');
require_once(HTML2PS_DIR.'parser.xhtml.class.php');

require_once(HTML2PS_DIR.'filter.pre._interface.class.php');
require_once(HTML2PS_DIR.'filter.pre.fields.class.php');
require_once(HTML2PS_DIR.'filter.pre.headfoot.class.php');
require_once(HTML2PS_DIR.'filter.pre.footnotes.class.php');
require_once(HTML2PS_DIR.'filter.pre.height-constraint.class.php');

require_once(HTML2PS_DIR.'layout._interface.class.php');
require_once(HTML2PS_DIR.'layout.default.class.php');
require_once(HTML2PS_DIR.'layout.page.breaks.php');

require_once(HTML2PS_DIR.'filter.post._interface.class.php');
require_once(HTML2PS_DIR.'filter.post.positioned.class.php');
require_once(HTML2PS_DIR.'filter.post.postponed.class.php');

require_once(HTML2PS_DIR.'filter.output._interface.class.php');
require_once(HTML2PS_DIR.'filter.output.ps2pdf.class.php');
require_once(HTML2PS_DIR.'filter.output.gzip.class.php');

require_once(HTML2PS_DIR.'destination._interface.class.php');
require_once(HTML2PS_DIR.'destination._http.class.php');
require_once(HTML2PS_DIR.'destination.browser.class.php');
require_once(HTML2PS_DIR.'destination.download.class.php');
require_once(HTML2PS_DIR.'destination.file.class.php');

require_once(HTML2PS_DIR.'xml.validation.inc.php');

require_once(HTML2PS_DIR.'content_type.class.php');
require_once(HTML2PS_DIR.'dispatcher.class.php');
require_once(HTML2PS_DIR.'observer.class.php');

require_once(HTML2PS_DIR.'strategy.page.break.simple.php');
require_once(HTML2PS_DIR.'strategy.page.break.smart.php');

require_once(HTML2PS_DIR.'strategy.link.rendering.normal.php');
require_once(HTML2PS_DIR.'strategy.position.absolute.php');
require_once(HTML2PS_DIR.'strategy.width.absolute.positioned.php');
require_once(HTML2PS_DIR.'autofix.url.php');

require_once(HTML2PS_DIR.'fetcher._interface.class.php');
require_once(HTML2PS_DIR.'features/_factory.php');

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

  var $_page_at_rules;
  var $_counters;
  var $_footnotes;

  var $_cssState;
  var $_css;
  var $_defaultCSS;

  var $_dispatcher;

  var $_current_page_name;

  var $_page_break_strategy;

  function Pipeline() {
    $this->_counters = array();
    $this->_footnotes = array();

    $this->_base_url = array('');
    $this->_reset_page_at_rules();

    $this->pre_tree_filters = array();

    $this->_dispatcher =& new Dispatcher();

    $this->_dispatcher->add_event('before-page-heights');
    $this->_dispatcher->add_event('before-page');
    $this->_dispatcher->add_event('after-page');
    $this->_dispatcher->add_event('before-batch-item');
    $this->_dispatcher->add_event('after-batch-item');
    $this->_dispatcher->add_event('after-parse');
    $this->_dispatcher->add_event('before-document');
    $this->_dispatcher->add_event('after-document');
    $this->_dispatcher->add_event('before-batch');
    $this->_dispatcher->add_event('after-batch');

    $this->_page_break_strategy = new StrategyPageBreakSimple();
  }

  function add_feature($feature_name, $params = array()) {
    $feature_object =& FeatureFactory::get($feature_name);
    if (is_null($feature_object)) {
      die(sprintf('No feature "%s" found', $feature_name));
    };

    $feature_object->install($this, $params);
  }

  function add_fetcher(&$fetcher) {
    array_unshift($this->fetchers, $fetcher);
  }

  function calculate_page_heights(&$media, &$box) {
    return $this->_page_break_strategy->run($this, $media, $box);
  }

  function clear_box_id_map() {
    $GLOBALS['__html_box_id_map'] = array();
  }

  function close() {
    $this->_dispatcher->fire('after-batch', array('pipeline' => &$this));

    $this->output_driver->close();
    $this->_output();
    $this->output_driver->release();   

    // Non HTML-specific cleanup
    //
    Image::clear_cache();
  }

  function configure($options) {
    $defaults = array('compress'      => false,
                      'cssmedia'      => 'screen',
                      'debugbox'      => false,
                      'debugnoclip'   => false,
                      'draw_page_border' => false,
                      'encoding'      => '',
                      'html2xhtml'    => true,
                      'imagequality_workaround' => false,
                      'landscape'     => false,
                      'margins'       => array('left' => 30,
                                               'right' => 15,
                                               'top' => 15,
                                               'bottom' => 15),
                      'media'         => 'A4',
                      'method'        => 'fpdf',
                      'mode'          => 'html',
                      'output'        => 0,
                      'pagewidth'     => 800,
                      'pdfversion'    => "1.2",
                      'ps2pdf'        => false,
                      'pslevel'       => 3,
                      'renderfields'  => false,
                      'renderforms'   => false,
                      'renderimages'  => true,
                      'renderlinks'   => false,
                      'scalepoints'   => true,
                      'smartpagebreak' => true,
                      'transparency_workaround' => false
                      );

    // As a reminder: If the input arrays have the same string keys, then the later value for that key will overwrite the previous one.
    $GLOBALS['g_config'] = array_merge($defaults, $options);

    // Note that CSS media names should be case-insensitive
    $GLOBALS['g_config']['cssmedia'] = strtolower($GLOBALS['g_config']['cssmedia']);

    if ($GLOBALS['g_config']['smartpagebreak']) {
      $this->_page_break_strategy = new StrategyPageBreakSmart();
    } else {
      $this->_page_break_strategy = new StrategyPageBreakSimple();
    };
  }

  function _addFootnote(&$note_call) {
    $this->_footnotes[] =& $note_call;
  }

//   function _fillContent($content) {
//     $filled = "";

//     while (preg_match("/^.*?('.*?'|\".*?\"|counter\(.*?\))(.*)$/", $content, $matches)) {
//       $data    = $matches[1];
//       $content = $matches[2];
      
//       if ($data{0} != '\'' && $data{0} != '"') {
//         $filled .= $this->_fillContentCounter($data);
//       } else {
//         $filled .= $this->_fillContentString($data);
//       };
//     };

//     return $filled;
//   }

//   function _fillContentString($content) {
//     $unescaped_content = css_process_escapes($content);
//     $unquoted_content = css_remove_value_quotes($unescaped_content);
//     return $unquoted_content;
//   }

//   function _fillContentCounter($content) {
//     preg_match("/counter\((.*?)\)/", $content, $matches);
//     return $this->_getCounter($matches[1]);
//   }

  function &get_counters() {
    $counter_collection =& new CSSCounterCollection();

    foreach ($this->_counters as $counter_name => $counter_value) {
      $counter =& new CSSCounter($counter_name);
      $counter->set($counter_value);
      $counter_collection->add($counter);
    };

    return $counter_collection;
  }

  function &get_dispatcher() {
    return $this->_dispatcher;
  }

  function _getCounter($counter) {
    if (isset($this->_counters[$counter])) { 
      return $this->_counters[$counter];
    };

    /**
     * CSS  2.1:   Counters  that  are   not  in  the  scope   of  any
     * 'counter-reset',  are assumed  to have  been  reset to  0 by  a
     * 'counter-reset' on the root element.
     */
    return 0;
  }

  function _resetCounter($counter, $value) {
    $this->_counters[$counter] = $value;
  }

  function _incrementCounter($counter, $value) {
    $this->_counters[$counter] += $value;
  }

  function add_at_rule_page($at_rule) {
    $selector =& $at_rule->getSelector();
    $type = $selector->get_type();
    $this->_page_at_rules[$type][] = $at_rule;
  }

  function _reset_page_at_rules() {
    $this->_page_at_rules = array(CSS_PAGE_SELECTOR_ALL   => array(),
                                  CSS_PAGE_SELECTOR_FIRST => array(),
                                  CSS_PAGE_SELECTOR_LEFT  => array(),
                                  CSS_PAGE_SELECTOR_RIGHT => array(),
                                  CSS_PAGE_SELECTOR_NAMED => array());
  }

  function &getDefaultCSS() {
    return $this->_defaultCSS;
  }

  function &getCurrentCSS() {
    return $this->_css[0];
  }

  function &getCurrentCSSState() {
    return $this->_cssState[0];
  }

  function pushCSS() {
    array_unshift($this->_css, new CSSRuleset());
  }

  function popCSS() {
    array_shift($this->_css);
  }

  /**
   * Note that different pages  may define different margin boxes (for
   * example,  left and right  pages may  have different  headers). In
   * this  case, we  should  process  @page rules  in  order of  their
   * specificity (no selector  < :left / :right <  :first) and extract
   * margin boxes to be drawn
   *
   * @param $page_no Integer current page index (1-based)
   * @param $media 
   */
  function render_margin_boxes($page_no, &$media) {
    $boxes =& $this->reflow_margin_boxes($page_no, $media);

    foreach ($boxes as $selector => $box) {
      $boxes[$selector]->show($this->output_driver);
    };

    // Memleak fix
    for ($i=0, $size = count($boxes); $i < $size; $i++) {
      $boxes[$i]->destroy();
    };
    unset($boxes);
  }

  function get_page_media($page_no, &$media) {
    $page_rules =& $this->get_page_rules($page_no);
    $size_landscape = $page_rules->getPropertyValue(CSS_SIZE);
    if (!is_null($size_landscape)) {
      $media->set_width($size_landscape['size']['width']);
      $media->set_height($size_landscape['size']['height']);
      $media->set_landscape($size_landscape['landscape']);
    };

    $margins = $page_rules->getPropertyValue(CSS_MARGIN);
    if (!is_null($margins)) {
      $media->margins['left'] = $margins->left->calc(mm2pt($media->get_width())) / mm2pt(1) / pt2pt(1);
      $media->margins['right'] = $margins->right->calc(mm2pt($media->get_width())) / mm2pt(1) / pt2pt(1);
      $media->margins['top'] = $margins->top->calc(mm2pt($media->get_height())) / mm2pt(1) / pt2pt(1);
      $media->margins['bottom'] = $margins->bottom->calc(mm2pt($media->get_height())) / mm2pt(1) / pt2pt(1);
    };

    $left_margin = $page_rules->getPropertyValue(CSS_MARGIN_LEFT);
    if (!is_null($left_margin)) {
      $media->margins['left'] = $left_margin->calc(mm2pt($media->get_width())) / mm2pt(1) / pt2pt(1);
    };

    $right_margin = $page_rules->getPropertyValue(CSS_MARGIN_RIGHT);
    if (!is_null($right_margin)) {
      $media->margins['right'] = $right_margin->calc(mm2pt($media->get_width())) / mm2pt(1) / pt2pt(1);
    };

    $top_margin = $page_rules->getPropertyValue(CSS_MARGIN_TOP);
    if (!is_null($top_margin)) {
      $media->margins['top'] = $top_margin->calc(mm2pt($media->get_height())) / mm2pt(1) / pt2pt(1);
    };

    $bottom_margin = $page_rules->getPropertyValue(CSS_MARGIN_BOTTOM);
    if (!is_null($bottom_margin)) {
      $media->margins['bottom'] = $bottom_margin->calc(mm2pt($media->get_height())) / mm2pt(1) / pt2pt(1);
    };

    $pixels = $page_rules->getPropertyValue(CSS_HTML2PS_PIXELS);
    if (!is_null($pixels)) {
      $media->set_pixels($pixels);
    };
  }

  function &get_page_rules($page_no) {
    $collection =& new CSSPropertyCollection();

    foreach ($this->_page_at_rules[CSS_PAGE_SELECTOR_ALL] as $rule) {
      $collection->merge($rule->css);
    };

    /**
     * Check which one of :right/:left selector is applicable (assuming that first page matches :right)
     */
    if ($page_no % 2 == 0) {
      foreach ($this->_page_at_rules[CSS_PAGE_SELECTOR_LEFT] as $rule) {
        $collection->merge($rule->css);
      };
    } else {
      foreach ($this->_page_at_rules[CSS_PAGE_SELECTOR_RIGHT] as $rule) {
        $collection->merge($rule->css);
      };
    };

    if ($page_no == 1) {
      foreach ($this->_page_at_rules[CSS_PAGE_SELECTOR_FIRST] as $rule) {
        $collection->merge($rule->css);
      };
    };

    return $collection;
  }

  function &reflow_page_box($page_no, &$media) {
    $rules =& $this->get_page_rules($page_no);
    $box =& BoxPage::create($this, $rules);
    $box->reflow($media);
    return $box;
  }

  function render_page_box($page_no, &$media) {
    $box =& $this->reflow_page_box($page_no, $media);
    $box->show($this->output_driver);
    $box->destroy();
    unset($box);
  }

  function &reflow_margin_boxes($page_no, &$media) {
    $at_rules = $this->_getMarginBoxes($page_no, $media);

    $boxes = array();
    foreach ($at_rules as $at_rule) {
      $selector = $at_rule->getSelector();
      $boxes[$selector] =& BoxPageMargin::create($this, $at_rule);
    };

    foreach ($boxes as $selector => $box) {
      $linebox_started     = false;
      $previous_whitespace = false;
      $boxes[$selector]->reflow_whitespace($linebox_started, $previous_whitespace);
      $boxes[$selector]->reflow_text($this->output_driver);
    };

    foreach ($boxes as $selector => $box) {
      $boxes[$selector]->reflow($this->output_driver, 
                                $media,
                                $boxes);
    };

    return $boxes;
  }

  /**
   * Note that "+" operation on arrays will preserve existing elements; thus
   * we need to process @page rules in order of decreasing specificity
   *
   */
  function _getMarginBoxes($page_no, $media) {
    $applicable_margin_boxes = array();

    /**
     * Check if :first selector is applicable
     */
    if ($page_no == 1) {
      foreach ($this->_page_at_rules[CSS_PAGE_SELECTOR_FIRST] as $rule) {
        $applicable_margin_boxes = $applicable_margin_boxes + $rule->getAtRuleMarginBoxes();
      };
    };

    /**
     * Check which one of :right/:left selector is applicable (assuming that first page matches :right)
     */
    if ($page_no % 2 == 0) {
      foreach ($this->_page_at_rules[CSS_PAGE_SELECTOR_LEFT] as $rule) {
        $applicable_margin_boxes = $applicable_margin_boxes +  $rule->getAtRuleMarginBoxes();
      };
    } else {
      foreach ($this->_page_at_rules[CSS_PAGE_SELECTOR_RIGHT] as $rule) {
        $applicable_margin_boxes = $applicable_margin_boxes +  $rule->getAtRuleMarginBoxes();
      };
    };

    /**
     * Extract margin boxes from plain @page rules
     */
    foreach ($this->_page_at_rules[CSS_PAGE_SELECTOR_ALL] as $rule) {
      $applicable_margin_boxes = $applicable_margin_boxes + $rule->getAtRuleMarginBoxes();
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_TOP,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP_LEFT_CORNER])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP_LEFT_CORNER] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_TOP_LEFT_CORNER,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP_LEFT])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP_LEFT] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_TOP_LEFT,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP_CENTER])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP_CENTER] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_TOP_CENTER,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT_CORNER])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT_CORNER] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_TOP_RIGHT_CORNER,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_BOTTOM,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT_CORNER])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT_CORNER] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT_CORNER,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_BOTTOM_LEFT,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_CENTER])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_CENTER] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_BOTTOM_CENTER,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT_CORNER])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT_CORNER] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_BOTTOM_RIGHT_CORNER,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_TOP])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_TOP] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_LEFT_TOP,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_MIDDLE])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_MIDDLE] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_LEFT_MIDDLE,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_BOTTOM])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_LEFT_BOTTOM] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_LEFT_BOTTOM,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_RIGHT_TOP])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_RIGHT_TOP] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_RIGHT_TOP,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_RIGHT_MIDDLE])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_RIGHT_MIDDLE] =& 
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_RIGHT_MIDDLE,$this); 
    };

    if (!isset($applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_RIGHT_BOTTOM])) { 
      $applicable_margin_boxes[CSS_MARGIN_BOX_SELECTOR_RIGHT_BOTTOM] =&
        new CSSAtRuleMarginBox(CSS_MARGIN_BOX_SELECTOR_RIGHT_BOTTOM,$this); 
    };

    return $applicable_margin_boxes;
  }

  function _process_item($data_id, &$media, $offset=0) {    
    $this->_dispatcher->fire('before-batch-item', array('pipeline' => &$this));

    $box =& $this->_layout_item($data_id, $media, $offset, $context, $postponed_filter);

    if (is_null($box)) {
      error_log(sprintf(_('Could not fetch: %s'), (string)$data_id));
      return true;
    };

    $this->_show_item($box, $offset, $context, $media, $postponed_filter);

    // Clear CSS for this item 
    $this->popCSS();
    $this->_defaultCSS = null;

    // Memory leak fix: caused by circular references?
    $box->destroy();

    $this->_dispatcher->fire('after-batch-item', array('pipeline' => &$this));
    return true;
  }

  function _show_item(&$box, $offset, &$context, &$media, &$postponed_filter) {
    $context->sort_absolute_positioned_by_z_index();

    $this->_dispatcher->fire('before-page-heights', array('pipeline' => &$this, 
                                                          'document' => &$box,
                                                          'media' => &$media));

    // Make batch-processing offset
    $page_heights = $this->calculate_page_heights($media, $box);
    $box->offset(0, $offset);

    $box->reflow_anchors($this->output_driver, $this->output_driver->anchors, $page_heights);
    
    $this->_dispatcher->fire('before-document', array('pipeline' => &$this, 
                                                      'document' => &$box,
                                                      'page-heights' => &$page_heights,
                                                      'media' => &$media));

    $expected_pages = count($page_heights);
    $this->output_driver->set_expected_pages($expected_pages);
    $this->_resetCounter('pages', $expected_pages);
    $this->_resetCounter('page',  0);

    // Output PDF pages using chosen PDF driver
    for ($i=0; $i<$expected_pages; $i++) {
      $this->get_page_media(1, $media);

      $this->output_driver->update_media($media);
      $this->_setupScales($media);

      $current_page_offset = $i == 0 ? 0 : $page_heights[$i-1];
      $current_page_height = $page_heights[$i];

      $this->output_driver->next_page($current_page_offset);

      // Preparen list of postponed (floating and relative-positioned) boxes for the current page
      $postponed_filter->process($box, null, $this);

      $this->_resetCounter('footnote', 0);
      $this->_incrementCounter('page', 1);

      $this->output_driver->save();

      /**
       * Note that margin boxes should be rendered before 'setup_clip', as it will trim all
       * content rendered outside the 'main' page area
       */
      $this->render_margin_boxes($i+1, $media);
      $this->render_page_box($i+1, $media);

      $this->output_driver->setPageHeight($current_page_height);
      $this->output_driver->setup_clip();

      $this->_dispatcher->fire('before-page', array('pipeline' => &$this, 
                                                    'document' => &$box, 
                                                    'pageno' => $i));

      if (is_null($box->show($this->output_driver))) { 
        error_log('Pipeline::_process_item: output routine failed');
        return null; 
      };

      /**
       * Show postponed boxes - relative and floating boxes, as they should be 
       * shown over boxes on the same layer
       */
      $this->output_driver->show_postponed();

      $this->renderAbsolutePositioned($context);
      $this->output_driver->restore();
      $this->renderFixedPositioned($context);
      $this->renderFootnotes();

      global $g_config;
      if ($g_config['draw_page_border']) { 
        $this->output_driver->draw_page_border(); 
      };

      $this->_dispatcher->fire('after-page', array('pipeline' => &$this, 
                                                   'document' => &$box, 
                                                   'pageno' => $i));
    };

    $this->_dispatcher->fire('after-document', array('pipeline' => &$this, 
                                                     'document' => &$box));
  }

  function _output() {
    $temporary_output_filename = $this->output_driver->get_filename();

    for ($i=0; $i<count($this->output_filters); $i++) {
      $temporary_output_filename = $this->output_filters[$i]->process($temporary_output_filename);
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

    $this->destination->process($temporary_output_filename, $content_type);
    unlink($temporary_output_filename);
  }

  function set_destination(&$destination) {
    $this->destination =& $destination;
  }

  function set_output_driver(&$output_driver) {
    $this->output_driver =& $output_driver;
  }

  function &fetch($data_id) {
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

    if (defined('DEBUG_MODE')) {
      error_log(sprintf('Could not fetch %s', $data_id));
    };

    $null = null;
    return $null;
  }
  
  function process($data_id, &$media) {
    return $this->process_batch(array($data_id), $media);
  }

  function _setupScales(&$media) {
    global $g_config;
    global $g_px_scale;
    global $g_pt_scale;

    $g_px_scale = floor(mm2pt($media->width() - $media->margins['left'] - $media->margins['right'])) / $media->pixels;

    if ($g_config['scalepoints']) {
      $g_pt_scale = $g_px_scale * 1.33; // This is a magic number, just don't touch it, or everything will explode!
    } else {
      $g_pt_scale = 1.0;
    };
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
    $this->clear_box_id_map();

    // Save and disable magic_quotes_runtime
    $mq_runtime = get_magic_quotes_runtime();
    set_magic_quotes_runtime(0);

    $this->_prepare($media);

    $this->_dispatcher->fire('before-batch', array('pipeline' => &$this));

    $i = 0;
    $offset = 0;
    foreach ($data_id_array as $data_id) {      
      $this->_process_item($data_id, $media, $offset);

      $i++;
      $offset = $this->output_driver->offset;
    };

    $this->close();

    // Restore magic_quotes_runtime setting
    set_magic_quotes_runtime($mq_runtime);

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

  function &get_output_driver() {
    return $this->output_driver;
  }  

  function guess_url($src) {
    return guess_url($src, $this->get_base_url());
  }

  function renderFootnotes() {
    /**
     * Render every footnote defined (note-call element is visible) on a current page
     */

    $footnote_y = $this->output_driver->getFootnoteTop() - FOOTNOTE_LINE_TOP_GAP - FOOTNOTE_LINE_BOTTOM_GAP;
    $footnote_x = $this->output_driver->getPageLeft();
    $footnotes_found = false;

    foreach ($this->_footnotes as $footnote) {
      // Note that footnote area for current page have been already defined,
      // as show_foonote is called after note-call boxes were placed.
      if ($this->output_driver->contains($footnote->_note_call_box)) { 
        $footnotes_found = true;
        $footnote_y = $footnote->show_footnote($this->output_driver, 
                                               $footnote_x,
                                               $footnote_y);
        $footnote_y -= FOOTNOTE_GAP;
      };
    };

    /**
     * Draw thin line separating footnotes from page content
     */
    if ($footnotes_found) {
      $this->output_driver->setrgbcolor(0,0,0);
      $this->output_driver->moveto($this->output_driver->getPageLeft(),
                                   $this->output_driver->getFootnoteTop() - FOOTNOTE_LINE_TOP_GAP);
      $this->output_driver->lineto($this->output_driver->getPageLeft() + $this->output_driver->getPageWidth()*FOOTNOTE_LINE_PERCENT/100,
                                   $this->output_driver->getFootnoteTop() - FOOTNOTE_LINE_TOP_GAP);
      $this->output_driver->stroke();
    };
  }

  function renderAbsolutePositioned(&$context) {
    for ($j=0, $size = count($context->absolute_positioned); $j<$size; $j++) {
      $current_box =& $context->absolute_positioned[$j];
      if ($current_box->getCSSProperty(CSS_VISIBILITY) === VISIBILITY_VISIBLE) {
        $this->output_driver->save();
        $current_box->_setupClip($this->output_driver);
        if (is_null($current_box->show($this->output_driver))) {
          return null;
        };
        $this->output_driver->restore();
      };
    };
    $this->output_driver->show_postponed_in_absolute();
  }

  function renderFixedPositioned(&$context) {
    for ($j=0, $size = count($context->fixed_positioned); $j<$size; $j++) {
      $current_box =& $context->fixed_positioned[$j];
      if ($current_box->getCSSProperty(CSS_VISIBILITY) === VISIBILITY_VISIBLE) {
        $this->output_driver->save();
        $current_box->_setupClip($this->output_driver);
        if (is_null($current_box->show_fixed($this->output_driver))) { 
          return null;
        };
        $this->output_driver->restore();
      };
    };
    $this->output_driver->show_postponed_in_fixed();
  }

  function _prepare(&$media) {
    $this->_setupScales($media);
    $GLOBALS['g_media'] =& $media;
    $this->output_driver->reset($media);
  }

  function &_layout_item($data_id, &$media, $offset, &$context, &$postponed_filter) {
    $this->_reset_page_at_rules();

    $css_cache = CSSCache::get();
    $this->_defaultCSS = $css_cache->compile('resource://default.css', 
                                             file_get_contents(HTML2PS_DIR.'/default.css'));
    $this->_css = array();
    $this->pushCSS();

    $this->_cssState = array(new CSSState(CSS::get()));

    $font = $this->_cssState[0]->getProperty(CSS_FONT);
    $font->units2pt(0);
    $this->_cssState[0]->setProperty(CSS_FONT, $font);

    $data = $this->fetch($data_id);

    if (is_null($data)) { 
      $dummy = null;
      return $dummy;
    };

    // Run raw data filters
    for ($i=0; $i<count($this->data_filters); $i++) {
      $data = $this->data_filters[$i]->process($data);
    };

    // Parse the raw data
    $box =& $this->parser->process($data->get_content(), $this, $media);

    $this->_dispatcher->fire('after-parse', array('pipeline' => &$this,
                                                  'document' => &$box,
                                                  'media' => $media));

    /**
     * Run obligatory tree filters
     */

    /**
     * height-constraint processing filter;
     */
    $filter = new PreTreeFilterHeightConstraint();
    $filter->process($box, $data, $this);

    /**
     * Footnote support filter
     */
    $filter = new PreTreeFilterFootnotes();
    $filter->process($box, $data, $this);

    // Run pre-layout tree filters
    for ($i=0, $size = count($this->pre_tree_filters); $i < $size; $i++) {
      $this->pre_tree_filters[$i]->process($box, $data, $this);
    };

    $context = new FlowContext;

    /**
     * Extract absolute/fixed positioned boxes
     */
    $positioned_filter = new PostTreeFilterPositioned($context);
    $positioned_filter->process($box, null, $this);

    $postponed_filter = new PostTreeFilterPostponed($this->output_driver);
    $postponed_filter->process($box, null, $this);

    $this->output_driver->prepare();

    $status = $this->layout_engine->process($box, $media, $this->output_driver, $context);
    if (is_null($status)) { 
      error_log('Pipeline::_process_item: layout routine failed');
      $dummy = null;
      return $dummy;
    };

    // Run post-layout tree filters
    for ($i=0; $i<count($this->post_tree_filters); $i++) {
      $this->post_tree_filters[$i]->process($box);
    };

    return $box;
  }

  function &getDispatcher() {
    return $this->_dispatcher;
  }

  function get_current_page_name() {
    return $this->_current_page_name;
  }

  function set_current_page_name($name) {
    $this->_current_page_name = $name;
  }
}

?>