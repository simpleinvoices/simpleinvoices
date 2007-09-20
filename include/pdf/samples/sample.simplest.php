<?php

require_once('../pipeline.class.php');
require_once('../pipeline.factory.class.php');

parse_config_file('../html2ps.config');

/**
 * Runs the HTML->PDF conversion with default settings
 *
 * Warning: if you have any files (like CSS stylesheets and/or images referenced by this file,
 * use absolute links (like http://my.host/image.gif).
 *
 * @param $path_to_html String path to source html file.
 * @param $path_to_pdf  String path to file to save generated PDF to.
 */
function convert_to_pdf($path_to_html, $path_to_pdf) {
  /**
   * Handles the saving generated PDF to user-defined output file on server
   */
  class MyDestinationFile extends Destination {
    /**
     * @var String result file name / path
     * @access private
     */
    var $_dest_filename;

    function MyDestinationFile($dest_filename) {
      $this->_dest_filename = $dest_filename;
    }

    function process($tmp_filename, $content_type) {
      copy($tmp_filename, $this->_dest_filename);
    }
  }

  class MyFetcherLocalFile extends Fetcher {
    var $_content;

    function MyFetcherLocalFile($file) {
      $this->_content = file_get_contents($file);
    }

    function get_data($dummy1) {
      return new FetchedDataURL($this->_content, array(), "");
    }

    function get_base_url() {
      return "";
    }
  }

  $pipeline = PipelineFactory::create_default_pipeline("", // Attempt to auto-detect encoding
                                                       "");

  // Override HTML source 
  $pipeline->fetchers[] = new MyFetcherLocalFile($path_to_html);

  // Override destination to local file
  $pipeline->destination = new MyDestinationFile($path_to_pdf);

  $baseurl = "";
  $media = Media::predefined("A4");
  $media->set_landscape(false);
  $media->set_margins(array('left'   => 0,
                            'right'  => 0,
                            'top'    => 0,
                            'bottom' => 0));
  $media->set_pixels(1024); 

  global $g_config;
  $g_config = array(
                    'cssmedia'     => 'screen',
                    'renderimages' => true,
                    'renderlinks'  => true,
                    'renderfields' => true,
                    'renderforms'  => false,
                    'mode'         => 'html',
                    'encoding'     => '',
                    'debugbox'     => false,
                    'pdfversion'    => '1.4',
                    'draw_page_border' => false
                    );

  global $g_px_scale;
  $g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels; 
  global $g_pt_scale;
  $g_pt_scale = $g_px_scale * 1.43; 

  $pipeline->process($baseurl, $media);
}

convert_to_pdf("./testing/forms.html", "./testing/forms.pdf");

?>