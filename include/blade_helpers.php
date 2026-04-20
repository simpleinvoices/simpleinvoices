<?php
/**
 * Blade-compatible helpers for template output.
 * Used by Blade directives and legacy tag precompilers ({merge_address ...}, etc.).
 * All functions return HTML strings (no echo) for use in Blade {{ }} or @directives.
 */

// --- Request / form helpers (direct $_GET / $_POST access for Blade templates) ---

if (!function_exists('post')) {
/**
 * Get POST value(s).
 *
 * @param string|null $key    Key (e.g. 'name', 'submit'); null = entire $_POST
 * @param mixed       $default Default when key missing
 * @return mixed
 */
function post($key = null, $default = '') {
    if ($key === null) {
        return $_POST ?? [];
    }
    return $_POST[$key] ?? $default;
}
}

if (!function_exists('get')) {
/**
 * Get GET value(s).
 *
 * @param string|null $key    Key; null = entire $_GET
 * @param mixed       $default Default when key missing
 * @return mixed
 */
function get($key = null, $default = '') {
    if ($key === null) {
        return $_GET ?? [];
    }
    return $_GET[$key] ?? $default;
}
}

if (!function_exists('form_submitted')) {
/**
 * Whether the request is a POST and (optionally) a submit key is present.
 *
 * @param string|null $submitKey Name of submit button (default 'submit'); null = any POST
 * @return bool
 */
function form_submitted($submitKey = 'submit') {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return false;
    }
    if ($submitKey === null) {
        return true;
    }
    return isset($_POST[$submitKey]);
}
}

if (!function_exists('blade_merge_address')) {
/**
 * Merge city, state, zip onto one line with commas; optional street lines with "Address:" label.
 *
 * @param array $params field1 (city), field2 (state), field3 (zip), street1, street2, class1, class2, colspan
 * @return string HTML table row(s)
 */
function blade_merge_address(array $params) {
    global $LANG;
    $skip_section = false;
    $ma = '';
    $field1 = $params['field1'] ?? null;
    $field2 = $params['field2'] ?? null;
    $field3 = $params['field3'] ?? null;
    $street1 = $params['street1'] ?? null;
    $street2 = $params['street2'] ?? null;
    $class1 = htmlspecialchars((string)($params['class1'] ?? ''), ENT_QUOTES, 'UTF-8');
    $class2 = htmlspecialchars((string)($params['class2'] ?? ''), ENT_QUOTES, 'UTF-8');
    $colspan = htmlspecialchars((string)($params['colspan'] ?? '3'), ENT_QUOTES, 'UTF-8');

    if (($field1 != null || $field2 != null || $field3 != null) && ($street1 == null && $street2 == null)) {
        $ma .= "\n\t\t<tr>\n\t\t\t<td class='" . $class1 . "'>" . ($LANG['address'] ?? '') . ":</td>\n\t\t\t<td class='" . $class2 . "' colspan='" . $colspan . "'>";
        $skip_section = true;
    }
    if (($field1 != null || $field2 != null || $field3 != null) && !$skip_section) {
        $ma .= "\n\t\t<tr>\n\t\t\t<td class='" . $class1 . "'></td>\n\t\t\t<td class='" . $class2 . "' colspan='" . $colspan . "'>";
    }
    if ($field1 != null) {
        $ma .= htmlspecialchars((string)$field1, ENT_QUOTES, 'UTF-8');
    }
    if ($field1 != null && $field2 != null) {
        $ma .= ", ";
    }
    if ($field2 != null) {
        $ma .= htmlspecialchars((string)$field2, ENT_QUOTES, 'UTF-8');
    }
    if (($field1 != null || $field2 != null) && $field3 != null) {
        $ma .= ", ";
    }
    if ($field3 != null) {
        $ma .= htmlspecialchars((string)$field3, ENT_QUOTES, 'UTF-8');
    }
    $ma .= "</td>\n\t\t</tr>";
    return $ma;
}
}

if (!function_exists('blade_inv_itemised_cf')) {
/**
 * Print a single custom field label/value in a table cell (invoice itemised custom fields).
 *
 * @param array $params label, field
 * @return string HTML <td> or empty string
 */
function blade_inv_itemised_cf(array $params) {
    $field = $params['field'] ?? null;
    if ($field == null) {
        return '';
    }
    $label = $params['label'] ?? '';
    return "<td width=\"50%\">" . htmlspecialchars((string)$label, ENT_QUOTES, 'UTF-8') . ": " . htmlspecialchars((string)$field, ENT_QUOTES, 'UTF-8') . "</td>";
}
}

