<?php

/**
 * Get the custom flag labels
 * @param string $domain_id Domain to obtain records for. If not set, the domain for
 *        this session will be used.
 */
function getCustomFlagLabels($customFlagsEnabled, $domain_id = '') {
    global $pdoDb;
    $custom_flag_labels = array('','','','','','','','','','');
    if ($customFlagsEnabled) {
        $domain_id = domain_id::get($domain_id);
        $pdoDb->addSimpleWhere("domain_id", $domain_id);
        $rows = $pdoDb->request("SELECT", "custom_flags");
        $ndx = 0;
        foreach($rows as $row) {
            if ($row['enabled'] == ENABLED) $custom_flag_labels[$ndx] = $row['field_label'];
            $ndx++;
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
    global $LANG, $pdoDb;
    $domain_id = domain_id::get($domain_id);

    $pdoDb->addSimpleWhere("domain_id", $domain_id, "AND");
    $pdoDb->addSimpleWhere("associated_table", $associated_table, "AND");
    $pdoDb->addSimpleWhere("flg_id", $flg_id);
    $rows = $pdoDb->request("SELECT", "custom_flags");
    if (empty($rows)) return $rows;

    $cflg = $rows[0];
    $cflg['wording_for_enabled'] = ($cflg['enabled'] == ENABLED ? $LANG['enabled'] : $LANG['disabled']);
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
    global $LANG, $pdoDb;
    $domain_id = domain_id::get($domain_id);

    if ($qualifier == "E") {
        $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
    }
    $pdoDb->addSimpleWhere("domain_id", $domain_id);
    $rows = $pdoDb->request("SELECT", "custom_flags");
    $cflgs = array();
    foreach($rows as $cflg) {
        $cflg['wording_for_enabled'] = ($cflg['enabled'] == ENABLED ? $LANG['enabled'] : $LANG['disabled']);
        $cflgs[] = $cflg;
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
 * @return boolean <b>true</b> if update processed, <b>false</b> if not.
 */
function updateCustomFlags($associated_table, $flg_id, $field_label, $enabled, $clear_flags, $field_help, $domain_id = '') {
    if (is_bool($enabled)) {
        $enabled = ($enabled ? ENABLED : DISABLED);
    } elseif (is_string($enabled)) {
        // @formatter:off
        $enabled = ($enabled == 'Enabled'  ? ENABLED  :
                    $enabled == 'Disabled' ? DISABLED : intval($enabled));
        // @formatter:on
    }

    try {
        // If the reset flags option was specified, do so now. Note that this is not considered
        // critical. Therefore failure to update will report in the error log for will not otherwise
        // affect the update.
        $products = Product::select_all();
        $requests = new Requests();
        if ($clear_flags == ENABLED) {
            foreach ($products as $product) {
                if (substr($product['custom_flags'], $flg_id - 1, 1) == ENABLED) {
                    $custom_flags = substr_replace($product['custom_flags'], DISABLED, $flg_id - 1, 1);

                    $request = new Request("UPDATE", "products");
                    $request->addSimpleWhere("id", $product['id'], "AND");
                    $request->addSimpleWhere("domain_id", $product['domain_id']);
                    $request->setFauxPost(array("custom_flags" => $custom_flags));
                    $requests->add($request);
                }
            }
        }

        // @formatter:off
        $request = new Request("UPDATE", "custom_flags");
        $request->addSimpleWhere("flg_id", $flg_id, "AND");
        $request->addSimpleWhere("domain_id", domain_id::get($domain_id), "AND");
        $request->addSimpleWhere("associated_table", $associated_table);
        $request->addFauxPostList(array("field_label" => $field_label, "enabled" => $enabled, "field_help" => $field_help));
        $requests->add($request);

        $requests->process();
    } catch (PDOException $pde) {
        return false;
    }

    return true;
}
