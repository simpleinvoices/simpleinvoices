<?php

// BigDump ver. 0.28b from 2007-06-08
// Staggered import of an large MySQL Dump (like phpMyAdmin 2.x Dump)
// Even through the webservers with hard runtime limit and those in safe mode
// Works fine with Internet Explorer 7.0 and Firefox 2.x

// Author:       Alexey Ozerov (alexey at ozerov dot de) 
//               AJAX & CSV functionalities: Krzysiek Herod (kr81uni at wp dot pl) 
// Copyright:    GPL (C) 2003-2007
// More Infos:   http://www.ozerov.de/bigdump.php

// This program is free software; you can redistribute it and/or modify it under the
// terms of the GNU General Public License as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.

// THIS SCRIPT IS PROVIDED AS IS, WITHOUT ANY WARRANTY OR GUARANTEE OF ANY KIND

// USAGE

// 1. Adjust the database configuration in this file
// 2. Drop the old tables on the target database if your dump doesn't contain "DROP TABLE"
// 3. Create the working directory (e.g. dump) on your web-server
// 4. Upload bigdump.php and your dump files (.sql, .gz) via FTP to the working directory
// 5. Run the bigdump.php from your browser via URL like http://www.yourdomain.com/dump/bigdump.php
// 6. BigDump can start the next import session automatically if you enable the JavaScript
// 7. Wait for the script to finish, do not close the browser window
// 8. IMPORTANT: Remove bigdump.php and your dump files from the web-server

// If Timeout errors still occure you may need to adjust the $linepersession setting in this file

// LAST CHANGES

// *** Improved error message for file open errors
// *** Handle CSV files (you have to specify $csv_insert_table)
// *** Restart script in the background using AJAX

// Database configuration

$db_server   = 'localhost';
$db_name     = 'test2';
$db_username = 'root';
$db_password = 'test';

// Other Settings


$dir = dirname($_SERVER['SCRIPT_FILENAME']);

$csv_insert_table = '';     // Destination table for CSV files
$ajax             = true;   // AJAX mode: import will be done without refreshing the website
$filename         = $dir."/SimpleInvoices.sql";     // Specify the dump filename to suppress the file selection dialog
$linespersession  = 3000;   // Lines to be executed per one import session
$delaypersession  = 0;      // You can specify a sleep time in milliseconds after each session
                            // Works only if JavaScript is activated. Use to reduce server overrun
//exit($filename);
// Allowed comment delimiters: lines starting with these strings will be dropped by BigDump

$comment[]='#';           // Standard comment lines are dropped by default
$comment[]='-- ';
// $comment[]='---';      // Uncomment this line if using proprietary dump created by outdated mysqldump
// $comment[]='/*!';         // Or add your own string to leave out other proprietary things


// Connection character set should be the same as the dump file character set (utf8, latin1, cp1251, koi8r etc.)
// See http://dev.mysql.com/doc/refman/5.0/en/charset-charsets.html for the full list

$db_connection_charset = '';


// *******************************************************************************************
// If not familiar with PHP please don't change anything below this line
// *******************************************************************************************

if ($ajax)
  ob_start();

define ('VERSION','0.28b');
define ('DATA_CHUNK_LENGTH',16384);  // How many chars are read per time
define ('MAX_QUERY_LINES',300);      // How many lines may be considered to be one query (except text lines)
define ('TESTMODE',false);           // Set to true to process the file without actually accessing the database

