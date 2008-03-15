<?php include("header.php"); ?>

<div id="Wrapper">
	<div id="Container">

		<div class="Full">    
			<div class="col">
			<h1>Simple Invoices :: installer</h1>
			<hr />


			<!-- connexion -->
			<form name="connection" method="post" action="connection_post.php">
				<input type="hidden" name="Click" value="on">

				<label for="server"><?php echo $LANG['DBServer'] ?></label>
				<select name="server">
					<option value="mysql">MySQL 5+</option>
					<option value="pgsql">PostgreSQL 8.1+</option>
				</select>

				<label for="host"><?php echo $LANG['DBHost'] ?></label>
				<input name="host" type="text" value="<?php echo $host; ?>" size="20">


				<label for="dbname"><?php echo $LANG['DBName'] ?></label>
				<input name="dbname" type="text" value="<?php echo $dbname; ?>" size="20">


				<label for="username"><?php echo $LANG['DBUsername'] ?></label>
				<input name="username" type="text" value="<?php echo $username; ?>" size="20">


				<label for="passwd"><?php echo $LANG['DBPassword'] ?></label>
				<input name="passwd" type="password" size="20">

				
				<label for="prefix"><?php echo $LANG['prefix'] ?></label>
				<input name="prefix" type="text" value="<?php echo $table_prefix; ?>" size="20">

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
<?php include("footer.php"); ?>
