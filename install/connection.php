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


			<!-- connexion -->
			<form name="connection" method="post" action="connection_post.php">
			<input type="hidden" name="Click" value="on">

				<label for="host"><?php echo $LANG['DBHost'] ?></label>
				<input name="host" type="text" value="<?php echo $host; ?>" size="20">
				<?php
				if($_SESSION['DBHost_error'] != '') {
					echo $_SESSION['DBHost_error'].'<br />';
				}
				?>

				<label for="dbname"><?php echo $LANG['DBName'] ?></label>
				<input name="dbname" type="text" value="<?php echo $dbname; ?>" size="20">
				<?php
				if($_SESSION['DBName_error'] != '') {
					echo $_SESSION['DBName_error'].'<br />';
				}
				?>

				<label for="username"><?php echo $LANG['DBUsername'] ?></label>
				<input name="username" type="text" value="<?php echo $username; ?>" size="20">
				<?php
				if($_SESSION['DBUsername_error'] != '') {
					echo $_SESSION['DBUsername_error'].'<br />';
				}
				?>

				<label for="passwd"><?php echo $LANG['DBPassword'] ?></label>
				<input name="passwd" type="password" size="20">
				<?php
				if($_SESSION['DBPassword_error'] != '') {
					echo $_SESSION['DBPassword_error'].'<br />';
				}
				?>
				
				<label for="prefix"><?php echo $LANG['prefix'] ?></label>
				<input name="prefix" type="text" value="<?php echo $table_prefix; ?>" size="20">
				<?php
				if($_SESSION['prefix_error'] != '') {
					echo $_SESSION['prefix_error'].'<br />';
				}
				?>

			<p>
			<br /><br />
				<input type="submit" name="submit[create]" value="<?php echo $LANG['createDB'] ?>">
				<input type="submit" name="submit[drop]" value="<?php echo $LANG['replaceDB'] ?>">
			</p>
			</form>

			<hr />

			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>

</body>
</html>