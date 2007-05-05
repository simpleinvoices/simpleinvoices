<?php
session_start();

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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<title>Simple Invoices | Installer</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="./css/screen.css" media="all"/>


<div id="Wrapper">
	<div id="Container">
 
		<div class="Full">
			<div class="col">
   	
			<h1>Simple Invoices :: Installer</h1>
			<hr />
			<br />
			
			<h2><?php echo $LANG['welcome'] ."<br />"; ?></h2>
			
			<div id="welcome">
				<p><?php echo $LANG['intro'] ."<br />"; ?></p>
			</div>
			
			<br /><br />

			<form method="post" action="preferences.php">
				<input type="submit" name="Continue" value="<?php echo $LANG['continue']; ?>">
			</form>

			<hr />

			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>

</body>
</html>