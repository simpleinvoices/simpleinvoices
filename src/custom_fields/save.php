<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


include('./config/config.php');


# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#edit custom field

if (  $op === 'edit_custom_field' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

        if (isset($_POST['save_custom_field'])) {
                $sql = "UPDATE
                                {$tb_prefix}custom_fields
                        SET
                                cf_custom_label = '$_POST[cf_custom_label]'
                        WHERE
                                cf_id = $_GET[submit]";

                if (mysql_query($sql, $conn)) {
                        $display_block =  $LANG_save_custom_field_success;
                } else {
                        $display_block =  $LANG_save_custom_field_success;
			$display_block .=  mysql_error();
                }

                //header( 'refresh: 2; url=manage_custom_fields.php' ); 
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=custom_fields&view=manage>";
                }

        else if (isset($_POST['cancel'])) {

                //header( 'refresh: 0; url=manage_custom_fields.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=custom_fields&view=manage>";
        }


}



include('./include/include_main.php');

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
$display_block_items = isset($display_block_items) ? $display_block_items : '&nbsp;';
echo <<<EOD
{$refresh_total}


<br>
<br>
{$display_block}
<br><br>
{$display_block_items}

EOD;
?>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
