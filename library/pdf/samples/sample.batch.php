<?php

error_reporting(E_ALL);
ini_set("display_errors","1");
@set_time_limit(600);

$urls = array(
              'http://247realmedia.com',
              'http://888.com',
              'http://abetterinternet.com',
              'http://adsense.google.com',
              'http://alphadg.com',
              'http://allwomencentral.com/?r=sub',
              'http://aol.com',
              'http://www.articlehub.com/add.html',
              'http://aur.archlinux.org',
              'http://bbc.co.uk',
              'http://benews.net',
              'http://casalemedia.com',
              'http://cnn.com',
              'http://www.codeguru.com/register.php', 
              'http://cra-arc.gc.ca/menu-e.html',
              'http://crux.nu',
              'http://cs.wisc.edu/~ghost/',
              'http://distrowatch.com',
              'http://dmoz.org/cgi-bin/apply.cgi?submit=Proceed&where=Business%2FEmployment%2FCareers&id=&lk=&loc=', // Connect
              'http://download.com',
              'http://ebay.com',
              'http://ewizard.com',
              'http://exactsearch.net',
              'http://exitexchange.com',
              'http://ezinearticles.com/submit',
              'http://falkag.net',
              'http://fedora.redhat.com',
              'http://freebsd.org',
              'http://freewho.com/expired/index.php',
              'http://gentoo.org',
              'http://geocities.com',
              'http://www.getafreelancer.com/users/signup.php',
              'http://gmail.com',
              'http://go.com',
              'http://google.co.in',
              'http://google.com/about.html',
              'http://google.com/froogle',
              'http://google.com/services/',
              'http://google.fi/fi',
              'http://guru.com/pro/post_profile.cfm',
              'http://hamster.sazco.net',
              'http://www-128.ibm.com/developerworks/linux/library/l-proc.html',
              'http://hotmail.com',
              'http://indianrail.gov.in',
              'http://internet-optimizer.com',
              'http://jakpsatweb.cz/css/css-vertical-center-solution.html',
              'http://jobsearch.monsterindia.com/advanced_job_search.html',
              'http://johnlewis.com',
              'http://kubuntu.org',
              'http://lyrc.com.ar/en/add/add.php',
              'http://microsoft.com',
              'http://msn.com',
              'http://myblog.de',
              'http://myway.com',
              'http://mywebsearch.com',
              'http://net-offers.net',
              'http://netscape.com',
              'http://netvenda.com',
              'http://offeroptimizer.com',
              'http://onet.pl',
              'http://opensuse.org',
              'http://osnews.com',
              'http://papajohns.com',
              'http://partypoker.com',
              'http://passport.com',
              'http://php.net',
              'http://pilger.carlton.com',
              'http://priyank.one09.net',
              'http://python.org/~guido/',
              'http://realmedia.com',
              'http://rentacoder.com',
              'http://revenue.net',
              'http://rubixlinux.org',
              'http://sage.com/local/regionNorthAmerica.aspx',
              'http://searchscout.com',
              'http://search.ebay.in/ws/search/AdvSearch?sofindtype=13',
              'http://services.princetonreview.com/register.asp?RUN=%2FstudentTools%2FstudentTool%2Easp&RCN=auth&RDN=1&ALD=http%3A%2F%2Ftestprep%2Eprincetonreview%2Ecom',
              'http://smarty.php.net',
              'http://stallman.org',
              'http://stanton-finley.net/fedora_core_5_installation_notes.html',
              'http://thefacebook.com',
              'http://tickle.com',
              'http://trafficmp.com',
              'http://tufat.com',
              'http://ubuntu.com',
              'http://user.it.uu.se/~jan/html2ps.html',
              'http://vianet.com.pl',
              'http://website.in/domain.php',
              'http://whenu.com',
              'http://whitehouse.gov',
              'http://whois.org/',
              'http://en.wikipedia.org',
              'http://en.wikipedia.org/w/index.php?title=Spangenhelm&action=edit',
              'http://wolfram.com',
              'http://www.xe.com/ucc',
              'http://yahoo.com',
              'http://yahoomail.com',
              'http://edit.yahoo.com/config/eval_register',
              'http://zango.com'
              );

require_once('../pipeline.class.php');

parse_config_file('../html2ps.config');

$g_config = array(
                  'cssmedia'     => 'screen',
                  'renderimages' => true,
                  'renderforms'  => true,
                  'renderlinks'  => true,
                  'mode'         => 'html',
                  'debugbox'     => false,
                  'draw_page_border' => false
                  );

$media = Media::predefined('A4');
$media->set_landscape(false);
$media->set_margins(array('left'   => 10,
                          'right'  => 10,
                          'top'    => 10,
                          'bottom' => 10));
$media->set_pixels(1024);

$g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;
$g_pt_scale = $g_px_scale * 1.43; 

$pipeline = new Pipeline;
$pipeline->configure($g_config);
$pipeline->fetchers[]     = new FetcherURL;
$pipeline->data_filters[] = new DataFilterDoctype();
$pipeline->data_filters[] = new DataFilterUTF8("");
$pipeline->data_filters[] = new DataFilterHTML2XHTML;
$pipeline->parser         = new ParserXHTML;
$pipeline->pre_tree_filters = array();
$pipeline->layout_engine  = new LayoutEngineDefault;
$pipeline->post_tree_filters = array();
$pipeline->output_driver  = new OutputDriverFPDF();

$time = time();
foreach ($urls as $url) {
  $pipeline->destination    = new DestinationFile($url);
  $pipeline->process($url, $media); 

  $message = sprintf("<br/>Processing of '%s' completed in %u seconds", $url, time() - $time);
  error_log($message);
  print($message."<br/>");
  flush();

  $time = time();
};


?>