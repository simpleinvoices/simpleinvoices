<?php	include("header.php"); ?>
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
			<a href="preferences.php"><input type="submit" name="Continue" value="<?php echo $LANG['continue']; ?>" /></a>
			<!--<form method="post" action="preferences.php">
				<input type="submit" name="Continue" value="<?php echo $LANG['continue']; ?>">
			</form>-->
			<hr />
			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>
<?php include("footer.php"); ?>
