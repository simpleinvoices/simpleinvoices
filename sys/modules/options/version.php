<?php
/*
   ============================================================================================================================
   Simple Invoices
   www.simpleinvoices.org

   Version control

   This file will retrieve the version file and perform a version check:
      if the version id numeric (in the format yyyymmdd) match then the version is up to date
      BUT if the description differ then the version is up to date but there is an optional (minor) update
      if the version id numeric differ then there's an advised update

   Build 2010.10.8
   Albert Drent
   Aducom Software
   ============================================================================================================================
*/

//stop the direct browsing to this file - let index.php handle which files get displayed

checkLogin();
// this should be in the definition file

 $c = curl_init();
 curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($c, CURLOPT_URL,SI_VERSION_FILE);
 $contents = curl_exec($c);
 curl_close($c);

 //$doc = new SimpleXMLElement($contents);
 // we now do it the text way
 $varray = explode(chr(10),$contents);

 $smarty->Assign("localVersion", SI_VERSION_ID);
 $smarty->Assign("serverVersion", $varray[6]);
 $smarty->Assign("si_serverversion", $varray[3]);
 $smarty->Assign("downloadurl",$varray[9]);
 $result = '';
 // if the version identifier is not equal then there's a new version on the server
 if (SI_VERSION_ID ==$varray[6]) {
   // version is ok
   $result.="<b>".$LANG['version_ok']."</b>";
   // but if description differ then there's a minor update available

   if (strcasecmp(SI_VERSION, $varray[3])!=0) {
       $result.=$LANG['version_notreq']."<br>";
   }
 }
 else
 {
   // version is out-of-date
   $result = "<b>".$LANG['version_outofdate']."</b><br>";
   $result .= '<a href="'.$doc->downloadLink.'">download</a><br>';
 }

// $result.="<br><br>".$LANG['version_notes']."<br><br>";
// foreach ($doc->releaseNotes->note as $r)
//    {
//      $result .= $r."<br>";
//    }

 $smarty->Assign("versionControl", $result);
