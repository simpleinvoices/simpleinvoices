<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

/* validataion code */
include("./include/validation.php");

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("b_name",$LANG_biller_name);
jsFormValidationEnd();
jsEnd();

/* end validataion code */

/*drop down list code for invoice logo */


$dirname="images/logo";
   $ext = array("jpg", "png", "jpeg", "gif");
   $files = array();
   if($handle = opendir($dirname)) {
       while(false !== ($file = readdir($handle)))
           for($i=0;$i<sizeof($ext);$i++)
               if(stristr($file, ".".$ext[$i])) //NOT case sensitive: OK with JpeG, JPG, ecc.
                   $files[] = $file;
       closedir($handle);
   }

sort($files);


//TODO: not complet template
$display_block_logo_list = <<<EOD
<select name="b_co_logo">
<option selected value="_default_blank_logo.png" style="font-weight: bold">_default_blank_logo.png</option>
EOD;

foreach ($files as $var)
{
	$display_block_logo_list .= "<option>$var</option>";
}
$display_block_logo_list .= "</select>";

/*end logo stuff */

#do the product enabled/disblaed drop down
$display_block_enabled = <<<EOD
<select name="b_enabled">
<option value="1" selected>$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;


#get custom field labels
$customFieldLabel = getCustomFieldLabels("biller");


include("./src/billers/add.tpl");
echo $block;

?>
