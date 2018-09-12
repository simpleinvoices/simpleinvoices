<?php
global $pdoDb, $auth_session;

$row_id = htmlsafe($_GET['row']);
$id = $_GET['id'];
if (!empty($id)) {
    $output = array();
    $pdoDb->addSimpleWhere("id", $id, "AND");
    $pdoDb->addSimpleWhere("domain_id", $auth_session->domain_id);
    $pdoDb->setLimit(1);
    $rows = $pdoDb->request("SELECT", "products");
    if (!empty($rows)) {
        $row = $rows[0];
        $attr = (empty($row['attribute']) ? "[]" : $row['attribute']);
        $html = "";
        $json_att = json_decode($attr);
        if($json_att !== null && $row['attribute'] !== '[]') {
            $html .= "<tr id='json_html$row_id'>";
            $html .= "  <td></td>";
            $html .= "  <td colspan='5'>";
            $html .= "    <table>";
            $html .= "      <tr>";
            foreach ($json_att as $k => $v) {
                if ($v == 'true') {
                    $join = new Join("INNER", "products_attribute_type", "t");
                    $join->addSimpleItem("a.type_id", "t.id");
                    $pdoDb->addToJoins($join);
                    $pdoDb->addSimpleWhere("id", $k);
                    $pdoDb->setSelectList(array("name", "enabled", "t.name AS type"));
                    $rows = $pdoDb->request("SELECT", "products_attributes", "a");
                    $attr_name = $rows[0];

                    $join = new Join("INNER", "products_values", "v");
                    $join->addSimpleItem("a.id", "v.attribute_id");
                    $pdoDb->addToJoins($join);
                    $pdoDb->addSimpleWhere("a.id", $k);
                    $pdoDb->setSelectList(array("a.name AS name", "v.id AS id", "v.value AS value", "v.enabled AS enabled"));
                    $rows = $pdoDb->request("SELECT", "products_attributes", "a");
                    if ($attr_name['enabled'] == '1') {
                        if ($attr_name['type'] == 'list') {
                            $html .= "        <td>" . $attr_name['name'];
                            $html .= "           <select name='attribute[$row_id][$k]'>";
                            foreach ($rows as $att_val) {
                                if ($att_val['enabled'] == '1') {
                                    $html .= "             <option value='" . $att_val['id'] . "'>";
                                    $html .=               $att_val['value'];
                                    $html .= "             </option>";
                                }
                            }
                            $html .= "           </select>";
                            $html .= "         </td>";
                        } else if ($attr_name['type'] == 'free') {
                            $html .= "        <td>" . $attr_name['name'];
                            $html .= "          <input name='attribute[$row_id][$k]' />";
                            $html .= "        </td>";
                        } else if ($attr_name['type'] == 'decimal') {
                            $html .= "        <td>" . $attr_name['name'];
                            $html .= "          <input name='attribute[$row_id][$k]' size='5'/>";
                            $html .= "        </td>";
                        }
                    }
                }
            }
            $html .= "      </tr>";
            $html .= "    </table>";
            $html .= "  </td>";
            $html .= "</tr>";
        }

        // Format with decimal places with precision as defined in config.php
        // @formatter:off
        $output['unit_price']           = siLocal::number_trim($row['unit_price']);
        $output['default_tax_id']       = (isset($row['default_tax_id']) ? $row['default_tax_id'] : "");
        $output['default_tax_id_2']     = (isset($row['default_tax_id_2']) ? $row['default_tax_id_2'] : "");
        $output['attribute']            = (isset($row['attribute']) ? $row['default_tax_id_2'] : "");
        $output['json_attribute']       = $json_att;
        $output['json_html']            = $html;
        $output['notes']                = (isset($row['notes']) ? $row['notes'] : "");
        $output['notes_as_description'] = (isset($row['notes_as_description']) ? $row['notes_as_description'] : "");
        $output['show_description']     = (isset($row['show_description']) ? $row['show_description'] : "");
        // @formatter:on
    } else {
        $output .= '';
    }

    echo json_encode($output);
    exit();
}
echo "";