if (!function_exists('blade_print_if_not_null')) {
/**
 * Print a table row with label and value only if value is not null.
 *
 * @param array $params label, field, class1, class2, colspan
 * @return string HTML <tr>...</tr> or empty string
 */
function blade_print_if_not_null(array $params) {
    $field = $params['field'] ?? null;
    if ($field == null) {
        return '';
    }
    $label = htmlspecialchars((string)($params['label'] ?? ''), ENT_QUOTES, 'UTF-8');
    $class1 = htmlspecialchars((string)($params['class1'] ?? ''), ENT_QUOTES, 'UTF-8');
    $class2 = htmlspecialchars((string)($params['class2'] ?? ''), ENT_QUOTES, 'UTF-8');
    $colspan = htmlspecialchars((string)($params['colspan'] ?? '3'), ENT_QUOTES, 'UTF-8');
    return "\n\t\t<tr>\n\t\t\t<td class='" . $class1 . "'>" . $label . ":</td>\n\t\t\t<td class='" . $class2 . "' colspan='" . $colspan . "'>" . htmlspecialchars((string)$field, ENT_QUOTES, 'UTF-8') . "</td>\n\t\t</tr>";
}
}

if (!function_exists('blade_do_tr')) {
/**
 * Emit closing </tr> and opening <tr> for multi-column layout (number 2 or 4).
 *
 * @param array $params number (1–4), class
 * @return string HTML fragment or empty string
 */
function blade_do_tr(array $params) {
    $number = $params['number'] ?? 0;
    $class = htmlspecialchars((string)($params['class'] ?? ''), ENT_QUOTES, 'UTF-8');
    if ($number == 2 || $number == 4) {
        return "</tr><tr class='" . $class . "'>";
    }
    return '';
}
}

if (!function_exists('blade_html_options')) {
/**
 * Build <select> and <option>s (legacy {html_options} tag).
 *
 * @param array $params name, options (assoc value=>label), or values+output (parallel arrays), selected, and any HTML attr (e.g. class)
 * @return string HTML <select>...</select> or just <option>s if no name
 */
function blade_html_options(array $params) {
    $name = $params['name'] ?? null;
    $options = $params['options'] ?? null;
    $values = $params['values'] ?? null;
    $output = $params['output'] ?? null;
    $selected = $params['selected'] ?? null;

    if ($options !== null && is_array($options)) {
        $valueLabelPairs = [];
        foreach ($options as $val => $label) {
            $valueLabelPairs[] = [ (string)$val, (string)$label ];
        }
    } elseif ($values !== null && is_array($values) && $output !== null && is_array($output)) {
        $valueLabelPairs = [];
        $n = min(count($values), count($output));
        for ($i = 0; $i < $n; $i++) {
            $valueLabelPairs[] = [ (string)$values[$i], (string)$output[$i] ];
        }
    } elseif ($values !== null && is_array($values)) {
        $valueLabelPairs = [];
        foreach ($values as $v) {
            $valueLabelPairs[] = [ (string)$v, (string)$v ];
        }
    } else {
        return '';
    }

    $selectedStr = $selected !== null ? (string)$selected : '';
    $opts = '';
    foreach ($valueLabelPairs as list($val, $label)) {
        $valEsc = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
        $labelEsc = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $sel = ($valEsc === $selectedStr || (string)$val === $selectedStr) ? ' selected="selected"' : '';
        $opts .= '<option value="' . $valEsc . '"' . $sel . '>' . $labelEsc . '</option>';
    }

    if ($name === null || $name === '') {
        return $opts;
    }

    $attrs = [];
    $allowed = [ 'name', 'class', 'id', 'style', 'disabled', 'readonly', 'required' ];
    foreach ($allowed as $attr) {
        if (isset($params[$attr]) && $params[$attr] !== '' && $params[$attr] !== null) {
            $attrs[$attr] = htmlspecialchars((string)$params[$attr], ENT_QUOTES, 'UTF-8');
        }
    }
    if (!isset($attrs['name'])) {
        $attrs['name'] = htmlspecialchars((string)$name, ENT_QUOTES, 'UTF-8');
    }
    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= ' ' . $k . '="' . $v . '"';
    }
    return '<select' . $attrStr . '>' . $opts . '</select>';
}
}

if (!function_exists('showCustomFieldsForBlade')) {
/**
 * Render additional plugin-based custom fields for a category/item.
 * The plugin-based extra custom field system was removed; the 4 standard custom fields
 * (custom_field1–4) are rendered directly in Blade templates. This stub satisfies the
 * @showCustomFields directive calls that remain in those templates.
 */
function showCustomFieldsForBlade($categorieId, $itemId = '') {
    return '';
}
}

if (!function_exists('blade_show_custom_fields')) {
/**
 * Render custom fields for a category/item (tag form: {showCustomFields categorieId="1" itemId="..."}).
 *
 * @param array $params categorieId, itemId
 * @return string HTML
 */
function blade_show_custom_fields(array $params) {
    $categorieId = $params['categorieId'] ?? '';
    $itemId = $params['itemId'] ?? '';
    return showCustomFieldsForBlade($categorieId, $itemId);
}
}
