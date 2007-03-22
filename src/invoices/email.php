<?php
include("./include/include_main.php");

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


include("./templates/default/invoices/email.tpl");

//show the email stage info
//stage 1 = enter to, from, cc and message
if ($_GET['stage'] == 1 ) {

	#get the invoice id
	$invoice_id = $_GET['submit'];

	#Info from DB print
	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );


	#master invoice id select
	$print_master_invoice_id = "SELECT * FROM {$tb_prefix}invoices WHERE inv_id =$invoice_id";
	$result_print_master_invoice_id  = mysql_query($print_master_invoice_id , $conn) or die(mysql_error());

	$invoice = mysql_fetch_array($result_print_master_invoice_id);

	$customer = getCustomer($invoice['inv_customer_id']);
	$biller = getBiller($invoice['inv_biller_id']);



	echo $block_stage1;
}
//stage 2 = create pdf
else if ($_GET['stage'] == 2 ) {

        $url_pdf = "$_SERVER[HTTP_HOST]$install_path/index.php?module=invoices&view=templates/template&submit=$inv_idField&action=view&location=pdf&invoice_style=$inv_ty_descriptionField";
        $url_pdf_encoded = urlencode($url_pdf);
        $url_for_pdf = "pdf/html2ps.php?process_mode=single&renderfields=1&renderlinks=1&renderimages=1&scalepoints=1&pixels=$pdf_screen_size&media=$pdf_paper_size&leftmargin=$pdf_left_margin&rightmargin=$pdf_right_margin&topmargin=$pdf_top_margin&bottommargin=$pdf_bottom_margin&transparency_workaround=1&imagequality_workaround=1&output=2&location=pdf&pdfname=$pref_inv_wordingField$inv_idField&URL=$url_pdf_encoded";


require_once('./pdf/pipeline.class.php');
require_once('./pdf/pipeline.factory.class.php');

parse_config_file('./pdf/html2ps.config');

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
  $pipeline->fetchers = array(new MyFetcherLocalFile($path_to_html));

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

//convert_to_pdf("$url_for_pdf, "./testing/forms.pdf");
convert_to_pdf("http://localhost/simpleinvoices/phpinfo.php", "./testing/forms.pdf");






	echo $block_stage2;
}
//stage 3 = assemble email and send
else if ($_GET['stage'] == 3 ) {
	echo $block_stage3;
}
else {
	echo "How did you get here :)";
}

?>
