<?php

class FeatureToc {
  var $_anchor_locator;
  var $_document_updater;

  function FeatureToc() {
    $this->set_anchor_locator(new FeatureTocAnchorLocatorHeaders());
    $this->set_document_updater(new FeatureTocDocumentUpdaterPrependPage());
  }

  function handle_after_parse($params) {
    $pipeline =& $params['pipeline'];
    $document =& $params['document'];
    $media =& $params['media'];

    $toc =& $this->find_toc_anchors($pipeline, $media, $document);
    $this->update_document($toc, $pipeline, $media, $document);
  }

  function handle_before_document($params) {
    $pipeline =& $params['pipeline'];
    $document =& $params['document'];
    $media =& $params['media'];
    $page_heights =& $params['page-heights'];

    $toc =& $this->find_toc_anchors($pipeline, $media, $document);
    $this->update_page_numbers($toc, $pipeline, $document, $page_heights, $media);
  }

  function &find_toc_anchors(&$pipeline, &$media, &$document) {
    $locator =& $this->get_anchor_locator();
    $toc =& $locator->run($pipeline, $media, $document);
    return $toc;
  }

  function &get_anchor_locator() {
    return $this->_anchor_locator;
  }

  function &get_document_updater() {
    return $this->_document_updater;
  }

  function guess_page(&$element, $page_heights, &$media) {
    $page_index = 0;
    $bottom = mm2pt($media->height() - $media->margins['top']);
    do {
      $bottom -= $page_heights[$page_index];
      $page_index ++;
    } while ($element->get_top() < $bottom);

    return $page_index;
  }

  function install(&$pipeline, $params) {
    $dispatcher =& $pipeline->get_dispatcher();
    $dispatcher->add_observer('after-parse', array(&$this, 'handle_after_parse'));
    $dispatcher->add_observer('before-document', array(&$this, 'handle_before_document'));

    if (isset($params['location'])) {
      switch ($params['location']) {
      case 'placeholder':
        $this->set_document_updater(new FeatureTocDocumentUpdaterPlaceholder());
        break;
      case 'before':
        $this->set_document_updater(new FeatureTocDocumentUpdaterPrependPage());
        break;
      case 'after':
      default:
        $this->set_document_updater(new FeatureTocDocumentUpdaterAppendPage());
        break;
      };
    };
  }

  function set_anchor_locator(&$locator) {
    $this->_anchor_locator =& $locator;
  }

  function set_document_updater(&$updater) {
    $this->_document_updater =& $updater;
  }

  function make_toc_name_element_id($index) {
    return sprintf('html2ps-toc-name-%d', $index);
  }

  function make_toc_page_element_id($index) {
    return sprintf('html2ps-toc-page-%d', $index);
  }

  function update_document(&$toc, &$pipeline, &$media, &$document) {
    $code = '';
    $index = 1;
    foreach ($toc as $toc_element) {
      $code .= sprintf('
<div id="html2ps-toc-%s" class="html2ps-toc-wrapper html2ps-toc-%d-wrapper">
<div id="%s" class="html2ps-toc-name html2ps-toc-%d-name"><a href="#%s">%s</a></div>
<div id="%s" class="html2ps-toc-page html2ps-toc-%d-page">0000</div>
</div>%s', 
                       $index,
                       $toc_element['level'],
                       $this->make_toc_name_element_id($index),
                       $toc_element['level'],
                       $toc_element['anchor'],
                       $toc_element['name'],                       
                       $this->make_toc_page_element_id($index),
                       $toc_element['level'],
                       "\n");
      $index++;
    };

    $toc_box_document =& $pipeline->parser->process('<body><div>'.$code.'</div></body>', $pipeline, $media);
    $context =& new FlowContext();
    $pipeline->layout_engine->process($toc_box_document, $media, $pipeline->get_output_driver(), $context);
    $toc_box =& $toc_box_document->content[0];

    $document_updater =& $this->get_document_updater();
    $document_updater->run($toc_box, $media, $document);
  }

  function update_page_numbers(&$toc, &$pipeline, &$document, &$page_heights, &$media) {
    for ($i = 0, $size = count($toc); $i < $size; $i++) {
      $toc_element =& $document->get_element_by_id($this->make_toc_page_element_id($i+1));
      $element =& $toc[$i]['element'];

      $toc_element->content[0]->content[0]->words[0] = $this->guess_page($element, $page_heights, $media);
    };
  }
}

class FeatureTocAnchorLocatorHeaders {
  var $_locations;
  var $_last_generated_anchor_id;

  function FeatureTocAnchorLocatorHeaders() {
    $this->set_locations(array());
    $this->_last_generated_anchor_id = 0;
  }

  function generate_toc_anchor_id() {
    $this->_last_generated_anchor_id++;
    $id = $this->_last_generated_anchor_id;
    return sprintf('html2ps-toc-element-%d', $id);
  }

  function get_locations() {
    return $this->_locations;
  }

  function process_node($params) {
    $node =& $params['node'];

    if (preg_match('/^h(\d)$/i', $node->get_tagname(), $matches)) {
      if (!$node->get_id()) {
        $id = $this->generate_toc_anchor_id();
        $node->set_id($id);
      };
      
      $this->_locations[] = array('name' => $node->get_content(),
                                  'level' => (int)$matches[1],
                                  'anchor' => $node->get_id(),
                                  'element' => &$node);
    };
  }

  function &run(&$pipeline, &$media, &$document) {
    $this->set_locations(array());
    $walker =& new TreeWalkerDepthFirst(array(&$this, 'process_node'));
    $walker->run($document);
    $locations = $this->get_locations();

    foreach ($locations as $location) {
      $location['element']->setCSSProperty(CSS_HTML2PS_LINK_DESTINATION, $location['element']->get_id());

      // $id = $location['element']->get_id();
      // $pipeline->output_driver->anchors[$id] =& $location['element']->make_anchor($media, $id);
    };

    return $locations;
  }

  function set_locations($locations) {
    $this->_locations = $locations;
  }
}

class FeatureTocDocumentUpdaterAppendPage {
  function FeatureTocDocumentUpdaterAppendPage() {
  }

  function run(&$toc_box, &$media, &$document) {
    $toc_box->setCSSProperty(CSS_PAGE_BREAK_BEFORE, PAGE_BREAK_ALWAYS);
    $document->append_child($toc_box);
  }
}

class FeatureTocDocumentUpdaterPrependPage {
  function FeatureTocDocumentUpdaterPrependPage() {
  }

  function run(&$toc_box, &$media, &$document) {
    $toc_box->setCSSProperty(CSS_PAGE_BREAK_AFTER, PAGE_BREAK_ALWAYS);
    $document->insert_before($toc_box, $document->content[0]);
  }
}

class FeatureTocDocumentUpdaterPlaceholder {
  function FeatureTocDocumentUpdaterPlaceholder() {
  }

  function run(&$toc_box, &$media, &$document) {
    $placeholder =& $document->get_element_by_id('html2ps-toc');
    $placeholder->append_child($toc_box);
  }
}

?>