<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

include('./include/sql_patches.php');


//        $sql_patch_update_33 = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('',33,'$sql_patch_name_33',20061214,'')";

#Max patches applied - start
$check_patches_sql = "SELECT count(sql_patch_ref) as count FROM {$tb_prefix}sql_patchmanager ";

	$patches_result = mysql_query($check_patches_sql, $conn) or die(mysql_error());
		
	$patches = mysql_fetch_array($patches_result);
	$patch_count = count($patch);
	if ($patches['count'] < $patch_count ) {
		$patches_to_be_applied = $patch_count - $patches['count'];
		$display_note = "<br>
			<b>Note:</b>You have $patches_to_be_applied patches to be applied
		";	
	}


if ($_GET['op'] == "run_updates") {
	$table = "{$tb_prefix}sql_patchmanager";
#DEFINE SQL PATCH
	
	if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '$table'"))==1) {



		echo "
		<table align='center'>
		";
/*
//MAKE THIS CODE WORK!!!	
                $r = 1;
		//get count of sql patches and run the check_sql_patch for each patch
                while  ($r <= $patch_count) {
                        run_sql_patch($r,"$sql_patch_name_$r","$sql_patch_$r","$sql_patch_update_$r");
                        $r++;
                }
*/
for($i=1;$i <= count($patch);$i++) {
	run_sql_patch($i,$patch[$i]['name'],$patch[$i]['patch'],$patch[$i]['date']);
}


		echo "<tr><td><br>The database patches have now been applied, please go back to the <a href='index.php?module=options&view=database_sqlpatches'>Database Upgrade Manager page</a> to see what patches have been applied. If all patches have been applied then there is now further action required</td></tr>";
		echo "
		</table>
";


	} else {


		echo "
		<table align='center'>
		";
	echo "<br><br><tr><td>Step 1 - This is the first time Database Updates has been run</td></tr><br>";
		initialise_sql_patch();
		
		echo "<tr><td><br>Now that the Database upgrade table has been initialised, please go back to the Database Upgrade Manger page by clicking <a href='index.php?module=options&view=database_sqlpatches'>HERE</a> to run the remaining patches</td></tr>";
		echo "
		</table>
		</div>
";

	}
}
	


else {
	#$tables = mysql_list_tables($dbname);
	$table = "{$tb_prefix}sql_patchmanager";

	if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '$table'"))==1) {


		echo <<<EOD
		<b>Database Upgrade Manager</b>
		$display_note
		<hr></hr>

		<table align="center">
			<tr></i><tr><td><br>The list below describes which patches have and have not been applied to the database, the aim is to have them all applied.  If there are patches that have not been applied to the Simple Invoices database, please run the Update database by clicking update </td></tr><tr align=center><td><p class='align_center'><br><a href='index.php?module=options&view=database_sqlpatches&op=run_updates'>UPDATE</a></p></td></tr></table><br>
<a href="docs.php?t=help&p=info_pages/text" rel="gb_page_center[450, 450]"><font color="red"><img src="./images/common/important.png"></img>Warning:</font></a>
<table align="center">
EOD;

		$p = 1;
                while  ($p <= $patch_count) {
			check_sql_patch($p,$patch[$p]['name']);
                        $p++;
                }

/*
	check_sql_patch(1,$sql_patch_name_1);
	check_sql_patch(2,$sql_patch_name_2);
	....
*/
		echo "</table>";

	}
	else {
		echo <<<EOD

		<table align='center'>
          <tr><td><br>This is the first time that the Database Upgrade process is to be run.  The first step in the process is to Initialse the database upgrade table. To do this click the Initialise database button<br><br><a href='index.php?module=options&view=database_sqlpatches&op=run_updates'>INITIALISE DATABASE UPGRADE</a></td></tr>
		</table>
EOD;
	}

}


include('./config/config.php'); 


