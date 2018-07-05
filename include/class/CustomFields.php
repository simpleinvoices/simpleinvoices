<?php
class CustomFields {

    /**
     * Function: get_custom_field_label
     * Prints name of custom field based on input. If custom field has not been defined
     * by the user than use the default in the lang files
     * @param string $field The custom field in question
     */
    public static function get_custom_field_label($field, $domain_id = '') {
        global $LANG;
        $domain_id = domain_id::get($domain_id);

        $sql = "SELECT cf_custom_label FROM " . TB_PREFIX . "custom_fields
            WHERE cf_custom_field = :field AND domain_id = :domain_id";
        $sth = dbQuery($sql, ':field', $field, ':domain_id', $domain_id);

        $cf = $sth->fetch();

        // grab the last character of the field variable
        $get_cf_number = $field[strlen($field) - 1];

        // if custom field is blank in db use the one from the LANG files
        if ($cf['cf_custom_label'] == NULL) {
            $cf['cf_custom_label'] = $LANG['custom_field'] . $get_cf_number;
        }

        return $cf['cf_custom_label'];
    }

    /**
     * Build screen values for displaying a custom field.
     * @param string $custom_field Name of the database field.
     * @param string $custom_field_value The value of this field.
     * @param string $permission Maintenance permission (read or write)
     * @param string $css_class_tr CSS class the the table row (tr)
     * @param string $css_class_th CSS class of the table heading (th)
     * @param string $css_class_td CSS class of the table detail (td)
     * @param string $td_col_span COLSPAN value to table detail row.
     * @param string $seperator Value to display between two values.
     * @return string Display/input string for a custom field. For "read" permission, the field to
     *         display the data. For "write" permission, the formatted label and field.
     */
    // @formatter:off
    public static function show_custom_field($custom_field, $custom_field_value, $permission,
                                             $css_class_tr, $css_class_th      , $css_class_td,
                                             $td_col_span , $seperator) {
        // @formatter:on
        global $help_image_path;

        $domain_id = domain_id::get();

        $write_mode = ($permission == 'write'); // if falst then in read mode.

        // Get the custom field number (last character of the name).
        $cfn = substr($custom_field, -1, 1);

        // Get custom field label
        // @formatter:off
        $get_custom_label = "SELECT cf_custom_label
                 FROM " . TB_PREFIX . "custom_fields
                 WHERE cf_custom_field = :field AND domain_id = :domain_id";
        // @formatter:on
        $sth = dbQuery($get_custom_label, ':field', $custom_field, ':domain_id', $domain_id);

        $cf_label = '';
        $row = $sth->fetch();
        if (!empty($row['cf_custom_label'])) $cf_label = $row['cf_custom_label'];

        $display_block = "";
        if (!empty($custom_field_value) || ($write_mode && !empty($cf_label))) {
            $custom_label_value = htmlsafe(self::get_custom_field_label($custom_field));
            // @formatter:off
            if ($write_mode) {
                $display_block = "<tr>\n" .
                        "  <th class='$css_class_th'>$custom_label_value\n" .
                        "    <a class='cluetip' href='#'\n" .
                        "       rel='index.php?module=documentation&amp;view=view&amp;page=help_custom_fields'\n" .
                        "       title='Custom Fields'>\n" .
                        "      <img src='{$help_image_path}help-small.png' alt='' />\n" .
                        "    </a>\n" .
                        "  </th>\n" .
                        "  <td>\n" .
                        "    <input type='text' name='custom_field$cfn' value='$custom_field_value' size='25' />\n" .
                        "  </td>\n" .
                        "</tr>\n";
            } else {
                $display_block = "<tr class='$css_class_tr'>\n" .
                "  <th class='$css_class_th'>$custom_label_value$seperator</th>\n" .
                "  <td class='$css_class_td' colspan='$td_col_span'>$custom_field_value</td>\n" .
                "</tr>\n";
            }
            // @formatter:on
        }
        return $display_block;
    }

}