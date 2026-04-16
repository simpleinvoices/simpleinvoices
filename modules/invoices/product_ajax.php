<?php

$row_id = htmlsafe($_GET['row']);
if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)
{
	//sleep(2);
	$sql = "SELECT unit_price, default_tax_id, default_tax_id_2,attribute,notes,notes_as_description,show_description FROM ".TB_PREFIX."products WHERE id = :id AND domain_id = :domain_id LIMIT 1";
	$states = dbQuery($sql, ':id', (int)$_GET['id'], ':domain_id', $auth_session->domain_id);
    //	$output = '';


	$row = $states->fetch();
	if($row !== false)
	{
        $html = '';
        $json_att = json_decode($row['attribute']);
        if($json_att !== null AND $row['attribute'] !== '[]')
        {
        $html = "<div id='json_html". $row_id ."' class='si-attr-row d-flex flex-wrap gap-2 align-items-end mt-1 mb-1'>";
        foreach($json_att as $k=>$v)
        {
            if($v == 'true')
            {
                $attr_id = (int)$k;
                $attr_name_sql = "SELECT a.name as name, a.enabled as enabled, t.name type
                    FROM ".TB_PREFIX."products_attributes as a,
                         ".TB_PREFIX."products_attribute_type as t
                   WHERE a.type_id = t.id
                     AND a.id = :attr_id
                     AND a.domain_id = :domain_id";
                $attr_name = dbQuery($attr_name_sql, ':attr_id', $attr_id, ':domain_id', $auth_session->domain_id);
                $attr_name = $attr_name->fetch();

                $sql2 = "SELECT a.name as name, v.id as id, v.value as value, v.enabled as enabled
                         FROM ".TB_PREFIX."products_attributes a
                             JOIN ".TB_PREFIX."products_values v
                                 ON (v.attribute_id = a.id AND v.domain_id = a.domain_id)
                         WHERE a.id = :attr_id
                           AND a.domain_id = :domain_id";
                $states2 = dbQuery($sql2, ':attr_id', $attr_id, ':domain_id', $auth_session->domain_id);

                if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'list')
                {
                    $html .= "<div><label class='form-label small mb-1'>".htmlspecialchars($attr_name['name'])."</label><select name='attribute[".$row_id."][".$k."]' class='form-select form-select-sm'>";
                    foreach($states2 as $att_key=>$att_val)
                    {
                        if($att_val['enabled'] == '1')
                        {
                            $html .= "<option value='". $att_val['id']. "'>".htmlspecialchars($att_val['value'])."</option>";
                        }
                    }
                    $html .= "</select></div>";
                }
                if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'free'  )
                {
                    $html .= "<div><label class='form-label small mb-1'>".htmlspecialchars($attr_name['name'])."</label><input class='form-control form-control-sm' name='attribute[".$row_id."][".$k."]' /></div>";
                }
                if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'decimal' )
                {
                    $html .= "<div><label class='form-label small mb-1'>".htmlspecialchars($attr_name['name'])."</label><input class='form-control form-control-sm' style='width:6rem' name='attribute[".$row_id."][".$k."]' /></div>";
                }
            }
        }
        $html .= "</div>";
        }
	//	print_r($row);
	//		$output .= '<input id="state" class="field select two-third addr" value="'.$row['unit_price'].'"/>';
			/*Format with decimal places with precision as defined in config.php*/
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
