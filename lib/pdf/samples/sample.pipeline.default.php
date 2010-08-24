<?php

require_once(dirname(__FILE__).'/../config.inc.php');
require_once(HTML2PS_DIR.'pipeline.factory.class.php');
parse_config_file(HTML2PS_DIR.'html2ps.config');

global $g_config;
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

global $g_px_scale;
$g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;

global $g_pt_scale;
$g_pt_scale = $g_px_scale * 1.43; 

$pipeline = PipelineFactory::create_default_pipeline("","");
$pipeline->configure($g_config);
$pipeline->process('http://localhost:81/testing/ww.html', $media); 

?>