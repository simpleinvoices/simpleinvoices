<?php

/*****************************************************************************/
/** Inclusions */

  define ( "HTML2PS_BASEDIR", "@@@@ change this value for yours needs @@@@" );
  require_once ( HTML2PS_BASEDIR . "config.inc.php" );
  require_once ( HTML2PS_DIR . "pipeline.factory.class.php" );
  require_once ( HTML2PS_DIR . "fetcher.url.class.php" );


/*****************************************************************************/
/** Definitions */

  // ** Valid post processing directives
  global $VALID_POST_PROCESSING_DIRECTIVES;
                                           // DIRECTIVE            => true: multi-value, false: single-value
  $VALID_POST_PROCESSING_DIRECTIVES = array ( // VISIBILITY SPECIFICATION (ids lists)
                                              "only_first_page"    => true,
                                              "only_last_page"     => true,
                                              "all_but_first_page" => true,
                                              "all_but_last_page"  => true,
                                              "only_even_page"     => true,
                                              "only_odd_page"      => true,
                                              // MARGIN (mm)
                                              "margin_left"        => false,
                                              "margin_right"       => false,
                                              "margin_top"         => false,
                                              "margin_bottom"      => false,
                                              // PAGE WIDTH (pixel)
                                              "page_width"         => false
                                            );


/*****************************************************************************/
/** Run-time settings */

  // ** PHP
  set_time_limit ( 10000 );

  // ** HTML2PS
  ini_set ( "user_agent", DEFAULT_USER_AGENT );
  $g_css_index = 0;
  $g_stylesheet_title = ""; // Title of styleshee to use (empty if no preferences are set)


/*****************************************************************************/
/** Parameters */

  // Input url
  $fi = trim ( urldecode ( get_var ( "fi", $_GET, 255, "" ) ) );
  // Output file name (without ".pdf")
  $fo = trim ( urldecode ( get_var ( "fo", $_GET, 255, "document" ) ) );
  // Margin value in mm (see page definition in test.html)
  $ml = (int) get_var ( "ml", $_GET, 255, -1 );
  $mr = (int) get_var ( "mr", $_GET, 255, -1 );
  $mt = (int) get_var ( "mt", $_GET, 255, -1 );
  $mb = (int) get_var ( "mb", $_GET, 255, -1 );
  // Page width
  $pw = (int) get_var ( "pw", $_GET, 255, -1 );


/*****************************************************************************/
/** Parameters validation */

  if ( $fi == "" ) { die ( "Wrong parameters." ); }


/*****************************************************************************/
/** Get post-processing information */

  // *** Init
  global $POST_PROCESSING_DIRECTIVES;
  $POST_PROCESSING_DIRECTIVES = array();
  // *** Get file content in row (array)
  $filerows = file ( $fi );
  if ( $filerows == false ) { die ( "Unable to get file content." ); }
  // *** Search for directives block
  $viewed_post_process_open  = false;
  $viewed_post_process_close = false;
  for ( $i = 0; $i < count ( $filerows ); $i++ ) {
    if ( strpos ( trim ( $filerows[$i] ), "HTML2PDF_POST_PROCESSING_DIRECTIVES -->" ) === 0 ) {
      // Directives block ended
      $viewed_post_process_close = true;
      break;
    }
    if ( $viewed_post_process_open ) { // Am i in directives lock?
      // Check if comment
      if ( strpos ( trim ( $filerows[$i] ), "//" ) === 0 ) { continue; } // Skip comment line
      // Normal line
      $tmp = explode ( ":", $filerows[$i] );
      $row_type = ( isset ( $tmp[0] ) ? trim ( $tmp[0] ) : "" );
      $row_info = ( isset ( $tmp[1] ) ? trim ( $tmp[1] ) : "" );
      // This row is a valid directive?
      if ( ! isset ( $VALID_POST_PROCESSING_DIRECTIVES[$row_type] ) ) {
        die ( "Unknown POST PROCESSING directive: |$row_type|." );
      }
      $mulval = $VALID_POST_PROCESSING_DIRECTIVES[$row_type];
      // Save directive
      $values = explode ( ",", $row_info );
      if ( $mulval ) {
        // Multi-value directive
        if ( count ( $values ) > 0 ) {
          if ( ! isset ( $POST_PROCESSING_DIRECTIVES[$row_type] ) ) {
            $POST_PROCESSING_DIRECTIVES[$row_type] = $values;
          } else {
            $POST_PROCESSING_DIRECTIVES[$row_type] = array_merge ( $POST_PROCESSING_DIRECTIVES[$row_type], $values );
          }
        }
      } else {
        // Single-value directive
        if ( ! isset ( $values[0] ) ) {
          die ( "Specify a value for |$row_type| directive." );
        }
        $POST_PROCESSING_DIRECTIVES[$row_type] = $values[0];
      }
    }
    if ( strpos ( trim ( $filerows[$i] ), "<!-- HTML2PDF_POST_PROCESSING_DIRECTIVES" ) === 0 ) {
       // Directives block started
       $viewed_post_process_open  = true;
    }
  }
  if ( $viewed_post_process_open != $viewed_post_process_close ) {
    die ( "Error reading POST PROCESSING directives." );
  }


