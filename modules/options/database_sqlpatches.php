<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
//checkLogin();

include('./include/sql_patches.php');

// Made it into 2 functions to get rid of the old defaults table

$db = new db();
function getNumberOfDonePatches() {


    $db = new db();
	$check_patches_sql = "SELECT count(sql_patch) AS count FROM ".TB_PREFIX."sql_patchmanager ";
	$sth = $db->query($check_patches_sql) or die(htmlsafe(end($dbh->errorInfo())));
		
	$patches = $sth->fetch();
	
	//Returns number of patches applied
	return $patches['count'];
}

function getNumberOfPatches() {
	global $patch;
	#Max patches applied - start
		
	$patches = getNumberOfDonePatches();
	$patch_count = count($patch);
	
	//Returns number of patches to be applied
	return $patch_count - $patches;
}


function runPatches() {
	global $patch;
	global $db_server;
	global $dbh;
    $db = new db();
	#DEFINE SQL PATCH

	$display_block = "";

	$sql = "SHOW TABLES LIKE '".TB_PREFIX."sql_patchmanager'";
	if ($db_server == 'pgsql') {
		$sql = "SELECT 1 FROM pg_tables WHERE tablename ='".TB_PREFIX."sql_patchmanager'";
	}
	$sth = $db->query($sql);
	$rows = $sth->fetchAll();
	$display_block .= <<<EOD
		<br />
		<b>Simple Invoices :: Database Upgrade Manager</b><br />
		<hr />
		<br />The database patches have now been applied. You can now start working with Simple Invoices.<br />
		<p align=middle><br /><a href="index.php"><font color="green">HOME</font></a></p>
<table align='center'>
EOD;

	if(count($rows) == 1) {


		if ($db_server == 'pgsql') {
			// Yay!  Transactional DDL
			$dbh->beginTransaction();
		}
		for($i=0;$i < count($patch);$i++) {
//			run_sql_patch($i,$patch[$i]); // use instead of following line if patch application status display is to be suppressed
			$display_block .= run_sql_patch($i,$patch[$i]);
		}
		if ($db_server == 'pgsql') {
			// Yay!  Transactional DDL
			$dbh->commit();
		}

		$display_block .= "\n</table>";

	//exit();
	$refresh = '<meta http-equiv="refresh" content="5;url=index.php">';

	} else {

		$display_block .= "\n<tr><td><br /><br />Step 1 - This is the first time Database Updates has been run<br /></td></tr>";
		
		initialise_sql_patch();
		
		$display_block .= "\n<tr><td><br />Now that the Database upgrade table has been initialised, please go back to the Database Upgrade Manger page by clicking <a href='index.php?module=options&amp;view=database_sqlpatches'>HERE</a> to run the remaining patches</td></tr>";
		$display_block .= "\n</table>";

	}
	
	global $smarty;
	$smarty-> assign("display_block",$display_block);
	$smarty-> assign("refresh",$refresh);

}

function donePatches() {
		$display_block = "<table align='center'>";
		$display_block .= <<<EOD
		<br />
		<b>Simple Invoices :: Database Upgrade Manager</b><br />
		<hr />
		<tr><td><br />The database patches are uptodate. You can continue working with Simple Invoices.<br /><p align=middle><br /><a href="index.php"><font color="green">HOME</font></a></p></tr>
		</table>

EOD;
	//exit();
	$refresh = '<meta http-equiv="refresh" content="2;url=index.php">';
	global $smarty;
	$smarty-> assign("display_block",$display_block);
	$smarty-> assign("refresh",$refresh);
}

