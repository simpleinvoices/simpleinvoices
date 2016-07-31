<?php
/**
 * Get the custom flag labels
 * @param string $domain_id Domain to obtain records for. If not set, the domain for
 *        this session will be used.
 */
function getCustomFlagLabels($customFlagsEnabled, $domain_id = '') {
    $custom_flag_labels = array('','','','','','','','','','');
    if ($customFlagsEnabled) {
        $domain_id = domain_id::get($domain_id);
        $sql = "SELECT * FROM " . TB_PREFIX . "custom_flags WHERE domain_id = :domain_id";
        $sth = dbQuery($sql, ':domain_id', $domain_id);
        if ($sth !== false) {
            $ndx = 0;
            while ($custom_flag_label = $sth->fetch()) {
                if ($custom_flag_label['enabled'] == '1') {
                    $custom_flag_labels[$ndx] = $custom_flag_label['field_label'];
                }
                $ndx++;
            }
        }
    }
    return $custom_flag_labels;
}
/**
 * Get a specified custom_flags record.
 * @param string $associated_table Database table the flag is for.
 *        Only "products" currently have custom flags.
 * @param integer $flg_id Number of the flag to get the record for.
 * @param string $domain_id Domain to obtain records for. If not set, the domain for
 *        this session will be used.
 */