/*****************************************************************************/
/** Use post-processing information (not all of them yet) */

  // ** Overwrite margin value with directives
  if ( $ml == -1 ) {
    $ml = ( isset ( $POST_PROCESSING_DIRECTIVES["margin_left"] ) ?
            $POST_PROCESSING_DIRECTIVES["margin_left"] :
            0 );
  }
  if ( $mr == -1 ) {
    $mr = ( isset ( $POST_PROCESSING_DIRECTIVES["margin_right"] ) ?
            $POST_PROCESSING_DIRECTIVES["margin_right"] :
            0 );
  }
  if ( $mt == -1 ) {
    $mt = ( isset ( $POST_PROCESSING_DIRECTIVES["margin_top"] ) ?
            $POST_PROCESSING_DIRECTIVES["margin_top"] :
            0 );
  }
  if ( $mb == -1 ) {
    $mb = ( isset ( $POST_PROCESSING_DIRECTIVES["margin_bottom"] ) ?
            $POST_PROCESSING_DIRECTIVES["margin_bottom"] :
            0 );
  }
  // ** Overwrite page width value with directives
  if ( $pw == -1 ) {
    $pw = ( isset ( $POST_PROCESSING_DIRECTIVES["page_width"] ) ?
            $POST_PROCESSING_DIRECTIVES["page_width"] :
            800 );
  }


/*****************************************************************************/
/** Configuration */

  $GLOBALS['g_config'] = array ( 'cssmedia'                => "screen",
                                 'media'                   => "A4",
                                 'scalepoints'             => true,
                                 'renderimages'            => true,
                                 'renderfields'            => true,
                                 'renderforms'             => false,
                                 'pslevel'                 => 3,
                                 'renderlinks'             => true,
                                 'pagewidth'               => $pw,
                                 'landscape'               => false,
                                 'method'                  => "fpdf",
                                 'margins'                 => array ( 'left'   => $ml,
                                                                      'right'  => $mr,
                                                                      'top'    => $mt,
                                                                      'bottom' => $mb,
                                                                    ),
                                 'encoding'                => "",
                                 'ps2pdf'                  => false,
                                 'compress'                => false,
                                 'output'                  => 1,
                                 'pdfversion'              => "1.2",
                                 'transparency_workaround' => false,
                                 'imagequality_workaround' => false,
                                 'draw_page_border'        => false,
                                 'debugbox'                => false,
                                 'html2xhtml'              => true,
                                 'mode'                    => 'html',
                                 'smartpagebreak'          => true
                               );


/*****************************************************************************/
/** Inizializza pipeline */

  // ** Parse configuration file
  parse_config_file ( HTML2PS_BASEDIR . "html2ps.config" );

  // ** Media
  $g_media = Media::predefined ( $GLOBALS['g_config']['media'] );
  $g_media->set_landscape ( $GLOBALS['g_config']['landscape'] );
  $g_media->set_margins ( $GLOBALS['g_config']['margins'] );
  $g_media->set_pixels ( $GLOBALS['g_config']['pagewidth'] );

  // ** Pipeline
  // *** Initialize the coversion pipeline
  $pipeline = new Pipeline();
  // *** Fetchers
  $pipeline->fetchers[] = new FetcherUrl();
  // *** Data filters
  $pipeline->data_filters[] = new DataFilterDoctype();
  $pipeline->data_filters[] = new DataFilterUTF8 ( "" );
  $pipeline->data_filters[] = new DataFilterHTML2XHTML();
  // *** Parser
  $pipeline->parser = new ParserXHTML();
  // *** Pre-tree filters
  $pipeline->pre_tree_filters = array();
  $pipeline->pre_tree_filters[] = new PreTreeFilterHTML2PSFields();
  // *** Layout engine
  $pipeline->layout_engine = new LayoutEngineDefault();
  // *** Post-tree filters
  $pipeline->post_tree_filters = array();
  // *** Output driver
  $pipeline->output_driver = new OutputDriverFPDF();
  // *** Destination
  $pipeline->destination = new DestinationDownload ( $fo );

  // *** Install event handler
  $dispatcher =& $pipeline->getDispatcher();
  $dispatcher->add_observer ( 'before-page', 'visible_hidden_by_id');


