<?php
/*
include('./config/config.php');
ob_start();
include("./lang/$language.inc.php");
ob_end_clean();

$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );
*/

function get_custom_field_label($field,$dir)         {
	
	//this .. $dir stuff is a total hack - needs to be fixed!!!!!!!!!
	if ($dir == '..') {
		ob_start();
		include('../config/config.php');
		include("../lang/$language.inc.php");
		ob_end_clean();
	}
	else {
		include('./config/config.php');
		ob_start();
		include("./lang/$language.inc.php");
		ob_end_clean();
	};

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

        $conn = mysql_connect( $db_host, $db_user, $db_password );
        mysql_select_db( $db_name, $conn );
        

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

function do_tr($number) {
	if ($number == 2 ) {
		$new_tr = "</tr><tr>";
		return $new_tr;
	}
	
        if ($number == 4 ) {
                $new_tr = "</tr><tr>";
                return $new_tr;
        }

	
}

function merge_address($field1,$field2,$field3,$street1,$street2) {
        ob_start();
        include('../config/config.php');
        include("../lang/$language.inc.php");
        ob_end_clean();

        if (($field1 != null OR $field2 != null OR $field3 != null) AND ($street1 ==null AND $street2 ==null)) {
                $ma .=  "<tr><td>$LANG_address:</td><td colspan=3>";
		$skip_section = 1;
        }
        if (($field1 != null OR $field2 != null OR $field3 != null) AND( $skip_section != 1)) {
                $ma .=  "<tr><td></td><td colspan=3>";
        }
        if ($field1 != null) {
                $ma .=  "$field1";
        }

        if ($field1 != null AND $field2 != null  ) {
                $ma .=  ", ";
        }

        if ($field2 != null) {
                $ma .=  "$field2";
        }

        if (($field1 != null OR $field2 != null) AND ($field3 != null)) {
                $ma .=  ", ";
        }

        if ($field3 != null) {
                $ma .=  "$field3";
        }
	return $ma;
}

function print_if_not_null($label,$field) {
        if ($field != null) {
                $print_if_not_null =  "
                <tr>
                        <td>$label:<td colspan=5>$field</td>
                </tr>";  
		return $print_if_not_null;
        }
}

function inv_itemised_cf($label,$field) {
        if ($field != null) {
                $print_cf =  "<td width=50%>$label: $field</td>";  
                return $print_cf;
        }
}


?>
