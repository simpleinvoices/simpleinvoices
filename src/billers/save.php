<?php

include('./include/include_main.php'); 

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert biller
 	
if ( $op === 'insert_biller') {
	
	if(insertBiller()) {
 		$display_block = $LANG_save_biller_success;
 	} else {
 		$display_block = $LANG_save_biller_failure;
 	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=billers&view=manage>";
}

#edit biller

else if ($op === 'edit_biller' ) {

	if (isset($_POST['save_biller'])) {
		if (updateBiller()) {
			$display_block = $LANG_save_biller_success;
		} else {
			$display_block = $LANG_save_biller_failure;
		}
	}
	else if (isset($_POST['cancel'])) {
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=billers&view=manage>";
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=billers&view=manage>";
}

include('./include/include_main.php');

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
$display_block_items = isset($display_block_items) ? $display_block_items : '&nbsp;';


?>