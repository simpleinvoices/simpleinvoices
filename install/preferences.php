<?php
session_start();

$language = $_SESSION['language'];

// +-----------------------------------------------------------------------+
// | Simple Invoices                                                       |
// | Licence: GNU General Public License 2.0                               |
// +-----------------------------------------------------------------------+

// Select the language
include('lang/lang_'.$language.'.php');
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
   	
			<h1>Simple Invoices :: installer</h1>
			<hr />

<?php

// Control size memory
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

// Mémoire maximum allouée pendant l'exécution d'un script
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

// Control library GD existence
function controleGd() {
if (extension_loaded('gd')) {
	return true; }
	else {
	return false; }
}

// Control xslt existence
function controleXslt() {
if (extension_loaded('xslt')) {
	return true; }
	else {
	return false; }	
}

?>
<br />
<h2><?php echo $LANG['installRequirements'] ?></h2>
<br />

<table border="1px" cellpadding="7px" align="center">
	<tr>
		<td><?php if(controleGd() == TRUE) echo $LANG['GD_true']; else echo $LANG['GD_false']; ?></td>
		<td><?php if(extension_loaded('gd')) echo '<img src="./images/valid.png" alt="valid"/>'; else echo '<img src="./images/no.png" alt="Failure"/>'; ?></td>
	</tr>
	<tr>
		<td><?php if(controle_post_max_size() == true) echo $LANG['memory_valid_1'] ."<b>".$post_max_size."</b>" .$LANG['memory_valid_2']; else echo $LANG['memory_caution_1'] ."<b>".$post_max_size."</b>" .$LANG['memory_caution_2']; ?></td>
		<td><?php if($boolMaxSize == true) echo '<img src="./images/valid.png" alt="valid"/>'; else echo '<img src="./images/attention.png" alt="Caution"/>'; ?></td>
	</tr>
	<tr>
		<td><?php if(controle_memory_limit() == true) echo $LANG['memory_valid_1'] ."<b>".$memory_limit."</b>" .$LANG['memory_valid_2']; else echo $LANG['memory_caution_1'] ."<b>".$memory_limit."</b>" .$LANG['memory_caution_2']; ?></td>
		<td><?php if($boolMaxSize == true) echo '<img src="./images/valid.png" alt="valid"/>'; else echo '<img src="./images/attention.png" alt="Caution"/>'; ?></td>
	</tr>
	<tr>
		<td><?php if(controleXslt() == TRUE) echo $LANG['xslt_true']; else echo $LANG['xslt_false']; ?></td>
		<td><?php if(extension_loaded('xslt')) echo '<img src="./images/valid.png" alt="valid"/>'; else echo '<img src="./images/no.png" alt="Failure"/>'; ?></td>
	</tr>
</table>

			<br /><br />
			<form name="connection" method="post" action="connection.php">
				<input type="submit" name="Submit" value="<?php echo $LANG['continue'] ?>">
			</form>

			<hr />

			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>

</body>
</html>