function listPatches() {
		global $patch;

	//if(mysql_num_rows(mysqlQuery("SHOW TABLES LIKE '".TB_PREFIX."sql_patchmanager'")) == 1) {

		$display_block = <<<EOD
		<b>Simple Invoices :: Database Upgrade Manager</b><br /><br />
		
		Your version of Simple Invoices has been upgraded<br /><br />  
		With this new release there are database patches that need to be applied<br /><br />
		
		<hr />

		<table align="center">
			<tr>
				<td>
					<br />
						The list below describes which patches have and have not been applied to the database, the aim is to have them all applied.  If there are patches that have not been applied to the Simple Invoices database, please run the Update database by clicking update 
				</td>
			</tr>
		</table>
		
		
		<table class="buttons" align="center">
	<tr>
		
		<td>

            <a href="./index.php?case=run" class="positive">
                <img src="./images/common/tick.png" alt="" />
                Update
            </a>
    
        </td>
    </tr>
</table>
		
		
		<br />
		<img src="./images/common/important.png" alt="" /><font color="red">Warning: Please backup your database before upgrading!</font>
		<br />
		<br />
<table align="center">
EOD;


		for($p = 0; $p < count($patch);$p++) {
			$patch_name = htmlsafe($patch[$p]['name']);
			$patch_date = htmlsafe($patch[$p]['date']);
			if(check_sql_patch($p,$patch[$p]['name'])) {
				$display_block .= "\n<tr><td>SQL patch $p, $patch_name <i>has</i> already been applied in release $patch_date</td></tr>";
			}
			else {
				$display_block .= "\n<tr><td>SQL patch $p, $patch_name <b>has not</b> been applied to the database</td></tr>";
			}	
		}

		$display_block .= "\n</table>";
	global $smarty;
	$smarty-> assign("display_block",$display_block);
}



function check_sql_patch($check_sql_patch_ref, $check_sql_patch_field) {

    $db = new db();
   	$sql = "SELECT * FROM ".TB_PREFIX."sql_patchmanager WHERE sql_patch_ref = :patch" ;

	$sth = $db->query($sql, ':patch', $check_sql_patch_ref) or die(htmlsafe(end($dbh->errorInfo())));

	if(count($sth->fetchAll()) > 0) {
		return true;
	}
	
	return false;
}




function run_sql_patch($id, $patch) {
	global $dbh;
	global $db_server;
    $db = new db();
	$display_block = "";

	$sql = "SELECT * FROM ".TB_PREFIX."sql_patchmanager WHERE sql_patch_ref = :id" ;
	$sth = $db->query($sql, ':id', $id) or die(htmlsafe(end($dbh->errorInfo())));
	
	//echo $sql;
	$escaped_id = htmlsafe($id);
	$patch_name = htmlsafe($patch['name']);
	#forget about it!! the patch as its already been run
	if (count($sth->fetchAll()) != 0)  {

		$display_block .= "\n	<tr><td>Skipping SQL patch $escaped_id, $patch_name as it <i>has</i> already been applied</td></tr>";
	}
	else {
		
		//patch hasn't been run
		#so do the bloody patch
		$db->query($patch['patch']) or die(htmlsafe(end($dbh->errorInfo())));
		

		$display_block  = "\n	<tr><td>SQL patch $escaped_id, $patch_name <i>has</i> been applied to the database</td></tr>";

		# now update the ".TB_PREFIX."sql_patchmanager table
		
		
		$sql_update = "INSERT INTO ".TB_PREFIX."sql_patchmanager ( sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES (:id, :name, :date, :patch)";
		
		/*echo $sql_update;*/

		$db->query($sql_update, ':id', $id, ':name', $patch['name'], ':date', $patch['date'], ':patch', $patch['patch']) or die(htmlsafe(end($dbh->errorInfo())));

		if($id == 126) {
			patch126();
		} 
		
		/*
		 * cusom_fields to new customFields patch - commented out till future
		 */
			/*
		 	elseif($id == 137) {
				convertInitCustomFields();
			}
			*/
		
		$display_block .= "\n	<tr><td>SQL patch $escaped_id, $patch_name <b>has</b> been applied</td></tr>";
	}
	return $display_block;
}


function initialise_sql_patch() {
	//SC: MySQL-only function, not porting to PostgreSQL
	global $dbh;
    $db = new db();

	#check sql patch 1
	$sql_patch_init = "CREATE TABLE ".TB_PREFIX."sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 255 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) TYPE = MYISAM ";
	dbQuery($sql_patch_init) or die(end($dbh->errorInfo()));

	$display_block = "<tr><td>Step 2 - The SQL patch table has been created<br /></td></tr>";

	echo $display_block;

	$sql_insert = "INSERT INTO ".TB_PREFIX."sql_patchmanager
 ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement )
