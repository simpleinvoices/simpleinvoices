<?php
//============================================================================
//============================================================================
// Script:    	PHP Class "weather"
// Version:     2.0 / 12.12.2006
//
// Rewritten: Dec 2006 by Matt Brown
//   fixed all PHP warnings
//   added cachedir parameter
//   changed structure of results
//   more adaptive to changes in Yahoo RSS feed
//============================================================================
// From:	http://www.voegeli.li
// Autor:	Marco Voegeli, Switzerland >> www.voegeli.li >> fly forward! >>
// Date:	28-Oct-2005 
// License/
// Usage:	Open Source / for free	
//============================================================================
// DEPENDENCIES:
// -  It requires the class "xmlParser" (Be lucky: Also in the Archive file!)
//============================================================================
// DESCRIPTION:
// This Class gets Weather RSS from WEATHER.YAHOO.COM and parses it into
// a weather object with usable attributes. Use it for:
//
// - Actual Situation (temperature/sunrise/sunset/Image...)
// - Forecast Day 1 (temp low, high/text/date/day/image...)
// - Forecast Day 2 (temp low, high/text/date/day/image...)
//
// PUBLIC METHODS
// - parse() :		Gets the XML File parses it and fills attributes
// - parsecahed() : 	Much quicker!!! Writes a cached version to a local
//				file with expiry date! expiry date is calculated
//				with the given input parameter
//		
//============================================================================
// SAMPLE:
// - See the file "weather.test.php" in this archive for Santiago de Chile
//
// WEB GUI URL: http://weather.yahoo.com/forecast/CIXX0020_c.html?force_units=1
// RSS URL:     http://xml.weather.yahoo.com/forecastrss?u=C&p=CIXX0020
//
// The class needs one Attribute in the Constructor Method:
//
// $weather_chile = new weather("CIXX0020", 60);
//
// "CIXX0020" is the Yahoo code for Santiago de Chile. See WEB GUI URL above!
// 
// "60" means 60 seconds until the cache expires. If not needed set = 0.
//
// GO TO WEATHER.YAHOO.COM and search for your desired weather location. If
// found, click on the location link (must see the forecast). Now take
// the code from the URL in your browsers address field.
//
//============================================================================
// Changes:
// - 19.11.2005 MAV : XML Feed Structure from Yahoo changed. Adapted script.
//============================================================================



class weather
{


// ------------------- 
// ATTRIBUTES DECLARATION
// -------------------

// HANDLING ATTRIBUTES
var $locationcode; // Yahoo Code for Location
var $allurl;       // generated url with location
var $parser;       // Instance of Class XML Parser
var $unit;         // F or C / Fahrenheit or Celsius

// CACHING ATTRIBUTES
var $cache_expires;
var $cache_lifetime;
var $source;       // cache or live

var $forecast=array();


// ------------------- 
// CONSTRUCTOR METHOD
// -------------------
function weather($location, $lifetime, $unit, $cachedir)
{

// Set Lifetime / Locationcode
$this->cache_lifetime = $lifetime;
$this->locationcode   = $location;
$this->unit           = $unit;
$this->cachedir       = $cachedir;
$this->filename       = $cachedir . $location;

}

// ------------------- 
// FUNCTION PARSE
// -------------------
function parse()
{
$this->allurl = "http://xml.weather.yahoo.com/forecastrss";
$this->allurl .= "?u=" . $this->unit;
$this->allurl .= "&p=" . $this->locationcode;

// Create Instance of XML Parser Class
// and parse the XML File
$this->parser = new xmlParser();
$this->parser->parse($this->allurl);
$content=&$this->parser->output[0]['child'][0]['child'];
foreach ($content as $item) {
  //print "<hr><pre>";
  //print_r($item);
  //print "</pre></p>";
  switch ($item['name']) {
    case 'TITLE':
    case 'LINK':
    case 'DESCRIPTION':
    case 'LANGUAGE':
    case 'LASTBUILDDATE':
      $this->forecast[$item['name']]=$item['content'];
      break;
    case 'YWEATHER:LOCATION':
    case 'YWEATHER:UNITS':
    case 'YWEATHER:ASTRONOMY':
      foreach ($item['attrs'] as $attr=>$value)
        $this->forecast[$attr]=$value;
      break;
    case 'IMAGE':
      break;
    case 'ITEM':
      foreach ($item['child'] as $detail) {
        switch ($detail['name']) {
          case 'GEO:LAT':
          case 'GEO:LONG':
          case 'PUBDATE':
            $this->forecast[$detail['name']]=$detail['content'];
            break;
          case 'YWEATHER:CONDITION':
            $this->forecast['CURRENT']=$detail['attrs'];
            break;
          case 'YWEATHER:FORECAST':
            array_push($this->forecast,$detail['attrs']);
            break;
        }
      }
      break;
  }
}
$this->source = 'live';

// FOR DEBUGGING PURPOSES
//print "<hr><pre>";
//print_r($this->forecast);
//print "</pre></p>";
}

// ------------------- 
// WRITE OBJECT TO CACHE
// -------------------
function writecache() {
  unset($this->parser);
  $this->cache_expires = time() + $this->cache_lifetime;
  $fp = fopen($this->filename, "w");
  fwrite($fp, serialize($this));
  fclose($fp);
}

// ------------------- 
// READ OBJECT FROM CACHE
// -------------------
function readcache()
{
$content=@file_get_contents($this->filename);
if ($content==false) return false;
$intweather = unserialize($content);
if ($intweather->cache_expires < time()) return false;

$this->source = 'cache';
$this->forecast = $intweather->forecast;
return true;
}


// ------------------- 
// FUNCTION PARSECACHED
// -------------------
function parsecached() {
  if ($this->readcache()) return;
  $this->parse();
  $this->writecache();
}

} // class : end

?>