<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert tax rate

if (  $op === 'insert_tax_rate' ) {


/*Raymond - what about the '', bit doesnt seem to do an insert in me environment when i exclude it
$sql = "INSERT INTO ".TB_PREFIX."tax VALUES ('$_POST[tax_description]','$_POST[tax_percentage]')";
*/

	$sql = "INSERT into
			".TB_PREFIX."tax
			(
				tax_id, tax_description,
				tax_percentage, tax_enabled,
			)
		VALUES
			(	
				NULL, :description,
				:percent, :enabled
			)";
	if ($db_server == 'pgsql') {
		$sql = "INSERT into ".TB_PREFIX."tax
			(tax_description, tax_percentage, tax_enabled)
		VALUES
			(:description, :percent, :enabled)";
	}
	
	if (dbQuery($sql,
	  ':description', $_POST['tax_description'],
	  ':percent', $_POST['tax_percentage'],
	  ':enabled', $_POST['tax_enabled'])) {
		$display_block = $LANG['save_tax_rate_success'];
	} else {
		$display_block = $LANG['save_tax_rate_failure'];
	}

	//header( 'refresh: 2; url=manage_tax_rates.php' );
	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=tax_rates&view=manage>";
}



#edit tax rate

else if (  $op === 'edit_tax_rate' ) {

	if (isset($_POST['save_tax_rate'])) {
		$sql = "UPDATE
				".TB_PREFIX."tax
			SET
				tax_description = :description,
				tax_percentage = :percentage,
				tax_enabled = :enabled
			WHERE
				tax_id = :id";

		if (dbQuery($sql, 
		  ':description', $_POST['tax_description'],
	  	  ':percentage', $_POST['tax_percentage'],
	  	  ':enabled', $_POST['tax_enabled'],
	  	  ':id', $_GET['submit'])) {
			$display_block = $LANG['save_tax_rate_success'];
		} else {
			$display_block = $LANG['save_tax_rate_failure'];
		}

		//header( 'refresh: 2; url=manage_tax_rates.php' );
		$refresh_total = "<META HTTP-EQUIV='REFRESH' CONTENT=2;URL=index.php?module=tax_rates&view=manage>";

		}

	else if (isset($_POST['cancel'])) {

		//header( 'refresh: 0; url=manage_tax_rates.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=tax_rates&view=manage>";
	}
}

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$smarty -> assign('display_block',$display_block); 
$smarty -> assign('refresh_total',$refresh_total); 
?>
