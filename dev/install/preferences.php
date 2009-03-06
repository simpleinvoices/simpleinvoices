<?php include("header.php"); ?>
<?php include_once("functions.php"); ?>
<div id="Wrapper">
	<div id="Container">
		<div class="Full">
			<div class="col">
			<h1>Simple Invoices :: installer</h1>
			<hr />
			<br />
			<h2><?php echo $LANG['installRequirements'] ?></h2>
			<br />
			<table align="center">
				<tr>
					<td><?php if(checkPDO()) echo $LANG['PDO_true']; else echo $LANG['PDO_false']; ?></td>
					<td><?php if(checkPDO()) echo '<img src="./images/valid.png" alt="valid" />'; else echo '<img src="./images/no.png" alt="Failure" />'; ?></td>
				</tr>
				<tr>
					<td><?php if(checkGD()) echo $LANG['GD_true']; else echo $LANG['GD_false']; ?></td>
					<td><?php if(checkGD()) echo '<img src="./images/valid.png" alt="valid" />'; else echo '<img src="./images/no.png" alt="Failure" />'; ?></td>
				</tr>
				<tr>
					<td><?php if(checkPostMaxSize()) echo $LANG['post_valid_1'] ."<b>".$post_max_size."</b>" .$LANG['post_valid_2']; else echo $LANG['post_caution_1'] ."<b>".$post_max_size."</b>" .$LANG['post_caution_2']; ?></td>
					<td><?php if(checkPostMaxSize()) echo '<img src="./images/valid.png" alt="valid" />'; else echo '<img src="./images/attention.png" alt="Caution" />'; ?></td>
				</tr>
				<tr>
					<td><?php if(checkMemoryLimit()) echo $LANG['memory_valid_1'] ."<b>".$memory_limit."</b>" .$LANG['memory_valid_2']; else echo $LANG['memory_caution_1'] ."<b>".$memory_limit."</b>" .$LANG['memory_caution_2']; ?></td>
					<td><?php if(checkMemoryLimit()) echo '<img src="./images/valid.png" alt="valid" />'; else echo '<img src="./images/attention.png" alt="Caution" />'; ?></td>
				</tr>
				<tr>
					<td><?php if(checkXSLT()) echo $LANG['xslt_true']; else echo $LANG['xslt_false']; ?></td>
					<td><?php if(checkXSLT()) echo '<img src="./images/valid.png" alt="valid"/>'; else echo '<img src="./images/no.png" alt="Failure"/> '; ?></td>
				</tr>
				<tr>
					<td><?php if(checkConfigPermissions()) echo "Simple Invoices config is writeable"; else echo "Simple Invoices config isn't writeable"; ?></td>
					<td><?php if(checkConfigPermissions()) echo '<img src="./images/valid.png" alt="valid" />'; else echo '<img src="./images/no.png" alt="Failure" />'; ?></td>
				</tr>
				<tr>
					<td><?php if(checkCachePermissions()) echo "Cache is writeable"; else echo "Cache isn't writeable"; ?></td>
					<td><?php if(checkCachePermissions()) echo '<img src="./images/valid.png" alt="valid" />'; else echo '<img src="./images/no.png" alt="Failure" />'; ?></td>
				</tr>
			</table>
			<br /><br />
			<?php
				if(checkCachePermissions() && checkConfigPermissions() && checkGD()) {
			?>
				<a href="connection.php"><input type="submit" name="Submit" value="<?php echo $LANG['continue'] ?>"></a>
			<?php 
				}
				else {
			?>
					<a href="preferences.php"><?php echo $LANG['reload'] ?></a>
			<?php
				}
			?>
			<hr />
			</div>
			<div class="bottom"></div>
		</div>
	</div>
</div>
<?php include("footer.php"); ?>
