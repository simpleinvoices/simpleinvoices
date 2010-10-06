<?php
/*
   ============================================================================================================================
   Simple Invoices
   www.simpleinvoices.org
   
   Version control
 
   This file will retrieve the version xml file and perform a version check:
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

 $doc = new SimpleXMLElement($contents);

 $smarty->Assign("localVersion", SI_VERSION_ID);
 $smarty->Assign("serverVersion", $doc->versionId);
 $smarty->Assign("si_serverversion", $doc->versionTitle);
 $smarty->Assign("downloadurl",$doc->downloadLink);
 $result = '';                            
 // if the version identifier is not equal then there's a new version on the server
 if (SI_VERSION_ID == $doc->versionId) {
   // version is ok  
   $result.="<b>".$LANG['version_ok']."</b>";  
   // but if description differ then there's a minor update available
      
   if ($SI_VERSION != $doc->versionTitle) {
       $result.=$LANG['version_notreq']."<br>";
   }   
 }
 else 
 { 
   // version is out-of-date
   $result = "<b>".$LANG['version_outofdate']."</b><br>";
   $result .= '<a href="'.$doc->downloadLink.'">download</a><br>';
 }
   
 $result.="<br><br>".$LANG['version_notes']."<br><br>";  
 foreach ($doc->releaseNotes->note as $r)
    {
      $result .= $r."<br>";   
    }
 
 $smarty->Assign("versionControl", $result); 

?>
