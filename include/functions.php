<?php
include('./config/config.php');
ob_start();
include("./lang/$language.inc.php");
ob_end_clean();

$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );


function get_custom_field_label($field)
        {
	
	include("./config/config.php");
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();

	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );

        $sql =  "select cf_custom_label from si_custom_fields where cf_custom_field = '$field'";
        $result = mysql_query($sql,$conn) or die(mysql_error());

        while ($Array = mysql_fetch_array($result)) {
                $custom_field_label = $Array['cf_custom_label'];
                $cf_display = $Array['cf_display'];
        };

        //grab the last character of the field variable
        $get_cf_number = $field[strlen($field)-1];    

        //if custom field is blank in db use the one from the LANG files
        if ($custom_field_label == null) {
                $custom_field_label = ${"LANG_custom_field" . $get_cf_number};
        }
 	return $custom_field_label;
}

function get_custom_field_name($field)
        {

        include('./config/config.php');
        ob_start();
        include("./lang/$language.inc.php");
        ob_end_clean();
        
	//grab the last character of the field variable
        $get_cf_letter = $field[0];
        //grab the last character of the field variable
        $get_cf_number = $field[strlen($field)-1];
	
	if ($get_cf_letter == "b") {
		$custom_field_name = $LANG_biller;
	}
	if ($get_cf_letter == "c") {
		$custom_field_name = $LANG_customer;
	}
	if ($get_cf_letter == "p") {
		$custom_field_name = $LANG_product;
	}
	
	$custom_field_name .= " :: " . $LANG_custom_field . " $get_cf_number" ;
        return $custom_field_name;
}
?>
