<?php
/*
* Script: save.php
* 	Custom fields save page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();



# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#edit custom field

if (  $op === 'edit_custom_field' ) {

	if (isset($_POST['save_custom_field'])) {
		$sql = "UPDATE
                                ".TB_PREFIX."custom_fields
                        SET
                                cf_custom_label = :label
                        WHERE
                                cf_id = :id";

		if (dbQuery($sql, ':id', $_GET['id'], ':label', $_POST['cf_custom_label'])) {
			$display_block =  $LANG['save_custom_field_success'];
		} else {
			$display_block =  $LANG['save_custom_field_success'];
			global $dbh;
			$display_block .=  end($dbh->errorInfo());
		}

		//header( 'refresh: 2; url=manage_custom_fields.php' );
		$refresh_total = "<meta http-equiv='refresh' content='2;url=index.php?module=custom_fields&amp;view=manage' />";
	}

	else if (isset($_POST['cancel'])) {

		//header( 'refresh: 0; url=manage_custom_fields.php' );
		$refresh_total = "<meta http-equiv='refresh' content='0;url=index.php?module=custom_fields&amp;view=manage' />";
	}


}

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';

$smarty -> assign('display_block',$display_block); 
$smarty -> assign('refresh_total',$refresh_total); 

$smarty -> assign('pageActive', 'custom_field');
$smarty -> assign('active_tab', '#setting');
?>
