<?php
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: ".gmdate("D, d M Y H:i:s",time()+(-1*60))." GMT");
header("Content-type: text/xml");
echo "<"."?xml version='1.0' encoding='iso-8859-1'?".">\n";

include("weather/class.xml.parser.php");
include("weather/class.weather.php");

$id=isset($_GET["id"]) ? $_GET["id"] : "";
$country=isset($_GET["c"]) ? $_GET["c"] : "";
$units="C";

$yahooLocCodes=array("USCA0987","USNY0996","USTX0617","MXDF0132","MXGR0150","CAXX0518","CIXX0020","BRXX0201","ARBA0009",
  "AUXX0025","BEXX0005","DAXX0009","FRXX0076","GMXX0007","ITXX0067","NLXX0002","NOXX0029","SPXX0050","SWXX0031","SZXX0033","UKXX0085","RSXX0063",
  "CHXX0008","CHXX0116","INXX0096","INXX0012","ISXX0026","IDXX0022","JAXX0085","SNXX0006","KSXX0037","ASXX0112");
//$yahooLocCodes=array("CIXX0020");
echo "\n<ajax-response><response type='object' id='".$id."_updater'>";

print "\n<rows update_ui='true'>";
$timeout=3*60*60;  // 3 hours
if (isset($_ENV["TEMP"]))
  $cachedir=$_ENV["TEMP"];
else if (isset($_ENV["TMP"]))
  $cachedir=$_ENV["TMP"];
else if (isset($_ENV["TMPDIR"]))
  $cachedir=$_ENV["TMPDIR"];
else
  $cachedir="/tmp";
$cachedir=str_replace('\\\\','/',$cachedir);
if (substr($cachedir,-1)!='/') $cachedir.='/';

foreach ($yahooLocCodes as $locCode) {
  $weather = new weather($locCode, $timeout, $units, $cachedir);
  $weather->parsecached();
  print "<tr>";
  $attr= (substr($locCode,0,2)==$country) ? "style='background-color:yellow;'" : "";
  print XmlCell($weather->forecast['CITY'],$attr);
  print XmlCell($weather->forecast['PUBDATE']);
  print XmlCell($weather->forecast['SUNRISE']);
  print XmlCell($weather->forecast['SUNSET']);
  @print XmlCell($weather->forecast['CURRENT']['TEXT']);
  @print tempCell($weather->forecast['CURRENT']['TEMP']);
  print tempCell($weather->forecast[0]['LOW']);
  print tempCell($weather->forecast[0]['HIGH']);
  print XmlCell($weather->forecast[0]['TEXT']);
  print XmlCell($weather->source);
  print "</tr>";
}

print "\n"."</rows>";
print "\n"."<rowcount>".count($yahooLocCodes)."</rowcount>";
//print "\n"."<cachedir>".$cachedir."</cachedir>";  // for debugging
echo "\n</response></ajax-response>";

function tempCell($temp) {
  if ($temp=='' || intval($temp) > 0)
    return XmlCell($temp);
  else
    return XmlCell($temp,"style='background-color:blue;color:white;'");
}

function XmlCell($value, $attr = "") {
  if (!isset($value)) {
    $result="";
  }
  else {
    $result=htmlspecialchars($value);
  }
  return "<td $attr>$result</td>";
}

?>