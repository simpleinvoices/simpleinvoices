<html>
<head>
</head>
<body>
<?php
//============================================================================
//============================================================================
// Script:    	PHP Script "Yahoo Weather Demo"
//============================================================================
// From:	www.voegeli.li
// Autor:	marco voegeli, switzerland - >> www.voegeli.li >> fly forward!
// Date:	28-Oct-2005 
// License/
// Usage:	Open Source / for free	
//============================================================================
// DESCRIPTION:
// This Script is the example of the class yahoo weather! It shows all
// attributes of the class ad shows how to use it!
//============================================================================
// Modified: Dec 2006 by Matt Brown
//============================================================================

// ------------------- 
// INCLUDES
// -------------------
include("class.xml.parser.php");
include("class.weather.php");

// ------------------- 
// LOGIC
// -------------------
// Create the new weather object!
// CIXX0020 = Location Code from weather.yahoo.com
// 3600     = seconds of cache lifetime (expires after that)
// C        = Units in Celsius! (Option: F = Fahrenheit)

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

$weather_chile = new weather("CIXX0020", 3600, "C", $cachedir);

// Parse the weather object via cached
// This checks if there's an valid cache object allready. if yes
// it takes the local object data, what's much FASTER!!! if it
// is expired, it refreshes automatically from rss online!
$weather_chile->parsecached(); // => RECOMMENDED!

// allway refreshes from rss online. NOT SO FAST. 
//$weather_chile->parse(); // => NOT recommended!


// ------------------- 
// OUTPUT
// -------------------

// VARIOUS
print "<h1>Various</h1>";
print "title: ".$weather_chile->forecast['TITLE']."<br>";     // Yahoo! Weather - Santiago, CI
print "city: ".$weather_chile->forecast['CITY']."<br>";       // Santiago
print "sunrise: ".$weather_chile->forecast['SUNRISE']."<br>"; // 6:49 am
print "sunset: ".$weather_chile->forecast['SUNSET']."<br>";   // 08:05 pm
print "yahoolink: ".$weather_chile->forecast['LINK']."<br>";  // http://us.rd.yahoo.com/dailynews/rss/weather/Santiago__CI/*http://xml.weather.yahoo.com/forecast/CIXX0020_c.html
print "<hr>";

// ACTUAL SITUATION
print "<h1>Actual Situation</h1>";
//print_r($weather_chile->forecast['CURRENT']);
print "acttext: ".$weather_chile->forecast['CURRENT']['TEXT']."<br>";       // Partly Cloudy
print "acttemp: ".$weather_chile->forecast['CURRENT']['TEMP']."<br>";       // 16
print "acttime: ".$weather_chile->forecast['CURRENT']['DATE']."<br>";       // Wed, 26 Oct 2005 2:00 pm CLDT
//print "imagurl: ".$weather_chile->forecast['CURRENT']['IMAGEURL']."<br>"; // http://us.i1.yimg.com/us.yimg.com/i/us/nws/th/main_142b.gif
print "actcode: ".$weather_chile->forecast['CURRENT']['CODE']."<br>";
print "image: <img src=http://us.i1.yimg.com/us.yimg.com/i/us/we/52/".$weather_chile->forecast['CURRENT']['CODE'].".gif>";

print "<hr>";

// Forecast


for ($day=0; isset($weather_chile->forecast[$day]); $day++) {
  print "<h1>Forecast Day $day</h1>";
  //print_r($weather_chile->forecast[$day]);
  print "day: ".$weather_chile->forecast[$day]['DAY']."<br>";      // Wed
  print "date: ".$weather_chile->forecast[$day]['DATE']."<br>";    // 26 Oct 2005
  print "low �C: ".$weather_chile->forecast[$day]['LOW']."<br>";   // 8
  print "high �C: ".$weather_chile->forecast[$day]['HIGH']."<br>"; // 19
  print "text: ".$weather_chile->forecast[$day]['TEXT']."<br>";    // Partly Cloudy
  print "imgcode: ".$weather_chile->forecast[$day]['CODE']."<br>"; // 29=Image for partly cloudy
  print "image: <img src=http://us.i1.yimg.com/us.yimg.com/i/us/we/52/".$weather_chile->forecast[$day]['CODE'].".gif>";
  print "<hr>";
}

?>
</body>
</html>
