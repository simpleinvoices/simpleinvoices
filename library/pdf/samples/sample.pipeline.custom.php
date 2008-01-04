<?php

require_once('../pipeline.class.php');
parse_config_file('../html2ps.config');

$g_config = array(
                  'cssmedia'     => 'screen',
                  'renderimages' => true,
                  'renderforms'  => false,
                  'renderlinks'  => true,
                  'mode'         => 'html',
                  'debugbox'     => false,
                  'draw_page_border' => false
                  );

$media = Media::predefined('A4');
$media->set_landscape(false);
$media->set_margins(array('left'   => 0,
                          'right'  => 0,
                          'top'    => 0,
                          'bottom' => 0));
$media->set_pixels(1024);

$g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;
$g_pt_scale = $g_px_scale * 1.43; 

$pipeline = new Pipeline;
$pipeline->fetchers[]     = new FetcherURL;
$pipeline->data_filters[] = new DataFilterHTML2XHTML;
$pipeline->parser         = new ParserXHTML;
$pipeline->layout_engine  = new LayoutEngineDefault;
$pipeline->output_driver  = new OutputDriverFPDF($media);
$pipeline->destination    = new DestinationFile(null);

$pipeline->process('http://localhost/simpleinvoices/index.php', $media); 

?>
