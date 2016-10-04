<?php
global $LANG,
       $smarty,
       $extension_php_insert_files,
       $perform_extension_php_insertions;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

/**
 * Help function for sorting the language array by name
 */
function compareNameIndex($a, $b) {
  $a = $a->name . "";
  $b = $b->name . "";
  return ($a > $b ? 1 : -1);
}

$defaults = getSystemDefaults();

// @formatter:off
$get_val = (empty($_GET['submit']) ? '' : trim($_GET['submit']));
switch ($get_val) {
  case "line_items":
    jsBegin();
    jsFormValidationBegin("frmpost");
    jsValidateifNum("def_num_line_items", "Default number of line items");
    jsFormValidationEnd();
    jsEnd();

    $default     = "line_items";
    $value       = '<input type="text" size="25" name="value" value="'.htmlsafe($defaults['line_items']).'">';
    $description = "{$LANG['default_number_items']}";
    break;

  case "def_inv_template":
    $default = "template";

    /****************************************************************
     * Make drop down list invoice template - start
     * Note: Only show the folder names in src/invoices/templates
     ****************************************************************/
    $handle = opendir("./templates/invoices/");
    $files = array();
    while ($template = readdir($handle)) {
      if ($template != ".."           &&
          $template != "."            &&
          $template != "logos"        &&
          $template != ".svn"         &&
          $template != "template.php" &&
          $template != "template.php~") {
        $files[] = $template;
      }
    }
    closedir($handle);
    sort($files);

    $escaped = htmlsafe($defaults['template']);
    $value   = '<select name="value">' . "\n";
    $value  .= '  <option selected value="'.$escaped.'" style="font-weight:bold;" >';
    $value  .= '    '.$escaped;
    $value  .= '  </option>' . "\n";

    foreach ($files as $var) {
      $var    = htmlsafe($var);
      $value .= '  <option value="'.$var.'" >';
      $value .= '    '.$var;
      $value .= '  </option> . "\n"';
    }

    $value .= '</select>' . "\n";
    /****************************************************************
     * Make drop down list invoice template - end
     ****************************************************************/

    /****************************************************************
     * Validation section - start
     ****************************************************************/
    jsBegin();
    jsFormValidationBegin("frmpost");
    jsValidateRequired("def_inv_template", "{$LANG['default_inv_template']}");
    jsFormValidationEnd();
    jsEnd();
    /****************************************************************
     * Validation section - end
     ****************************************************************/

    $description = $LANG['default_inv_template'];
    break;

  case "biller":
    $default = "biller";
    $billers = Biller::get_all(true);
    if (empty($billers)) {
      $value = "<p><em>{$LANG['no_billers']}</em></p>" . "\n";
    }
    else {
      $value  = '<select name="value">' . "\n";
      $value .= '  <option value="0"></option>' . "\n";

      foreach ($billers as $biller) {
        $selected = $biller['id'] == $defaults['biller'] ? "selected style='font-weight: bold'" : "";
        $escaped  = htmlsafe($biller['name']);
        $value   .= '<option ' . $selected . ' value="' . $biller['id'] . '">' . $escaped . '</option>' . "\n";
      }
      $value .= "</select>" . "\n";
    }

    $description = "{$LANG['biller_name']}";
    break;

  case "customer":
    $default   = "customer";
    $customers = Customer::get_all(true);

    if (empty($customers)) {
      $value = "<p><em>{$LANG['no_customers']}</em></p>" . "\n";
    }
    else {
      $value  = '<select name="value">' . "\n";
      $value .= '  <option value="0"> </option>' . "\n";

      foreach ($customers as $customer) {
        $selected = $customer['id'] == $defaults['customer'] ? "selected style='font-weight: bold'" : "";
        $escaped  = htmlsafe($customer['name']);
        $value   .= '<option ' . $selected . ' value="' . $customer['id'] . '">' . $escaped . '</option>' . "\n";
      }
      $value .= "</select>" . "\n";
    }

    $description = "{$LANG['customer_name']}";
    break;

  case "tax":
    $default = "tax";
    $taxes = getActiveTaxes();
    if (empty($taxes)) {
      $value = "<p><em>{$LANG['no_tax_rates']}</em></p>" . "\n";
    }
    else {
      $value  = '<select name="value">' . "\n";
      $value .= '  <option value="0"> </option>' . "\n";

      foreach ($taxes as $tax) {
        $selected = $tax['tax_id'] == $defaults['tax'] ? "selected style='font-weight: bold'" : "";
        $escaped  = htmlsafe($tax['tax_description']);
        $value   .= '<option ' . $selected . ' value="' . $tax['tax_id'] . '">' . $escaped . '</option>' . "\n";
      }
    }

    $description = "{$LANG['tax']}";
    break;

  case "preference_id":
//    $pref        = getPreference($defaults['preference']);
    $preferences = getActivePreferences();

    if (empty($preferences)) {
      $value = "<p><em>{$LANG['no_preferences']}</em></p>" . "\n";
    }
    else {
      $default = "preference";
      $value   = '<select name="value">' . "\n";
      $value  .= '  <option value="0"></option>' . "\n";

      foreach ($preferences as $preference) {
        $selected = ($preference['pref_id'] == $defaults['preference'] ? ' selected style="font-weight:bold"' : '');
        $escaped  = htmlsafe($preference['pref_description']);
        $value   .= '  <option'.$selected.' value="'.$preference['pref_id'].'">' . "\n";
        $value   .= '    '.$escaped . "\n";
        $value   .= '  </option>' . "\n";
      }
    }

    $description = "{$LANG['inv_pref']}";
    break;

  case "def_payment_type":
    $payments = PaymentType::select_all(true);
    if (empty($payments)) {
      $value = "<p><em>{$LANG['payment_type']}</em></p>";
    }
    else {
      $default = "payment_type";
      $value   = '<select name="value">' . "\n";
      $value  .= '  <option value="0"> </option>' . "\n";

      foreach ($payments as $payment) {
        $selected = $payment['pt_id'] == $defaults['payment_type'] ? " selected style='font-weight: bold'" : "";
        $escaped = htmlsafe($payment['pt_description']);
        $value .= '  <option'.$selected.' value="'.$payment['pt_id'].'}">' . "\n";
        $value .= '    '.$escaped . "\n";
        $value .= '  </option>' . "\n";
      }
    }

    $description = "{$LANG['payment_type']}";
    break;

  case "delete":
    $array       = array(0 => $LANG['disabled'], 1 => $LANG['enabled']);
    $default     = "delete";
    $description = $LANG['delete'];
    $value       = dropDown($array, $defaults['delete']);
    break;

  case "logging":
    $array       = array(0 => $LANG['disabled'], 1 => $LANG['enabled']);
    $default     = "logging";
    $description = $LANG['logging'];
    $value       = dropDown($array, $defaults[$default]);
    break;

  case "language":
    $default   = "language";
    $languages = getLanguageList();
    $lang      = getDefaultLanguage();

    usort($languages, "compareNameIndex");
    $description = $LANG['language'];
    $value = "<select name='value'>";
    foreach ($languages as $language) {
      $selected = ($language->shortname == $lang ? " selected" : '');
      $value .= '  <option'.$selected.' value="'.htmlsafe($language->shortname).'">' . "\n";
      $value .= '    '.htmlsafe("$language->name ($language->englishname) ($language->shortname)") . "\n";
      $value .= '  </option>' . "\n";
    }
    $value .= '</select>' . "\n";
    break;

  case "tax_per_line_item":
    $default     = "tax_per_line_item";
    $value       = '<input type="text" size="25" name="value" value="'.htmlsafe($defaults['tax_per_line_item']).'">' . "\n";
    $description = "{$LANG['number_of_taxes_per_line_item']}";
    break;

  case "inventory":
    $array       = array(0 => $LANG['disabled'], 1 => $LANG['enabled']);
    $default     = "inventory";
    $description = $LANG['inventory'];
    $value       = dropDown($array, $defaults[$default]);
    break;

  case "product_attributes":
    $array       = array(0 => $LANG['disabled'], 1 => $LANG['enabled']);
    $default     = "product_attributes";
    $description = $LANG['product_attributes'];
    $value       = dropDown($array, $defaults[$default]);
    break;

  case "large_dataset":
    $array       = array(0 => $LANG['disabled'], 1 => $LANG['enabled']);
    $default     = "large_dataset";
    $description = $LANG['large_dataset'];
    $value       = dropDown($array, $defaults[$default]);
    break;

  default:
    // The following logic allows the edit of system default extension
    // values.  The content of the extenson edit.tpl file will be inserted
    // loaded below and all the generic edit template to display them.
    // The $get_val variable contains the field name that is to be edited.
    $found = false;
    if ($perform_extension_php_insertions) {
        foreach ($extension_php_insert_files as $phpfile) {
            if ($phpfile['module'] == 'system_defaults' &&
                $phpfile['view']   == 'edit') {
                include_once $phpfile['file'];
                if ($found) break;
            }
        }
    }

    if (!$found) {
        $default     = null;
        $value       = '';
        $description = "{$LANG['no_defaults']}";
    }
    break;
}

$smarty->assign('defaults'   , $defaults);
$smarty->assign('value'      , $value);
$smarty->assign('description', $description);
$smarty->assign('default'    , $default);
$smarty->assign('pageActive' , 'system_default');
$smarty->assign('active_tab' , '#setting');
// @formatter:on
