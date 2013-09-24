<?php

$row_id = $_GET['row'];
if($_GET['id'])
{
	//sleep(2);
	$sql = sprintf("SELECT unit_price, default_tax_id, default_tax_id_2,attribute,notes,notes_as_description,show_description FROM ".TB_PREFIX."products WHERE id = %d LIMIT 1", $_GET['id']);
	$states = dbQuery($sql);
    //	$output = '';


	if($states->rowCount() > 0)
	{	
		$row = $states->fetch();

        $json_att = json_decode($row['attribute']);
        if($json_att !== null)
        {
        $html ="<tr id='json_html". $row_id ."'><td></td><td colspan='5'><table><tr>";
        foreach($json_att as $k=>$v)
        {
            if($v == 'true')
            {
                $attr_name_sql = sprintf('select 
                    a.name as name, a.enabled as enabled,  t.name type 
                    from 
                        si_products_attributes as a, 
                        si_products_attribute_type as t 
                   where 
                        a.type_id = t.id
                        AND a.id = %d', $k);
                $attr_name = dbQuery($attr_name_sql);
                $attr_name = $attr_name->fetch();

                $sql2 = sprintf("select a.name as name, v.id as id, v.value as value, v.enabled as enabled from ".TB_PREFIX."products_attributes a, ".TB_PREFIX."products_values v where a.id = v.attribute_id AND a.id = %d", $k);
                $states2 = dbQuery($sql2);

                if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'list')
                {
                    $html .= "<td>".$attr_name['name']."<select name='attribute[".$row_id."][".$k."]'>";
                    foreach($states2 as $att_key=>$att_val)
                    {
                        if($att_val['enabled'] == '1')
                        {
                            $html .= "<option value='". $att_val['id']. "'>".$att_val['value']."</option>";
                        }
                    }
                    $html .= "</select></td>";
                }
                if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'free'  )
                {
                    $html .= "<td>".$attr_name['name']."<input name='attribute[".$row_id."][".$k."]' /></td>";
                }
                if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'decimal' )
                {
                    $html .= "<td>".$attr_name['name']."<input name='attribute[".$row_id."][".$k."]' size='5'/></td>";
                }
            }
        }
                    $html .= "</tr></table></td></tr>";
        }
	//	print_r($row);
	//		$output .= '<input id="state" class="field select two-third addr" value="'.$row['unit_price'].'"/>';
			/*Format with decimal places with precision as defined in config.ini*/
			$output['unit_price'] = siLocal::number_clean($row['unit_price']);
			$output['default_tax_id'] = $row['default_tax_id'];
			$output['default_tax_id_2'] = $row['default_tax_id_2'];
			$output['attribute'] = $row['attribute'];
			$output['json_attribute'] = $json_att;
			$output['json_html'] = $html;
			$output['notes'] = $row['notes'];
			$output['notes_as_description'] = $row['notes_as_description'];
			$output['show_description'] = $row['show_description'];
	//		$output .= $_POST['id'];
		
	}else {
		$output .= '';
	}

	echo json_encode($output);
	
	exit();
} else {

echo "";
}

// Perform teh Queries!
//$sql = 'SELECT * FROM si_products';
//$country = mysqlQuery($sql) or die('Query Failed:' . mysql_error());


?>