VALUES ('','1','Create ".TB_PREFIX."sql_patchmanger table','20060514', :patch)";
	$db->query($sql_insert, ':patch', $sql_patch_init) or die(end($dbh->errorInfo()));

	$display_block2 = "<tr><td>Step 3 - The SQL patch has been inserted into the SQL patch table<br /></td></tr>";
	
	echo $display_block2;
}

function patch126() {
	//SC: MySQL-only function, not porting to PostgreSQL
    $db = new db();
	$sql = "SELECT * FROM ".TB_PREFIX."invoice_items WHERE product_id = 0";
	$sth = $db->query($sql);
	
	while($res = $sth->fetch()) {
		$sql = "INSERT INTO ".TB_PREFIX."products (id, description, unit_price, enabled, visible) 
			VALUES (NULL, :description, :gross_total, '0',  '0')";
		$db->query($sql, ':description', $res[description], ':total', $res[gross_total]);
		$id = lastInsertId();

		$sql = "UPDATE  ".TB_PREFIX."invoice_items SET product_id = :id, unit_price = :price WHERE ".TB_PREFIX."invoice_items.id = :item";

		$db->query($sql,
			':id', $id[0],
			':price', $res[gross_total],
			':item', $res[id]
			);
	}
}



function convertInitCustomFields() {
// This function is exactly the same as convertCustomFields() in ./include/customFieldConversion.php but without the print_r and echo output while storing
	/* check if any value set -> keeps all data for sure */
	global $dbh;
    $db = new db();
	$sql = "SELECT * FROM ".TB_PREFIX."custom_fields";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	
	while($custom = $sth->fetch()) {
		if(preg_match("/(.+)_cf([1-4])/",$custom['cf_custom_field'],$match)) {
			//print_r($match);
			
			switch($match[1]) {
				case "biller": $cat = 1;	break;
				case "customer": $cat = 2;	break;
				case "product": $cat = 3;	break;
				case "invoice": $cat = 4;	break;
				default: $case = 0;
			}
			
			$cf_field = "custom_field".$match[2];
			if($match[1] != "biller") {
				$sql = "SELECT id, :field FROM :table";
				$tablename = TB_PREFIX.$match[1]."s";
			}
			else {
				$sql = "SELECT id, :field FROM :table";
				$tablename = TB_PREFIX.$match[1];
			}
			
			
			$store = false;

			/*
			 * If custom field name is set
			 */
			if($custom['cf_custom_label'] != NULL) {
				$store = true;
			}
			
			
			//error_log($sql);
			$tth = $dbh->prepare($sql);
			$tth->bindValue(':table', $tablename);
			$tth->bindValue(':field', $cf_field);
			$tth->execute();

			/*
			 * If any field is set, create custom field
			 */
			while($res = $tth->fetch()) {
				if($res[1] != NULL) {
					$store = true;
					break;
				}
				//echo($res[0]."<br />");
			}
			
			if($store) {
//				print_r($res);
//				echo "<br />".$sql."   ".$res['id'];
				
				//create new text custom field
				saveInitCustomField(3,$cat,$custom['cf_custom_field'],$custom['cf_custom_label']);
				$id = lastInsertId();
				error_log($id);
				
				$plugin = getPluginById(3);
				$plugin->setFieldId($id);
				
				//insert all data
				$uth = $dbh->prepare($sql);
				$uth->bindValue(':table', $tablename);
				$uth->bindValue(':field', $cf_field);
				$uth->execute();
				while($res2 = $uth->fetch()) {
					$plugin->saveInput($res2[$cf_field], $res2['id']);
				}
				
			}
		}
	}
}

function saveInitCustomField($id, $category, $name, $description) {
// This function is exactly same as saveCustomField() in ./include/manageCustomFields.php but without the final echo output
    $db = new db();
	$sql = "INSERT INTO ".TB_PREFIX."customFields  (pluginId, categorieId, name, description) 
		VALUES (:id, :category, :name, :description)";
	$db->query($sql, ':id', $id, ':category', $category, ':name', $name, ':description', $description);
//	echo "SAVED<br />";
}
?>
