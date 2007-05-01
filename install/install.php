<?php
session_start();

//All files need for the installation should be placed in the folder install (sql-queries etc..). An this folder should be deleted after installation

// +-----------------------------------------------------------------------+
// | Simple Invoices                                                       |
// | Licence: GNU General Public License 2.0                               |
// +-----------------------------------------------------------------------+

// définition automatique de la langue du navigateur
$langNav = substr(getenv("HTTP_ACCEPT_LANGUAGE"),0,2);

// Selection du fichier de langue
if(!empty($langNav)) {
include('lang/lang_'.$langNav.'.php');
$_SESSION['language']= $langNav;
}
else
$langNav = "en";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<title>Simple Invoices | Installer</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" type="text/css" href="./css/screen.css" media="all"/>


	<!-- Additional IE/Win specific style sheet (Conditional Comments) -->
	<!--[if lte IE 7]>
	<style type="text/css" media="screen, projection">
	    body {
		font-size: 100%; /* resizable fonts */
	    }
	</style>
	<![endif]-->


<div id="Wrapper">
	<div id="Container">
 
		<div class="Full">
			<div class="col">
   	
			<h1>Simple invoices :: installer</h1>
			<hr></hr>
			<br />
			<p><?php
			
			echo $LANG['welcome'] ."<br />";
			echo $LANG['procedure'] ."<br />";
			echo $LANG['step1'] ."<br />";
			echo $LANG['step2'] ."<br />";
			echo $LANG['step3'] ."<br />";
			echo $LANG['step4'] ."<br />";
			
			?></p>
			
			<br /><br />
			<!-- Choix de la langue -->
			<form method="post" action="preferences.php">

				<input type="submit" name="Continue" value="<?php echo $LANG['continue']; ?>">

			</form>

			<hr></hr>

			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>

</body>
</html>