header("Expires: Mon, 1 Dec 2003 01:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

@ini_set('auto_detect_line_endings', true);
@set_time_limit(0);

// Clean and strip anything we don't want from user's input [0.27b]

foreach ($_REQUEST as $key => $val) 
{
  $val = preg_replace("/[^_A-Za-z0-9-\.&=]/i",'', $val);
  $_REQUEST[$key] = $val;
}

?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>BigDump ver. <?php echo (VERSION); ?></title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
<meta http-equiv="content-language" content="EN"/>

<meta http-equiv="Cache-Control" content="no-cache/"/>
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Expires" content="-1"/>

<style type="text/css">
<!--

body
{ background-color:#FFFFF0;
}

h1 
{ font-size:20px;
  line-height:24px;
  font-family:Arial,Helvetica,sans-serif;
  margin-top:5px;
  margin-bottom:5px;
}

p,td,th
{ font-size:14px;
  line-height:18px;
  font-family:Arial,Helvetica,sans-serif;
  margin-top:5px;
  margin-bottom:5px;
  text-align:justify;
  vertical-align:top;
}

p.centr
{ 
  text-align:center;
}

p.smlcentr
{ font-size:10px;
  line-height:14px;
  text-align:center;
}

p.error
{ color:#FF0000;
  font-weight:bold;
}

p.success
{ color:#00DD00;
  font-weight:bold;
}

p.successcentr
{ color:#00DD00;
  background-color:#DDDDFF;
  font-weight:bold;
  text-align:center;
}

td
{ background-color:#F8F8F8;
  text-align:left;
}

td.transparent
{ background-color:#FFFFF0;
}

th
{ font-weight:bold;
  color:#FFFFFF;
  background-color:#AAAAEE;
  text-align:left;
}

td.right
{ text-align:right;
}

form
{ margin-top:5px;
  margin-bottom:5px;
}

div.skin1
{
  border-color:#3333EE;
  border-width:5px;
  border-style:solid;
  background-color:#AAAAEE;
  text-align:center;
  vertical-align:middle;
  padding:3px;
  margin:1px;
}

td.bg3
{ background-color:#EEEE99;
  text-align:left;
  vertical-align:top;
  width:20%;
}

th.bg4
{ background-color:#EEAA55;
  text-align:left;
  vertical-align:top;
  width:20%;
}

td.bgpctbar
{ background-color:#EEEEAA;
  text-align:left;
  vertical-align:middle;
  width:80%;
}

-->
</style>

</head>

<body>

<center>

<table width="780" cellspacing="0" cellpadding="0">
<tr><td class="transparent">

<!-- <h1>BigDump: Staggered MySQL Dump Importer ver. <?php echo (VERSION); ?></h1> -->

<?php

function skin_open() {
echo ('<div class="skin1">');
}

function skin_close() {
echo ('</div>');
}

skin_open();
echo ('<h1>BigDump: Staggered MySQL Dump Importer v'.VERSION.'</h1>');
skin_close();

$error = false;
$file  = false;

// Check PHP version

if (!$error && !function_exists('version_compare'))
{ echo ("<p class=\"error\">PHP version 4.1.0 is required for BigDump to proceed. You have PHP ".phpversion()." installed. Sorry!</p>\n");
  $error=true;
}

// Calculate PHP max upload size (handle settings like 10M or 100K)

if (!$error)
{ $upload_max_filesize=ini_get("upload_max_filesize");
  if (eregi("([0-9]+)K",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024;
  if (eregi("([0-9]+)M",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024*1024;
  if (eregi("([0-9]+)G",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024*1024*1024;
}

// Get the current directory

if (isset($_SERVER["CGIA"]))
  $upload_dir=dirname($_SERVER["CGIA"]);
else if (isset($_SERVER["ORIG_SCRIPT_FILENAME"]))
  $upload_dir=dirname($_SERVER["ORIG_SCRIPT_FILENAME"]);
else if (isset($_SERVER["PATH_TRANSLATED"]))
  $upload_dir=dirname($_SERVER["PATH_TRANSLATED"]);
else 
  $upload_dir=dirname($_SERVER["SCRIPT_FILENAME"]);

// Handle file upload

if (!$error && isset($_REQUEST["uploadbutton"]))
{ if (is_uploaded_file($_FILES["dumpfile"]["tmp_name"]) && ($_FILES["dumpfile"]["error"])==0)
  { 
    $uploaded_filename=str_replace(" ","_",$_FILES["dumpfile"]["name"]);
    $uploaded_filename=preg_replace("/[^_A-Za-z0-9-\.]/i",'',$uploaded_filename);
    $uploaded_filepath=str_replace("\\","/",$upload_dir."/".$uploaded_filename);

    if (file_exists($uploaded_filename))
    { echo ("<p class=\"error\">File $uploaded_filename already exist! Delete and upload again!</p>\n");
    }
    else if (!eregi("(\.(sql|gz|csv))$",$uploaded_filename))
    { echo ("<p class=\"error\">You may only upload .sql .gz or .csv files.</p>\n");
    }
    else if (!@move_uploaded_file($_FILES["dumpfile"]["tmp_name"],$uploaded_filepath))
    { echo ("<p class=\"error\">Error moving uploaded file ".$_FILES["dumpfile"]["tmp_name"]." to the $uploaded_filepath</p>\n");
      echo ("<p>Check the directory permissions for $upload_dir (must be 777)!</p>\n");
    }
    else
    { echo ("<p class=\"success\">Uploaded file saved as $uploaded_filename</p>\n");
    }
  }
  else
  { echo ("<p class=\"error\">Error uploading file ".$_FILES["dumpfile"]["name"]."</p>\n");
  }
}


// Handle file deletion (delete only in the current directory for security reasons)

if (!$error && isset($_REQUEST["delete"]) && $_REQUEST["delete"]!=basename($_SERVER["SCRIPT_FILENAME"]))
{ if (eregi("(\.(sql|gz|csv))$",$_REQUEST["delete"]) && @unlink(basename($_REQUEST["delete"])))
    echo ("<p class=\"success\">".$_REQUEST["delete"]." was removed successfully</p>\n");
  else
    echo ("<p class=\"error\">Can't remove ".$_REQUEST["delete"]."</p>\n");
}


// Connect to the database

if (!$error && !TESTMODE)
{ $dbconnection = @mysql_connect($db_server,$db_username,$db_password);
  if ($dbconnection) 
    $db = mysql_select_db($db_name);
  if (!$dbconnection || !$db) 
  { echo ("<p class=\"error\">Database connection failed due to ".mysql_error()."</p>\n");
    echo ("<p>Edit the database settings in ".$_SERVER["SCRIPT_FILENAME"]." or contact your database provider</p>\n");
    $error=true;
  }
  if (!$error && $db_connection_charset!=='')
    @mysql_query("SET NAMES $db_connection_charset", $dbconnection);
}
else
{ $dbconnection = false;
}


// List uploaded files in multifile mode

if (!$error && !isset($_REQUEST["fn"]) && $filename=="")
{ if ($dirhandle = opendir($upload_dir)) 
  { $dirhead=false;
    while (false !== ($dirfile = readdir($dirhandle)))
    { if ($dirfile != "." && $dirfile != ".." && $dirfile!=basename($_SERVER["SCRIPT_FILENAME"]))
      { if (!$dirhead)
        { echo ("<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\">\n");
          echo ("<tr><th>Filename</th><th>Size</th><th>Date&amp;Time</th><th>Type</th><th>&nbsp;</th><th>&nbsp;</th>\n");
          $dirhead=true;
        }
        echo ("<tr><td>$dirfile</td><td class=\"right\">".filesize($dirfile)."</td><td>".date ("Y-m-d H:i:s", filemtime($dirfile))."</td>");

        if (eregi("\.sql$",$dirfile))
          echo ("<td>SQL</td>");
        elseif (eregi("\.gz$",$dirfile))
          echo ("<td>GZip</td>");
        elseif (eregi("\.csv$",$dirfile))
          echo ("<td>CSV</td>");
        else
          echo ("<td>Misc</td>");

        if ((eregi("\.gz$",$dirfile) && function_exists("gzopen")) || eregi("\.sql$",$dirfile) || eregi("\.csv$",$dirfile))
          echo ("<td><a href=\"".$_SERVER["PHP_SELF"]."?start=1&amp;fn=$dirfile&amp;foffset=0&amp;totalqueries=0\">Start Import</a> into $db_name at $db_server</td>\n <td><a href=\"".$_SERVER["PHP_SELF"]."?delete=$dirfile\">Delete file</a></td></tr>\n");
        else
          echo ("<td>&nbsp;</td>\n <td>&nbsp;</td></tr>\n");
      }

    }
    if ($dirhead) echo ("</table>\n");
    else echo ("<p>No uploaded files found in the working directory</p>\n");
    closedir($dirhandle); 
  }
  else
  { echo ("<p class=\"error\">Error listing directory $upload_dir</p>\n");
    $error=true;
  }
}


// Single file mode

if (!$error && !isset ($_REQUEST["fn"]) && $filename!="")
{ echo ("<p><a href=\"".$_SERVER["PHP_SELF"]."?start=1&amp;fn=$filename&amp;foffset=0&amp;totalqueries=0\">Start Import</a> from $filename into $db_name at $db_server</p>\n");
}


// File Upload Form

if (!$error && !isset($_REQUEST["fn"]) && $filename=="")
{ 

// Test permissions on working directory

  do { $tempfilename=time().".tmp"; } while (file_exists($tempfilename));
  if (!($tempfile=@fopen($tempfilename,"w")))
  { echo ("<p>Upload form disabled. Permissions for the working directory <i>$upload_dir</i> <b>must be set to 777</b> in order ");
    echo ("to upload files from here. Alternatively you can upload your dump files via FTP.</p>\n");
  }
  else
  { fclose($tempfile);
    unlink ($tempfilename);
 
    echo ("<p>You can now upload your dump file up to $upload_max_filesize bytes (".round ($upload_max_filesize/1024/1024)." Mbytes)  ");
    echo ("directly from your browser to the server. Alternatively you can upload your dump files of any size via FTP.</p>\n");
?>
<form method="post" action="<?php echo ($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="$upload_max_filesize" />
<p>Dump file: <input type="file" name="dumpfile" accept="*/*" size="60" /></p>
<p><input type="submit" name="uploadbutton" value="Upload" /></p>
</form>
<?php
  }
}

// Print the current mySQL connection charset

if (!$error && !TESTMODE && !isset($_REQUEST["fn"]) && $filename=="")
{ 
  $result = mysql_query("SHOW VARIABLES LIKE 'character_set_connection';");
  $row = mysql_fetch_assoc($result);
  if ($row) 
  { $charset = $row['Value'];
    echo ("<p>Note: The current mySQL connection charset is <i>$charset</i>. Your dump file must be encoded in <i>$charset</i> in order to avoid problems with non-latin characters. You can change the connection charset using the \$db_connection_charset variable in bigdump.php</p>\n");
  }
}

// Open the file

if (!$error && isset($_REQUEST["fn"]))
{ 

// Recognize GZip filename

	$_REQUEST["fn"] = $filename;

  if (eregi("\.gz$",$_REQUEST["fn"])) 
    $gzipmode=true;
  else
    $gzipmode=false;

  if ((!$gzipmode && !$file=fopen($_REQUEST["fn"],"rt")) || ($gzipmode && !$file=gzopen($_REQUEST["fn"],"rt")))
  { echo ("<p class=\"error\">Can't open ".$_REQUEST["fn"]." for import</p>\n");
    echo ("<p>Please, check that your dump file name contains only alphanumerical characters, and rename it accordingly, for example: ".
           $_REQUEST["fn"]." .<br />Or, you have to upload the ".$_REQUEST["fn"]." to the server</p>\n");
    $error=true;
  }

// Get the file size (can't do it fast on gzipped files, no idea how)

  else if ((!$gzipmode && fseek($file, 0, SEEK_END)==0) || ($gzipmode && gzseek($file, 0)==0))
  { if (!$gzipmode) $filesize = ftell($file);
    else $filesize = gztell($file);                   // Always zero, ignore
  }
  else
  { echo ("<p class=\"error\">I can't get the filesize of ".$_REQUEST["fn"]."</p>\n");
    $error=true;
  }
}

// *******************************************************************************************
// START IMPORT SESSION HERE
// *******************************************************************************************

if (!$error && isset($_REQUEST["start"]) && isset($_REQUEST["foffset"]) && eregi("(\.(sql|gz|csv))$",$_REQUEST["fn"]))
{

// Check start and foffset are numeric values

  if (!is_numeric($_REQUEST["start"]) || !is_numeric($_REQUEST["foffset"]))
  { echo ("<p class=\"error\">UNEXPECTED: Non-numeric values for start and foffset</p>\n");
    $error=true;
  }

  if (!$error)
  { $_REQUEST["start"]   = floor($_REQUEST["start"]);
    $_REQUEST["foffset"] = floor($_REQUEST["foffset"]);
    skin_open();
    if (TESTMODE) 
      echo ("<p class=\"centr\">TEST MODE ENABLED</p>\n");
    echo ("<p class=\"centr\">Processing file: <b>".$_REQUEST["fn"]."</b></p>\n");
    echo ("<p class=\"smlcentr\">Starting from line: ".$_REQUEST["start"]."</p>\n");	
    skin_close();
  }

// Check $_REQUEST["foffset"] upon $filesize (can't do it on gzipped files)

  if (!$error && !$gzipmode && $_REQUEST["foffset"]>$filesize)
  { echo ("<p class=\"error\">UNEXPECTED: Can't set file pointer behind the end of file</p>\n");
    $error=true;
  }

// Set file pointer to $_REQUEST["foffset"]

  if (!$error && ((!$gzipmode && fseek($file, $_REQUEST["foffset"])!=0) || ($gzipmode && gzseek($file, $_REQUEST["foffset"])!=0)))
  { echo ("<p class=\"error\">UNEXPECTED: Can't set file pointer to offset: ".$_REQUEST["foffset"]."</p>\n");
    $error=true;
  }

// Start processing queries from $file

  if (!$error)
  { $query="";
    $queries=0;
    $totalqueries=$_REQUEST["totalqueries"];
    $linenumber=$_REQUEST["start"];
    $querylines=0;
    $inparents=false;

// Stay processing as long as the $linespersession is not reached or the query is still incomplete

    while ($linenumber<$_REQUEST["start"]+$linespersession || $query!="")
    {

// Read the whole next line

      $dumpline = "";
      while (!feof($file) && substr ($dumpline, -1) != "\n")
      { if (!$gzipmode)
          $dumpline .= fgets($file, DATA_CHUNK_LENGTH);
        else
          $dumpline .= gzgets($file, DATA_CHUNK_LENGTH);
      }
      if ($dumpline==="") break;


// Stop if csv file is used, but $csv_insert_table is not set
      if (($csv_insert_table == "") && (eregi("(\.csv)$",$_REQUEST["fn"])))
      {
        echo ("<p class=\"error\">Stopped at the line $linenumber. </p>");
        echo ('<p>At this place the current query is from csv file, but $csv_insert_table was not set.');
        echo ("You have to tell where you want to send your data.</p>\n");
        $error=true;
        break;
      }
     
// Create an SQL query from CSV line

      if (($csv_insert_table != "") && (eregi("(\.csv)$",$_REQUEST["fn"])))
        $dumpline = 'INSERT INTO '.$csv_insert_table.' VALUES ('.$dumpline.');';

// Handle DOS and Mac encoded linebreaks (I don't know if it will work on Win32 or Mac Servers)

      $dumpline=str_replace("\r\n", "\n", $dumpline);
      $dumpline=str_replace("\r", "\n", $dumpline);
            
// DIAGNOSTIC
// echo ("<p>Line $linenumber: $dumpline</p>\n");

// Skip comments and blank lines only if NOT in parents

      if (!$inparents)
      { $skipline=false;
        reset($comment);
        foreach ($comment as $comment_value)
        { if (!$inparents && (trim($dumpline)=="" || strpos ($dumpline, $comment_value) === 0))
          { $skipline=true;
            break;
          }
        }
        if ($skipline)
        { $linenumber++;
          continue;
        }
      }

// Remove double back-slashes from the dumpline prior to count the quotes ('\\' can only be within strings)
      
      $dumpline_deslashed = str_replace ("\\\\","",$dumpline);

// Count ' and \' in the dumpline to avoid query break within a text field ending by ;
// Please don't use double quotes ('"')to surround strings, it wont work

      $parents=substr_count ($dumpline_deslashed, "'")-substr_count ($dumpline_deslashed, "\\'");
      if ($parents % 2 != 0)
        $inparents=!$inparents;

// Add the line to query

      $query .= $dumpline;

// Don't count the line if in parents (text fields may include unlimited linebreaks)
      
      if (!$inparents)
        $querylines++;
      
// Stop if query contains more lines as defined by MAX_QUERY_LINES

      if ($querylines>MAX_QUERY_LINES)
      {
        echo ("<p class=\"error\">Stopped at the line $linenumber. </p>");
        echo ("<p>At this place the current query includes more than ".MAX_QUERY_LINES." dump lines. That can happen if your dump file was ");
        echo ("created by some tool which doesn't place a semicolon followed by a linebreak at the end of each query, or if your dump contains ");
        echo ("extended inserts. Please read the BigDump FAQs for more infos.</p>\n");
        $error=true;
        break;
      }

// Execute query if end of query detected (; as last character) AND NOT in parents

      if (ereg(";$",trim($dumpline)) && !$inparents)
      { if (!TESTMODE && !mysql_query(trim($query), $dbconnection))
        { echo ("<p class=\"error\">Error at the line $linenumber: ". trim($dumpline)."</p>\n");
          echo ("<p>Query: ".trim(nl2br(htmlentities($query)))."</p>\n");
          echo ("<p>MySQL: ".mysql_error()."</p>\n");
          $error=true;
          break;
        }
        $totalqueries++;
        $queries++;
        $query="";
        $querylines=0;
      }
      $linenumber++;
    }
  }

// Get the current file position

  if (!$error)
  { if (!$gzipmode) 
      $foffset = ftell($file);
    else
      $foffset = gztell($file);
    if (!$foffset)
    { echo ("<p class=\"error\">UNEXPECTED: Can't read the file pointer offset</p>\n");
      $error=true;
    }
  }

// Print statistics

skin_open();

// echo ("<p class=\"centr\"><b>Statistics</b></p>\n");

  if (!$error)
  { 
    $lines_this   = $linenumber-$_REQUEST["start"];
    $lines_done   = $linenumber-1;
    $lines_togo   = ' ? ';
    $lines_tota   = ' ? ';
    
    $queries_this = $queries;
    $queries_done = $totalqueries;
    $queries_togo = ' ? ';
    $queries_tota = ' ? ';

    $bytes_this   = $foffset-$_REQUEST["foffset"];
    $bytes_done   = $foffset;
    $kbytes_this  = round($bytes_this/1024,2);
    $kbytes_done  = round($bytes_done/1024,2);
    $mbytes_this  = round($kbytes_this/1024,2);
    $mbytes_done  = round($kbytes_done/1024,2);
   
    if (!$gzipmode)
    {
      $bytes_togo  = $filesize-$foffset;
      $bytes_tota  = $filesize;
      $kbytes_togo = round($bytes_togo/1024,2);
      $kbytes_tota = round($bytes_tota/1024,2);
      $mbytes_togo = round($kbytes_togo/1024,2);
      $mbytes_tota = round($kbytes_tota/1024,2);
      
      $pct_this   = ceil($bytes_this/$filesize*100);
      $pct_done   = ceil($foffset/$filesize*100);
      $pct_togo   = 100 - $pct_done;
      $pct_tota   = 100;

      if ($bytes_togo==0) 
      { $lines_togo   = '0'; 
        $lines_tota   = $linenumber-1; 
        $queries_togo = '0'; 
        $queries_tota = $totalqueries; 
      }

      $pct_bar    = "<div style=\"height:15px;width:$pct_done%;background-color:#000080;margin:0px;\"></div>";
    }
    else
    {
      $bytes_togo  = ' ? ';
      $bytes_tota  = ' ? ';
      $kbytes_togo = ' ? ';
      $kbytes_tota = ' ? ';
      $mbytes_togo = ' ? ';
      $mbytes_tota = ' ? ';
      
      $pct_this    = ' ? ';
      $pct_done    = ' ? ';
      $pct_togo    = ' ? ';
      $pct_tota    = 100;
      $pct_bar     = str_replace(' ','&nbsp;','<tt>[         Not available for gzipped files          ]</tt>');
    }
    
    echo ("
    <center>
    <table width=\"520\" border=\"0\" cellpadding=\"3\" cellspacing=\"1\">
    <tr><th class=\"bg4\"> </th><th class=\"bg4\">Session</th><th class=\"bg4\">Done</th><th class=\"bg4\">To go</th><th class=\"bg4\">Total</th></tr>
    <tr><th class=\"bg4\">Lines</th><td class=\"bg3\">$lines_this</td><td class=\"bg3\">$lines_done</td><td class=\"bg3\">$lines_togo</td><td class=\"bg3\">$lines_tota</td></tr>
    <tr><th class=\"bg4\">Queries</th><td class=\"bg3\">$queries_this</td><td class=\"bg3\">$queries_done</td><td class=\"bg3\">$queries_togo</td><td class=\"bg3\">$queries_tota</td></tr>
    <tr><th class=\"bg4\">Bytes</th><td class=\"bg3\">$bytes_this</td><td class=\"bg3\">$bytes_done</td><td class=\"bg3\">$bytes_togo</td><td class=\"bg3\">$bytes_tota</td></tr>
    <tr><th class=\"bg4\">KB</th><td class=\"bg3\">$kbytes_this</td><td class=\"bg3\">$kbytes_done</td><td class=\"bg3\">$kbytes_togo</td><td class=\"bg3\">$kbytes_tota</td></tr>
    <tr><th class=\"bg4\">MB</th><td class=\"bg3\">$mbytes_this</td><td class=\"bg3\">$mbytes_done</td><td class=\"bg3\">$mbytes_togo</td><td class=\"bg3\">$mbytes_tota</td></tr>
    <tr><th class=\"bg4\">%</th><td class=\"bg3\">$pct_this</td><td class=\"bg3\">$pct_done</td><td class=\"bg3\">$pct_togo</td><td class=\"bg3\">$pct_tota</td></tr>
    <tr><th class=\"bg4\">% bar</th><td class=\"bgpctbar\" colspan=\"4\">$pct_bar</td></tr>
    </table>
    </center>
    \n");

// Finish message and restart the script

    if ($linenumber<$_REQUEST["start"]+$linespersession)
    { echo ("<p class=\"successcentr\">Congratulations: End of file reached, assuming OK</p>\n");
      echo ("<p class=\"centr\">Thank you for using this tool! Please rate <a href=\"http://www.hotscripts.com/Detailed/20922.html\" target=\"_blank\">Bigdump at Hotscripts.com</a></p>\n");
      echo ("<p class=\"centr\">You can send me some bucks or euros as appreciation <a href=\"http://www.ozerov.de/bigdump.php\" target=\"_blank\">via PayPal</a></p>\n");
      $error=true;
    }
    else
    { if ($delaypersession!=0)
        echo ("<p class=\"centr\">Now I'm <b>waiting $delaypersession milliseconds</b> before starting next session...</p>\n");
        if (!$ajax) 
          echo ("<script type=\"text/javascript\">window.setTimeout('location.href=\"".$_SERVER["PHP_SELF"]."?start=$linenumber&fn=".$_REQUEST["fn"]."&foffset=$foffset&totalqueries=$totalqueries\";',500+$delaypersession);</script>\n");
        echo ("<noscript>\n");
        echo ("<p class=\"centr\"><a href=\"".$_SERVER["PHP_SELF"]."?start=$linenumber&amp;fn=".$_REQUEST["fn"]."&amp;foffset=$foffset&amp;totalqueries=$totalqueries\">Continue from the line $linenumber</a> (Enable JavaScript to do it automatically)</p>\n");
        echo ("</noscript>\n");
   
      echo ("<p class=\"centr\">Press <b><a href=\"".$_SERVER["PHP_SELF"]."\">STOP</a></b> to abort the import <b>OR WAIT!</b></p>\n");
    }
  }
  else 
    echo ("<p class=\"error\">Stopped on error</p>\n");

skin_close();

}

if ($error)
  echo ("<p class=\"centr\"><a href=\"".$_SERVER["PHP_SELF"]."\">Start from the beginning</a> (DROP the old tables before restarting)</p>\n");

if ($dbconnection) mysql_close();
if ($file && !$gzipmode) fclose($file);
else if ($file && $gzipmode) gzclose($file);

?>

<p class="centr">� 2003-2007 <a href="mailto:alexey@ozerov.de">Alexey Ozerov</a> - <a href="http://www.ozerov.de/bigdump.php" target="_blank">BigDump Home</a></p>

</td></tr></table>

</center>

</body>
</html>

<?php

// *******************************************************************************************
// 				AJAX functionality starts here
// *******************************************************************************************

// Handle special situations (errors, and finish)

if ($error) 
{
  $out1 = ob_get_contents();
  ob_end_clean();
  echo $out1;
  die;
}

// Creates responses  (XML only or web page)

if (($ajax) && isset($_REQUEST['start']))
{
  if (isset($_REQUEST['ajaxrequest'])) 
  {	ob_end_clean();
		create_xml_response();
		die;
	} 
	else 
	{
	  create_ajax_script();	  
	}  
}
ob_flush();

// *******************************************************************************************
// 				AJAX utilities
// *******************************************************************************************

function create_xml_response() 
{
  global $linenumber, $foffset, $totalqueries, 
				 $lines_this, $lines_done, $lines_togo, $lines_tota,
				 $queries_this, $queries_done, $queries_togo, $queries_tota,
				 $bytes_this, $bytes_done, $bytes_togo, $bytes_tota,
				 $kbytes_this, $kbytes_done, $kbytes_togo, $kbytes_tota,
				 $mbytes_this, $mbytes_done, $mbytes_togo, $mbytes_tota,
				 $pct_this, $pct_done, $pct_togo, $pct_tota,$pct_bar;
	//echo "Content-type: application/xml; charset='iso-8859-1'";
	header('Content-Type: application/xml');
	header('Cache-Control: no-cache');
	/*	
	echo '<?xml version="1.0"?>'."\n";
	echo '<root>'."\n";
	echo 'cos'."\n";
	echo '</root>'."\n";
	*/
	
	echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
	echo "<root>";
	// data - for calculations
	echo "<linenumber>";
	echo "$linenumber";
	echo "</linenumber>";
	echo "<foffset>";
	echo "$foffset";
	echo "</foffset>";
	echo "<fn>";
	echo '"'.$_REQUEST['fn'].'"';
	echo "</fn>";
	echo "<totalqueries>";
	echo "$totalqueries";
	echo "</totalqueries>";
	// results - for form update
	echo "<elem1>";
	echo "$lines_this";
	echo "</elem1>";
	echo "<elem2>";
	echo "$lines_done";
	echo "</elem2>";
	echo "<elem3>";
	echo "$lines_togo";
	echo "</elem3>";
	echo "<elem4>";
	echo "$lines_tota";
	echo "</elem4>";
	
	echo "<elem5>";
	echo "$queries_this";
	echo "</elem5>";
	echo "<elem6>";
	echo "$queries_done";
	echo "</elem6>";
	echo "<elem7>";
	echo "$queries_togo";
	echo "</elem7>";
	echo "<elem8>";
	echo "$queries_tota";
	echo "</elem8>";
	
	echo "<elem9>";
	echo "$bytes_this";
	echo "</elem9>";
	echo "<elem10>";
	echo "$bytes_done";
	echo "</elem10>";
	echo "<elem11>";
	echo "$bytes_togo";
	echo "</elem11>";
	echo "<elem12>";
	echo "$bytes_tota";
	echo "</elem12>";
			
	echo "<elem13>";
	echo "$kbytes_this";
	echo "</elem13>";
	echo "<elem14>";
	echo "$kbytes_done";
	echo "</elem14>";
	echo "<elem15>";
	echo "$kbytes_togo";
	echo "</elem15>";
	echo "<elem16>";
	echo "$kbytes_tota";
	echo "</elem16>";
	
	echo "<elem17>";
	echo "$mbytes_this";
	echo "</elem17>";
	echo "<elem18>";
	echo "$mbytes_done";
	echo "</elem18>";
	echo "<elem19>";
	echo "$mbytes_togo";
	echo "</elem19>";
	echo "<elem20>";
	echo "$mbytes_tota";
	echo "</elem20>";
	
	echo "<elem21>";
	echo "$pct_this";
	echo "</elem21>";
	echo "<elem22>";
	echo "$pct_done";
	echo "</elem22>";
	echo "<elem23>";
	echo "$pct_togo";
	echo "</elem23>";
	echo "<elem24>";
	echo "$pct_tota";
	echo "</elem24>";
	
	// converting html to normal text
	$pct_bar    = htmlentities($pct_bar);	  
	echo "<elem_bar>";
	echo "$pct_bar";
	echo "</elem_bar>";
				
	echo "</root>";		
	
}

function create_ajax_script() 
{
  global $linenumber, $foffset, $totalqueries, $delaypersession;
	?>
	<script type="text/javascript">			

	// creates next action url (upload page, or XML response)
	function get_url(linenumber,fn,foffset,totalqueries) {
		return "<?php echo $_SERVER['PHP_SELF'] ?>"+
			"?start="+linenumber+"&fn="+fn+"&foffset="+
			foffset+"&totalqueries="+totalqueries+"&ajaxrequest=true";
	}
	
	// extracts text from XML element (itemname must be unique)
	function get_xml_data(itemname,xmld) {
		return xmld.getElementsByTagName(itemname).item(0).firstChild.data;
	}
	
	// action url (upload page)
	var url_request =  get_url(<?php echo $linenumber.',"'.$_REQUEST["fn"].'",'.$foffset.','.$totalqueries;?>);
	var http_request = false;
	
	function makeRequest(url) {
		http_request = false;
		if (window.XMLHttpRequest) { 
		// Mozilla,...
			http_request = new XMLHttpRequest();
			if (http_request.overrideMimeType) {
				http_request.overrideMimeType("text/xml");
			}
		} else if (window.ActiveXObject) { 
		// IE
			try {
				http_request = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				try {
					http_request = new ActiveXObject("Microsoft.XMLHTTP");
				} catch(e) {}
			}
		}
		if (!http_request) {
				alert("Cannot create an XMLHTTP instance");
				return false;
		}
		http_request.onreadystatechange = server_response;
		http_request.open("GET", url, true);
		http_request.send(null);
	}
	
	function server_response() 
	{

	  // waiting for correct response
	  if (http_request.readyState != 4)
			return;
	  if (http_request.status != 200) {
	    alert("Page unavailable, or wrong url!")
			return;
		}
		
		// r = xml response
		var r = http_request.responseXML;
		
		//if received not XML but HTML with new page to show
		if (r.getElementsByTagName('root').length == 0) {                   	//*
			var text = http_request.responseText;
			document.open();
			document.write(text);		
			document.close();	
			return;		
		}
		
		// update "Starting from line: "
		document.getElementsByTagName('p').item(1).innerHTML = 
			"Starting from line: " + 
			   r.getElementsByTagName('linenumber').item(0).firstChild.nodeValue;
		
		// update table with new values
		for(i = 1; i <= 24; i++) {						
			document.getElementsByTagName('td').item(i).firstChild.data = 
				get_xml_data('elem'+i,r);
		}				
		
		// update color bar
		document.getElementsByTagName('td').item(25).innerHTML = 
			r.getElementsByTagName('elem_bar').item(0).firstChild.nodeValue;
			 
		// action url (XML response)	 
		url_request =  get_url(
			get_xml_data('linenumber',r),
			get_xml_data('fn',r),
			get_xml_data('foffset',r),
			get_xml_data('totalqueries',r));
		
		// ask for XML response	
		window.setTimeout("makeRequest(url_request)",500+<?php echo $delaypersession; ?>);
	}
	// ask for upload page
	window.setTimeout("makeRequest(url_request)",500+<?php echo $delaypersession; ?>);
	</script>
	<?php
}

?>
