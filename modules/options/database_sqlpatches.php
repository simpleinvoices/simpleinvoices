<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

include('./include/sql_patches.php');


function getNumberOfPatches() {
	global $patch;
	#Max patches applied - start
	global $tb_prefix;
	

	$check_patches_sql = "SELECT count(sql_patch) AS count FROM {$tb_prefix}sql_patchmanager ";
	$patches_result = mysqlQuery($check_patches_sql) or die(mysql_error());
		
	$patches = mysql_fetch_array($patches_result);
	$patch_count = count($patch);
	
	//Returns number of patches to be applied
	return count($patch) - $patches['count'];
}


function runPatches() {
	global $tb_prefix;
	global $patch;
	#DEFINE SQL PATCH
	
	if(mysql_num_rows(mysqlQuery("SHOW TABLES LIKE '{$tb_prefix}sql_patchmanager'")) == 1) {

		$display_block = "<table align='center'>";

		for($i=0;$i < count($patch);$i++) {
			run_sql_patch($i,$patch[$i]);
		}


		$display_block .= <<<EOD
		<br>
		<b>Simple Invoices :: Database Upgrade Manager</b><br />
		<hr />
		<tr><td><br>The database patches have now been applied. You can now start working with Simple Invoices.<br /><p align=middle><br /><a href="index.php">HOME</a></p></tr>
		</table>

EOD;
	$refresh = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php>";

	} else {


		$display_block .= "<table align='center'>";
		$display_block .= "<br><br><tr><td>Step 1 - This is the first time Database Updates has been run</td></tr><br>";
		
		initialise_sql_patch();
		
		$display_block .= "<tr><td><br>Now that the Database upgrade table has been initialised, please go back to the Database Upgrade Manger page by clicking <a href='index.php?module=options&view=database_sqlpatches'>HERE</a> to run the remaining patches</td></tr>";
		$display_block .= "</table></div>";

	}
	
	global $smarty;
	$smarty-> assign("display_block",$display_block);
	$smarty-> assign("refresh",$refresh);

}

function listPatches() {
	global $tb_prefix;
	global $patch;

	//if(mysql_num_rows(mysqlQuery("SHOW TABLES LIKE '{$tb_prefix}sql_patchmanager'")) == 1) {

		$display_block = <<<EOD
		<b>Simple Invoices :: Database Upgrade Manager</b><br /><br />
		
		Your version of Simple Invoices has been upgraded<br><br>  
		With this new release there are database patches that need to be applied<br><br>
		
		<hr></hr>

		<table align="center">
			<tr></i><tr><td><br>The list below describes which patches have and have not been applied to the database, the aim is to have them all applied.  If there are patches that have not been applied to the Simple Invoices database, please run the Update database by clicking update </td></tr><tr align=center><td><p class='align_center'><br><a href='index.php?case=run'>UPDATE</a></p></td></tr></table><br>
<a href="docs.php?t=help&p=text" rel="gb_page_center[450, 450]"><font color="red"><img src="./images/common/important.png"></img>Warning:</font></a>
<table align="center">
EOD;


		for($p = 0; $p < count($patch);$p++) {
			if(check_sql_patch($p,$patch[$p]['name'])) {
				$display_block .= "<tr><td>SQL patch $p, {$patch[$p]['name']} <i>has</i> already been applied in release {$patch[$p]['date']}</td></tr>";
			}
			else {
				$display_block .= "<tr><td>SQL patch $p, {$patch[$p]['name']}  <b>has not</b> been applied to the database</td></tr>";
			}	
		}

		$display_block .= "</table>";
	global $smarty;
	$smarty-> assign("display_block",$display_block);
	/*}
	else {
		echo <<<EOD
		<table align="center">
          <tr><td><br>This is the first time that the Database Upgrade process is to be run.  The first step in the process is to Initialse the database upgrade table. To do this click the Initialise database button<br><br><a href='index.php?module=options&view=database_sqlpatches&op=run_updates'>INITIALISE DATABASE UPGRADE</a></td></tr>
		</table>
EOD;
	}*/

}



function check_sql_patch($check_sql_patch_ref, $check_sql_patch_field) {
    global $tb_prefix;
	$sql = "SELECT * FROM {$tb_prefix}sql_patchmanager WHERE sql_patch_ref = $check_sql_patch_ref" ;

	$query = mysqlQuery($sql) or die(mysql_error());

	if(mysql_num_rows($query) > 0) {
		return true;
	}
	
	return false;
}




function run_sql_patch($id, $patch) {

	global $tb_prefix;

	$sql = "SELECT * FROM {$tb_prefix}sql_patchmanager WHERE sql_patch_ref = $id" ;
	$query = mysqlQuery($sql) or die(mysql_error());
	
	//echo $sql;
	#forget about it!! the patch as its already been run
	if (mysql_num_rows($query) != 0)  {

		$display_block = <<<EOD
		</div id="header">
		<tr><td>Skipping SQL patch $id, $patch[name] as it <i>has</i> already been applied</td></tr>
EOD;
	}
	else {
		//patch hasn't been run
		#so do the bloody patch
		mysqlQuery($patch['patch']) or die(mysql_error());
		

		$display_block  = <<<EOD
			<tr><td>SQL patch $id, $patch[name] <i>has</i> been applied to the database</td></tr>
EOD;
		# now update the {$tb_prefix}sql_patchmanager table
		
		
		$sql_update = "INSERT INTO {$tb_prefix}sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ($id,'$patch[name]',$patch[date],'".addslashes($patch['patch'])."')";
		
		/*echo $sql_update;*/

		mysqlQuery($sql_update) or die(mysql_error());

		$display_block .= "<tr><td>SQL patch $id, $patch[name] <b>has</b> been applied</td></tr>";
	}
	
	global $smarty;
	$smarty-> assign("display_block",$display_block);
}


function initialise_sql_patch() {

	#check sql patch 1
	$sql_patch_init = "CREATE TABLE {$tb_prefix}sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 50 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) TYPE = MYISAM ";
	mysqlQuery($sql_patch_init) or die(mysql_error());

	$display_block = "<tr><td>Step 2 - The SQL patch table has been created<br></td></tr>";

	echo $display_block;

	$sql_insert = "INSERT INTO {$tb_prefix}sql_patchmanager
 ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement )
VALUES ('','1','Create {$tb_prefix}sql_patchmanger table','20060514','$sql_patch_init')";
	mysqlQuery($sql_insert, $conn) or die(mysql_error());

	$display_block2 = "<tr><td>Step 3 - The SQL patch has been inserted into the SQL patch table<br></td></tr>";
	
	echo $display_block2;
}


?>