/*****************************************************************************/
/** Main */

  // ** Generate PDF file
  $status = $pipeline->process ( $fi, $g_media );
  if ( $status == null ) {
    print ( $pipeline->error_message() );
    syslog ( LOG_ERR, "PHP: Error in conversion pipeline" );
    die();
  }


/*****************************************************************************/
/** Functions */

  // ** Event handler

  function visible_hidden_by_id ( $params ) {
    global $POST_PROCESSING_DIRECTIVES;
    // ** Read page number
    $exppag = $params["pipeline"]->output_driver->get_expected_pages();
    $pageno = $params["pageno"] + 1; // Plus 1 because in "before-page" pageno isn't yet incremented
    // ** Show elements only in first page
    if ( isset ( $POST_PROCESSING_DIRECTIVES["only_first_page"] ) ) {
      $value = ( $pageno == 1 ? VISIBILITY_VISIBLE : VISIBILITY_HIDDEN );
      foreach ( $POST_PROCESSING_DIRECTIVES["only_first_page"] as $k => $id ) {
        $elem = $params["document"]->get_element_by_id ( trim ( $id ) );
        if ( $elem != NULL ) {
          $elem->setCSSProperty ( CSS_VISIBILITY, $value );
        }
      }
    }
    // ** Show elements only in last page
    if ( isset ( $POST_PROCESSING_DIRECTIVES["only_last_page"] ) ) {
      $value = ( $pageno == $exppag ? VISIBILITY_VISIBLE : VISIBILITY_HIDDEN );
      foreach ( $POST_PROCESSING_DIRECTIVES["only_last_page"] as $k => $id ) {
        $elem = $params["document"]->get_element_by_id ( trim ( $id ) );
        if ( $elem != NULL ) {
          $elem->setCSSProperty ( CSS_VISIBILITY, $value );
        }
      }
    }
    // ** Show elements in all pages but first
    if ( isset ( $POST_PROCESSING_DIRECTIVES["all_but_first_page"] ) ) {
      $value = ( $pageno != 1 ? VISIBILITY_VISIBLE : VISIBILITY_HIDDEN );
      foreach ( $POST_PROCESSING_DIRECTIVES["all_but_first_page"] as $k => $id ) {
        $elem = $params["document"]->get_element_by_id ( trim ( $id ) );
        if ( $elem != NULL ) {
          $elem->setCSSProperty ( CSS_VISIBILITY, $value );
        }
      }
    }
    // ** Show elements in all pages but last
    if ( isset ( $POST_PROCESSING_DIRECTIVES["all_but_last_page"] ) ) {
      $value = ( $pageno != $exppag ? VISIBILITY_VISIBLE : VISIBILITY_HIDDEN );
      foreach ( $POST_PROCESSING_DIRECTIVES["all_but_last_page"] as $k => $id ) {
        $elem = $params["document"]->get_element_by_id ( trim ( $id ) );
        if ( $elem != NULL ) {
          $elem->setCSSProperty ( CSS_VISIBILITY, $value );
        }
      }
    }
    // ** Show elements only in even pages
    if ( isset ( $POST_PROCESSING_DIRECTIVES["only_even_page"] ) ) {
      $value = ( $pageno % 2 == 0 ? VISIBILITY_VISIBLE : VISIBILITY_HIDDEN );
      foreach ( $POST_PROCESSING_DIRECTIVES["only_even_page"] as $k => $id ) {
        $elem = $params["document"]->get_element_by_id ( trim ( $id ) );
        if ( $elem != NULL ) {
          $elem->setCSSProperty ( CSS_VISIBILITY, $value );
        }
      }
    }
    // ** Show elements only in odd pages
    if ( isset ( $POST_PROCESSING_DIRECTIVES["only_odd_page"] ) ) {
      $value = ( $pageno % 2 != 0 ? VISIBILITY_VISIBLE : VISIBILITY_HIDDEN );
      foreach ( $POST_PROCESSING_DIRECTIVES["only_odd_page"] as $k => $id ) {
        $elem = $params["document"]->get_element_by_id ( trim ( $id ) );
        if ( $elem != NULL ) {
          $elem->setCSSProperty ( CSS_VISIBILITY, $value );
        }
      }
    }
  }

  // ** Generic functions

  function get_var ( $name, $array, $maxlength = 255, $default = null ) {
    if ( ! isset ( $array[$name] ) ) { return $default; }
    $data = $array[$name];
    if ( is_array ( $data ) ) {
      if ( get_magic_quotes_gpc() ) {
        foreach ( $data as $key => $value ) {
          $data[$key] = stripslashes ( $data[$key] );
        }
      }
    } else {
      if ( get_magic_quotes_gpc() ) {
        $data = stripslashes ( $data );
      }
      $data = substr ( $data, 0, $maxlength );
    }
    return $data;
  }


?>