function getCustomFlag($associated_table, $flg_id, $domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT *
            FROM `" . TB_PREFIX . "custom_flags`
            WHERE `domain_id`        = :domain_id        AND
                  `associated_table` = :associated_table AND
                  `flg_id`           = :flg_id;";
    $sth = dbQuery($sql, ':domain_id'       , $domain_id,
                         ':associated_table', $associated_table,
                         ':flg_id'          , $flg_id);
    // @formatter:on
    if ($sth === false) return array();

    $cflg = $sth->fetch(PDO::FETCH_ASSOC);
    $cflg['wording_for_enabled'] = ($cflg['enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);
    return $cflg;
}

/**
 * Get all custom_flags records for the user's domain.
 * @param string $domain_id Domain to obtain records for. If not set, the domain for
 *        this session will be used.
 * @return array custom_flags rows with an added value, "wording_for_enabled", that contains
 *         "Enabled" or "Disabled" corresponding with the enabled field setting.
 */
function getCustomFlags($domain_id = '') {
    return getCustomFlagsQualified('A', $domain_id);
}

/**
 * Get custom_flag record based on the specified qualifier.
 * @param string $qualifier - Qualifies records to return.
 *        Valid options are: (A)ll, (E)nabled.
 * @param string $domain_id
 * @return multitype:unknown
 */
function getCustomFlagsQualified($qualifier, $domain_id = '') {
    global $LANG;
    $domain_id = domain_id::get($domain_id);
    // @formatter:off
    $sql = "SELECT *
            FROM `" . TB_PREFIX . "custom_flags`
            WHERE `domain_id` = :domain_id" .
                  ($qualifier == 'E' ? ' AND `enabled` = 1' : '') . ";";
    // @fomatter:on
    $sth = dbQuery($sql, ':domain_id', $domain_id);
    $cflgs = array();
    if($sth !== false) {
        while ($cflg = $sth->fetch(PDO::FETCH_ASSOC)) {
            $cflg['wording_for_enabled'] = ($cflg['enabled'] == 1 ? $LANG['enabled'] : $LANG['disabled']);
            $cflgs[] = $cflg;
        }
    }
    return $cflgs;
}

/**
 * Update the record with the specified values.
 * @param string $associated_table Associated table of record to update.
 *        Note: Only 'products' table defined at this time.
 * @param integer $flg_id Flag number (1 - 10) of record to update.
 * @param string $field_label The label that will be displayed on the screen where the custrom flag is displayed.
 * @param mixed $enabled Can be boolean, string or integer. A value of true, 'Enabled', '1' or 1 is an
 *        enabled setting. Otherwise, it is disabled.
 * @param string $field_help Help data to display for this field.
 * @param string $domain_id defaults to user's session id. Another value
 *        can be specified if needed.
 * @return boolean TRUE if update processed, FALSE if not.
 */
function updateCustomFlags($associated_table, $flg_id, $field_label, $enabled, $clear_flags, $field_help, $domain_id = '') {
    if (is_bool($enabled)) {
        $enabled = ($enabled ? 1 : 0);
    } elseif (is_string($enabled)) {
        // @formatter:off
        $enabled = ($enabled == 'Enabled'  ? 1 :
                    $enabled == 'Disabled' ? 0 : intval($enabled));
        // @formatter:on
    }

    // If the reset flags option was specified, do so now. Note that this is not considered
    // critical. Therefore failure to update will report in the error log for will not otherwise
    // affect the update.
    $products = getProducts();
    foreach ($products as $product) {
        if (substr($product['custom_flags'], $flg_id - 1, 1) == '1') {
            $custom_flags = substr_replace($product['custom_flags'], '0', $flg_id - 1, 1);

            // @formatter:off
            $sql = "UPDATE " . TB_PREFIX . "products
                    SET custom_flags = :custom_flags
                    WHERE id        = :id
                      AND domain_id = :domain_id";

            $result = dbQuery($sql, ':domain_id'    , $product['domain_id'],
                                    ':id'           , $product['id'],
                                    ':custom_flags' , $custom_flags
                             );
            if ($result === false) {
                error_log('updateCustomFlags(): Failed to reset custom flag #'.$flg_id.' for product ID, '.$product['id'].'.');
            }
            // @formatter:on
        }
    }

    // @formatter:off
    $sql = "UPDATE " .
                TB_PREFIX . "custom_flags
            SET field_label = :field_label,
                enabled     = :enabled,
                field_help  = :field_help
            WHERE associated_table = :associated_table
              AND flg_id           = :flg_id
              AND domain_id        = :domain_id";

    $sth = dbQuery($sql, ':associated_table', $associated_table,
                         ':flg_id'          , $flg_id,
                         ':field_label'     , $field_label,
                         ':enabled'         , $enabled,
                         ':field_help'      , $field_help,
                         ':domain_id'       , domain_id::get($domain_id));
    // @formatter:on
    return ($sth !== false && $sth->errorCode() == 0);
}

/**
 * Update product record
 * @param string $domain_id Domain ID of user. Defaults to user's session setting.
 * @return PDOStatement from dbQuery.
 */
function updateProduct_cflgs($domain_id = '') {
    $domain_id = domain_id::get($domain_id);

    // select all attributes
    $sql = "SELECT * FROM " . TB_PREFIX . "products_attributes";
    $sth = dbQuery($sql);
    if ($sth === false) return false;

    $attributes = $sth->fetchAll();

    $attr = array();
    foreach ($attributes as $v) {
        $tmp = (isset($_POST['attribute' . $v['id']]) ? $_POST['attribute' . $v['id']] : "");
        if ($tmp == 'true') {
            $attr[$v['id']] = $tmp;
        }
    }

    $notes_as_description = (isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : NULL);
    $show_description = (isset($_POST['show_description']) && $_POST['show_description'] == 'true' ? 'Y' : NULL);

    $custom_flags = '0000000000';
    for ($i = 1; $i <= 10; $i++) {
        if (isset($_POST['custom_flags_' . $i]) && $_POST['custom_flags_' . $i] == '1') {
            $custom_flags = substr_replace($custom_flags, '1', $i - 1, 1);
        }
    }

    // @formatter:off
    $sql = "UPDATE " . TB_PREFIX . "products
            SET description          = :description,
                enabled              = :enabled,
                default_tax_id       = :default_tax_id,
                notes                = :notes,
                custom_field1        = :custom_field1,
                custom_field2        = :custom_field2,
                custom_field3        = :custom_field3,
                custom_field4        = :custom_field4,
                unit_price           = :unit_price,
                cost                 = :cost,
                reorder_level        = :reorder_level,
                attribute            = :attribute,
                notes_as_description = :notes_as_description,
                show_description     = :show_description,
                custom_flags         = :custom_flags
            WHERE id        = :id
              AND domain_id = :domain_id";

    return dbQuery($sql, ':domain_id'           , $domain_id              ,
                         ':description'         , (isset($_POST['description'])    ? $_POST['description']    : ""),
                         ':enabled'             , (isset($_POST['enabled'])        ? $_POST['enabled']        : ""),
                         ':notes'               , (isset($_POST['notes'])          ? $_POST['notes']          : ""),
                         ':default_tax_id'      , (isset($_POST['default_tax_id']) ? $_POST['default_tax_id'] : ""),
                         ':custom_field1'       , (isset($_POST['custom_field1'])  ? $_POST['custom_field1']  : ""),
                         ':custom_field2'       , (isset($_POST['custom_field2'])  ? $_POST['custom_field2']  : ""),
                         ':custom_field3'       , (isset($_POST['custom_field3'])  ? $_POST['custom_field3']  : ""),
                         ':custom_field4'       , (isset($_POST['custom_field4'])  ? $_POST['custom_field4']  : ""),
                         ':unit_price'          , (isset($_POST['unit_price'])     ? $_POST['unit_price']     : ""),
                         ':cost'                , (isset($_POST['cost'])           ? $_POST['cost']           : ""),
                         ':reorder_level'       , (isset($_POST['reorder_level'])  ? $_POST['reorder_level']  : ""),
                         ':attribute'           , json_encode($attr)      ,
                         ':notes_as_description', $notes_as_description   ,
                         ':show_description'    , $show_description       ,
                         ':custom_flags'        , $custom_flags           ,
                         ':id'                  , $_GET['id']
                  );
    // @formatter:on
}

/**
 * Add a new product record
 * @param number $enabled 0 if false, 1 if true (1 is the default)
 * @param number $visible 0 if false, 1 if true (1 is the default);
 * @param string $domain_id User's Domain ID. Defaults to domain ID for the user's session.
 * @return PDOStatement result from dbQuery.
 */
function insertProduct_cflgs($enabled = 1, $visible = 1, $domain_id = '') {
    $domain_id = domain_id::get($domain_id);

    if (isset($_POST['enabled'])) $enabled = $_POST['enabled'];

    $sql = "SELECT * FROM " . TB_PREFIX . "products_attributes";
    $sth = dbQuery($sql);
    if ($sth === false) return false;

    $attributes = $sth->fetchAll();
    $custom_flags = '0000000000';
    for ($i = 1; $i <= 10; $i++) {
        if (isset($_POST['custom_flags_' . $i]) && $_POST['custom_flags_' . $i] == '1') {
            $custom_flags = substr_replace($custom_flags, '1', $i, 1);
        }
    }

    $attr = array();
    foreach ($attributes as $v) {
        if (isset($_POST['attribute' . $v['id']]) && $_POST['attribute' . $v['id']] == 'true') {
            $attr[$v['id']] = $_POST['attribute' . $v['id']];
        }
    }

    // @formatter:off
    $notes_as_description = (isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : NULL);
    $show_description     = (isset($_POST['show_description']    ) && $_POST['show_description'    ] == 'true' ? 'Y' : NULL);

    $cost = (isset($_POST['cost']) ? $_POST['cost'] : "");
    $sql = "INSERT into ".TB_PREFIX."products (
                    domain_id,
                    description,
                    unit_price,
                    cost,
                    reorder_level,
                    custom_field1,
                    custom_field2,
                    custom_field3,
                    custom_field4,
                    notes,
                    default_tax_id,
                    enabled,
                    visible,
                    attribute,
                    notes_as_description,
                    show_description,
                    custom_flags
                )
            VALUES
                (
                    :domain_id,
                    :description,
                    :unit_price,
                    :cost,
                    :reorder_level,
                    :custom_field1,
                    :custom_field2,
                    :custom_field3,
                    :custom_field4,
                    :notes,
                    :default_tax_id,
                    :enabled,
                    :visible,
                    :attribute,
                    :notes_as_description,
                    :show_description,
                    :custom_flags
                )";

    return dbQuery($sql, ':domain_id'           ,$domain_id,
                         ':description'         , (isset($_POST['description']   ) ? $_POST['description']    : ""),
                         ':unit_price'          , (isset($_POST['unit_price']    ) ? $_POST['unit_price']     : ""),
                         ':cost'                , $cost,
                         ':reorder_level'       , (isset($_POST['reorder_level'] ) ? $_POST['reorder_level']  : ""),
                         ':custom_field1'       , (isset($_POST['custom_field1'] ) ? $_POST['custom_field1']  : ""),
                         ':custom_field2'       , (isset($_POST['custom_field2'] ) ? $_POST['custom_field2']  : ""),
                         ':custom_field3'       , (isset($_POST['custom_field3'] ) ? $_POST['custom_field3']  : ""),
                         ':custom_field4'       , (isset($_POST['custom_field4'] ) ? $_POST['custom_field4']  : ""),
                         ':notes'               , (isset($_POST['notes']         ) ? $_POST['notes']          : ""),
                         ':default_tax_id'      , (isset($_POST['default_tax_id']) ? $_POST['default_tax_id'] : ""),
                         ':enabled'             , $enabled,
                         ':visible'             , $visible,
                         ':attribute'           , json_encode($attr),
                         ':notes_as_description', $notes_as_description,
                         ':show_description'    , $show_description,
                         ':custom_flags'        , $custom_flags
                  );
  // @formatter:on
}