function check_sql_patch($check_sql_patch_ref, $check_sql_patch_field) {
        #product query
        include('./config/config.php');
        $conn = mysql_connect("$db_host","$db_user","$db_password");
        mysql_select_db("$db_name",$conn);


	#check sql patch 1
	$sql = "select * from {$tb_prefix}sql_patchmanager where sql_patch_ref = $check_sql_patch_ref" ;

	$result = mysql_query($sql, $conn) or die(mysql_error());
	$number_of_rows = mysql_num_rows($result);

	$patch = mysql_fetch_array($result);

	if (!empty($patch['sql_id']))  {

	$display_block = "
		<tr><td>SQL patch $patch[sql_patch_ref], $patch[sql_patch] <i>has</i> already been applied in release $patch[sql_release]</td></tr>
";
	}

	else if (empty($patch['sql_id']))  {
		$display_block = "

		<tr><td>SQL patch $check_sql_patch_ref, $check_sql_patch_field  <b>has not</b> been applied to the database</td></tr>


	";
	}

	echo $display_block;
}




function run_sql_patch($sql_patch_ref, $sql_patch_name, $sql_patch, $date) {

        include('./config/config.php');
        $conn = mysql_connect("$db_host","$db_user","$db_password");
        mysql_select_db("$db_name",$conn);

	$sql_update = "INSERT INTO si_sql_patchmanager ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement ) VALUES ('', $sql_patch_ref,'$sql_patch_name',$date,'')";
	
	#check sql patch 1
	$sql_run = "select * from {$tb_prefix}sql_patchmanager where sql_patch_ref = $sql_patch_ref" ;

	$result_run = mysql_query($sql_run, $conn) or die(mysql_error());
	$number_of_rows_run = mysql_num_rows($result_run);
	$patch = mysql_fetch_array($result_run);
	
	#forget about it!! the patch as its already been run
        if (!empty($patch['sql_id']))  {
	
	$display_block = "
		</div id='header'>
		<tr><td>Skipping SQL patch $sql_patch_ref, $sql_patch_name as it <i>has</i> already been applied</td></tr>";
	};

	#patch hasnt been run before so run it - this is ganna be trouble :)
	if (empty($patch['sql_id']))  {
		
		#so do the bloody patch
                mysql_query($sql_patch, $conn) or die(mysql_error());


                $display_block  = "

                <tr><td>SQL patch $sql_patch_ref, $sql_patch_name <i>has</i> been applied to the database</td></tr>
                ";
		# now update the {$tb_prefix}sql_patchmanager table
                mysql_query($sql_update, $conn) or die(mysql_error());


                $display_block = "

                <tr><td>SQL patch $sql_patch_ref, $sql_patch_name <b>has</b> been applied</td></tr>



	";
	};


	echo $display_block;
}




function initialise_sql_patch() {
        #product query
        include('./config/config.php');
        $conn = mysql_connect("$db_host","$db_user","$db_password");
        mysql_select_db("$db_name",$conn);



	#check sql patch 1
	$sql_patch_init = "CREATE TABLE {$tb_prefix}sql_patchmanager (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,sql_patch_ref VARCHAR( 50 ) NOT NULL ,sql_patch VARCHAR( 50 ) NOT NULL ,sql_release VARCHAR( 25 ) NOT NULL ,sql_statement TEXT NOT NULL) TYPE = MYISAM ";
	mysql_query($sql_patch_init, $conn) or die(mysql_error());

	$display_block = "
		<tr>
		<td>Step 2 - The SQL patch table has been created<br></td></tr>";

	echo $display_block;

	$sql_insert = "INSERT INTO {$tb_prefix}sql_patchmanager
 ( sql_id  ,sql_patch_ref , sql_patch , sql_release , sql_statement )
VALUES ('','1','Create {$tb_prefix}sql_patchmanger table','20060514','$sql_patch_init')";
	mysql_query($sql_insert, $conn) or die(mysql_error());

	$display_block2 = "
		<tr><td>Step 3 - The SQL patch has been inserted into the SQL patch table<br></td></tr>";


	echo $display_block2;


}


?>
