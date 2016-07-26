<?php

function pdfThis($html, $file_location = "", $pdfname) {
    global $config;

    // set_include_path("../../../../library/pdf/");
    require_once ('./library/pdf/config.inc.php');
    require_once ('./library/pdf/pipeline.factory.class.php');
    require_once ('./library/pdf/pipeline.class.php');
    parse_config_file('./library/pdf/html2ps.config');
    // RCR 20160708 test
    //    require_once ("./include/init.php"); // for getInvoice() and getPreference()

    if (!function_exists('convert_to_pdf')) {
        /**
         * Runs the HTML->PDF conversion with default settings
         *
         * Warning: if you have any files (like CSS stylesheets and/or images referenced by this file,
         * use absolute links (like http://my.host/image.gif).
         *
         * @param $path_to_html String path to source html file.
         * @param $path_to_pdf String path to file to save generated PDF to.
         */
        function convert_to_pdf($html_to_pdf, $pdfname, $file_location = "") {
            global $config;

            $destination = $file_location == "download" ? "DestinationDownload" : "DestinationFile";

            $pipeline = PipelineFactory::create_default_pipeline("", ""); // Attempt to auto-detect encoding

            // Override HTML source
            $pipeline->fetchers[] = new MyFetcherLocalFile($html_to_pdf);

            $baseurl = "";
            $media = Media::predefined($config->export->pdf->papersize);
            $media->set_landscape(false);

            // @formatter:off
            $margins = array('left'   => $config->export->pdf->leftmargin,
                             'right'  => $config->export->pdf->rightmargin,
                             'top'    => $config->export->pdf->topmargin,
                             'bottom' => $config->export->pdf->bottommargin);

            global $g_config;
            $g_config = array('cssmedia'                => 'screen',
                              'renderimages'            => true,
                              'renderlinks'             => true,
                              'renderfields'            => true,
                              'renderforms'             => false,
                              'mode'                    => 'html',
                              'encoding'                => '',
                              'debugbox'                => false,
                              'pdfversion'              => '1.4',
                              'process_mode'            => 'single',
                              'pixels'                  => $config->export->pdf->screensize,
                              'media'                   => $config->export->pdf->papersize,
                              'margins'                 => $margins,
                              'transparency_workaround' => 1,
                              'imagequality_workaround' => 1,
                              'draw_page_border'        => false);
            // @formatter:on

            $media->set_margins($g_config['margins']);
            $media->set_pixels($config->export->pdf->screensize);

            global $g_px_scale;
            $g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;

            global $g_pt_scale;
            $g_pt_scale = $g_px_scale * (72 / 96);
            if ($g_pt_scale) {}

            $pipeline->configure($g_config);
            $pipeline->data_filters[] = new DataFilterUTF8("");
            $pipeline->destination = new $destination($pdfname);
            $pipeline->process($baseurl, $media);
        }
    }

    convert_to_pdf($html, $pdfname, $file_location);
}
