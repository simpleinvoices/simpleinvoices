<?php
session_start();

$language = $_SESSION['language'];

// +-----------------------------------------------------------------------+
// | Simple Invoices                                                       |
// | Licence: GNU General Public License 2.0                               |
// +-----------------------------------------------------------------------+

// Selection de la langue de l'installeur
include('lang/lang_'.$language.'.php');
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


<?php

// Contrôle taille mémoire
// Simple invoices want if possible 24M.
$post_max_size = ini_get('post_max_size');

function controle_post_max_size() {
	if(substr($post_max_size, 0, strlen($post_max_size)-1) >= 24) {
		return $boolMaxSize = true;
	}
	else if(substr($post_max_size, 0, strlen($post_max_size)-1) < 24) {
		return $boolMaxSize = false;
	}
}

// Contrôle taille mémoire
// Simple invoices want if possible 24M.
$memory_limit = ini_get('memory_limit');

function controle_memory_limit() {
	if(substr($memory_limit, 0, strlen($memory_limit)-1) >= 24) {
		return $boolMaxSize = true;
	}
	else if(substr($memory_limit, 0, strlen($memory_limit)-1) < 24) {
		return $boolMaxSize = false;
	}
}

// Contrôle existence librairie GD
function controleGd() {
if (extension_loaded('gd')) {
	return true; }
	else {
	return false; }
}

// Contrôle existence xslt
function controleXslt() {
if (extension_loaded('xslt')) {
	return true; }
	else {
	return false; }	
}

?>

<?php echo $LANG['installRequirements'] ?>
<br /><br />
<table border="1px" cellpadding="4px" align="center">
<tr>
<td><?php if(controleGd() == TRUE) echo $LANG['GD_true']; else echo $LANG['GD_false']; ?></td>
<td><?php if(extension_loaded('gd')) echo '<img src="./images/valid.png" alt="Success"/>'; else echo '<img src="./images/no.png" alt="Failure"/>'; ?></td>
</tr>
<tr>
<td><?php if(controle_post_max_size() == true) echo $LANG['memory_yes_1'] ."<b>".$post_max_size."</b>" .$LANG['memory_yes_2']; else echo $LANG['memory_no_1'] ."<b>".$post_max_size."</b>" .$LANG['memory_no_2']; ?></td>
<td><?php if($boolMaxSize == true) echo '<img src="./images/valid.png" alt=""/>'; else echo '<img src="./images/attention.png" alt="Caution"/>'; ?></td>
</tr>
<tr>
<td><?php if(controle_memory_limit() == true) echo $LANG['memory_yes_1'] ."<b>".$memory_limit."</b>" .$LANG['memory_yes_2']; else echo $LANG['memory_no_1'] ."<b>".$memory_limit."</b>" .$LANG['memory_no_2']; ?></td>
<td><?php if($boolMaxSize == true) echo '<img src="./images/valid.png" alt=""/>'; else echo '<img src="./images/attention.png" alt="Caution"/>'; ?></td>
</tr>
<tr>
<td><?php if(controleXslt() == TRUE) echo $LANG['xslt_true']; else echo $LANG['xslt_false']; ?></td>
<td><?php if(extension_loaded('xslt')) echo '<img src="./images/valid.png" alt="Success"/>'; else echo '<img src="./images/no.png" alt="Failure"/>'; ?></td>
</tr>
</table>

			<br />
			<form name="connection" method="post" action="connection.php">
				<input type="submit" name="Submit" value="<?php echo $LANG['continue'] ?>">
			</form>

			<hr></hr>

			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>

</body>
</html>
