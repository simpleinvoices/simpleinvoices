<?php

require_once('pipeline.class.php');

class PipelineFactory {
  function create_default_pipeline($encoding, $filename) {
    $pipeline = new Pipeline(); 

    $pipeline->fetchers[] = new FetcherURL();

    $pipeline->data_filters[] = new DataFilterUTF8($encoding);
    $pipeline->data_filters[] = new DataFilterHTML2XHTML();

    $pipeline->parser = new ParserXHTML();

    $pipeline->pre_tree_filters = array();

    $pipeline->layout_engine = new LayoutEngineDefault();

    $pipeline->post_tree_filters = array();

    $pipeline->output_driver = new OutputDriverFPDF();
    
    $pipeline->output_filters = array();

    $pipeline->destination = new DestinationDownload($filename, ContentType::pdf());

    return $pipeline;
  }
}

?>