<?php
namespace SimpleInvoices\Controller;

/**
 * TODO: 
 *     - Get rid of global variables.
 *     - Split template and code
 *
 * @author Juan Pedro Gonzalez Gutierrez
 */
class SystemDefaultsController
{
    protected $smarty;

    /**
     * TODO: Don't use globals!
     */
    public function __construct()
    {
        global $smarty;

        $this->smarty = $smarty;
    }
    
    /**
     * Help function for sorting the language array by name
     */
    protected function compareNameIndex($a,$b) 
    {
        $a = $a->name."";
        $b = $b->name."";
        
        if($a > $b) {
            return 1;
        }
        return -1;
    }

    public function editAction()
    {
        global $LANG;
        
        $defaults = getSystemDefaults();
     
        if ($_GET["submit"] == "line_items") {
        
            jsBegin();
            jsFormValidationBegin("frmpost");
            jsValidateifNum("def_num_line_items","Default number of line items");
            jsFormValidationEnd();
            jsEnd();
        
            $default = "line_items";
        
            $escaped = htmlsafe($defaults[line_items]);
            $value = <<<EOD
<input type="text" size="25" name="value" value="$escaped">
EOD;
            $description = "{$LANG['default_number_items']}";
        
        } else if ($_GET["submit"] == "def_inv_template") {
        
            $default = "template";
            /*drop down list code for invoice template - only show the folder names in src/invoices/templates*/
        
            $handle=opendir("./templates/invoices/");
            while ($template = readdir($handle)) {
                if ($template != ".." && $template != "." && $template !="logos" && $template !=".svn" && $template !="template.php" && $template !="template.php~" ) {
                    $files[] = $template;
                }
            }
            closedir($handle);
            sort($files);
        
            $escaped = htmlsafe($defaults[template]);
            $display_block_templates_list = <<<EOD
	<select name="value">
EOD;
        
            $display_block_templates_list .= <<<EOD
	<option selected value='$escaped' style="font-weight: bold" >$escaped</option>
EOD;
        
            foreach ( $files as $var ) {
                $var = htmlsafe($var);
                $display_block_templates_list .= "<option value='$var' >";
                $display_block_templates_list .= $var;
                $display_block_templates_list .= "</option>";
            }
        
        
            $display_block_templates_list .= "</select>";
        
            /*end drop down list section */
            /*start validataion section */
        
            jsBegin();
            jsFormValidationBegin("frmpost");
            jsValidateRequired("def_inv_template","{$LANG['default_inv_template']}");
            jsFormValidationEnd();
            jsEnd();
            /*end validataion section */
        
        
            $description = $LANG['default_inv_template'];
        
            $value = $display_block_templates_list;
            //error_log($value);
        
        } else if ($_GET["submit"] == "biller") {
            $default = "biller";
        
            #biller query
            $billers = getActiveBillers();
        
            if ($billers == null) {
                $display_block_biller = "<p><em>{$LANG['no_billers']}</em></p>";
            } else {
        
                $display_block_biller = '<select name="value">
			<option value="0"> </option>';
        
                foreach($billers as $biller) {
        
                    $selected = $biller['id'] == $defaults['biller']?"selected style='font-weight: bold'":"";
                    	
                    $escaped = htmlsafe($biller['name']);
                    $display_block_biller .= <<<EOD
			<option $selected value="$biller[id]">$escaped</option>
EOD;
                }
                $display_block_biller .= "</select>";
            }
        
            $description = "{$LANG['biller_name']}";
            $value = $display_block_biller;
            
        } else if ($_GET["submit"] == "customer") {
        
            $default = "customer";
            $customers = getActiveCustomers();
        
            if ($customers == null) {
                //no records
                $display_block_customer = "<p><em>{$LANG['no_customers']}</em></p>";
        
            } else {
                //has records, so display them
                $display_block_customer = '<select name="value">
                <option value="0"> </option>';
        
        
                foreach($customers as $customer) {
        
                    $selected = $customer['id'] == $defaults['customer']?"selected style='font-weight: bold'":"";
                    	
        
                    $escaped = htmlsafe($customer['name']);
                    $display_block_customer .= <<<EOD
			<option $selected value="$customer[id]">$escaped</option>
EOD;
                }
                $display_block_customer .= "</select>";
        
            }
        
            $description = "{$LANG['customer_name']}";
            $value = $display_block_customer;
        } else if ($_GET['submit'] == "tax") {
            $default = "tax";
        
            $taxes = getActiveTaxes();
        
            if ($taxes == null) {
                //no records
                $display_block_tax = "<p><em>{$LANG['no_tax_rates']}</em></p>";
        
            } else {
                //has records, so display them
        
                $display_block_tax = <<<EOD
	        <select name="value">
        
                <option value='0'> </option>
EOD;
        
        
                foreach($taxes as $tax) {
        
                    $selected = $tax['tax_id'] == $defaults['tax']?"selected style='font-weight: bold'":"";
        
                    $escaped = htmlsafe($tax['tax_description']);
                    $display_block_tax .= <<<EOD
			<option $selected value="$tax[tax_id]">$escaped</option>
EOD;
                }
            }
        
            $description = "{$LANG['tax']}";
            $value = $display_block_tax;
        } else if ($_GET["submit"] == "preference_id") {
        
            $pref = getPreference($defaults['preference']);
            $preferences = getActivePreferences();
        
            if ($preferences == null) {
                //no records
                $display_block_preferences = "<p><em>{$LANG['no_preferences']}</em></p>";
        
            } else {
                $default = "preference";
                //has records, so display them
                $display_block_preferences = <<<EOD
	        <select name="value">
        
                <option value='0'> </option>
EOD;
        
                foreach($preferences as $preference) {
        
                    $selected = $preference['pref_id'] == $defaults['preference']?"selected style='font-weight: bold'":"";
        
                    $escaped = htmlsafe($preference['pref_description']);
                    $display_block_preferences .= <<<EOD
			<option $selected value="{$preference['pref_id']}">
	                        $escaped</option>
EOD;
                }
            }
        
            $value = $display_block_preferences;
            $description = "{$LANG['inv_pref']}";
        
        } else if ($_GET["submit"] == "def_payment_type") {
            $payments = getActivePaymentTypes();
        
            if ($payments == null) {
                //no records
                $display_block_payment_type = "<p><em>{$LANG['payment_type']}</em></p>";
            } else {
                $default = "payment_type";
                //has records, so display them
                $display_block_payment_type = <<<EOD
                <select name="value">
        
                <option value='0'> </option>
EOD;
        
                foreach($payments as $payment) {
        
                    $selected = $payment['pt_id'] == $defaults['payment_type']?"selected style='font-weight: bold'":"";
                    $escaped = htmlsafe($payment['pt_description']);
                    $display_block_payment_type .= <<<EOD
			<option $selected value="{$payment['pt_id']}">
                        $escaped</option>
EOD;
                }
            }
        
            $description = "{$LANG['payment_type']}";
            $value = $display_block_payment_type;
        } else if ($_GET["submit"] == "delete") {
            $array = array(0 => $LANG['disabled'], 1=>$LANG['enabled']);
            $default = "delete";
            $description = $LANG['delete'];
            $value = dropDown($array, $defaults['delete']);
        } else if ($_GET['submit'] == "logging") {
        
            $array = array(0 => $LANG['disabled'], 1=>$LANG['enabled']);
            $default = "logging";
            $description = $LANG['logging'];
            $value = dropDown($array, $defaults[$default]);
        } else if($_GET['submit'] == "language") {
            $default = "language";
            $languages = getLanguageList();
            $lang = getDefaultLanguage();
        
            usort($languages, array($this, 'compareNameIndex'));
        
            $description = $LANG[language];
            //print_r($languages);
            $value = "<select name='value'>";
            foreach($languages as $language) {
                $selected = "";
                if($language->shortname == $lang) {
                    $selected = " selected ";
                }
                $value .= "<option $selected value='".htmlsafe($language->shortname)."'>".htmlsafe("$language->name ($language->englishname) ($language->shortname)")."</option>";
            }
            $value .= "</select>";
        } elseif ($_GET["submit"] == "tax_per_line_item") {
            $default = "tax_per_line_item";
            $escaped = htmlsafe($defaults[tax_per_line_item]);
            $value = '<input type="text" size="25" name="value" value="' . $escaped . '">';
            $description = "{$LANG['number_of_taxes_per_line_item']}";
        } else if ($_GET['submit'] == "inventory") {
            $array = array(0 => $LANG['disabled'], 1=>$LANG['enabled']);
            $default = "inventory";
            $description = $LANG['inventory'];
            $value = dropDown($array, $defaults[$default]);
        } else if ($_GET['submit'] == "product_attributes") {
            $array = array(0 => $LANG['disabled'], 1=>$LANG['enabled']);
            $default = "product_attributes";
            $description = $LANG['product_attributes'];
            $value = dropDown($array, $defaults[$default]);
        } else if ($_GET['submit'] == "large_dataset") {
            $array = array(0 => $LANG['disabled'], 1=>$LANG['enabled']);
            $default = "large_dataset";
            $description = $LANG['large_dataset'];
            $value = dropDown($array, $defaults[$default]);
        } else {
            $description = "{$LANG['no_defaults']}";
        }
        
        
        /*$this->smarty->assign('pageActive', $pageActive);
         $this->smarty->assign('files', $files);
         $this->smarty->assign('customFieldLabel', $customFieldLabel);
         $this->smarty->assign('save', $save);
         $this->smarty->assign('lang', $lang);
         $this->smarty->assign('billers',$billers);*/
        $this->smarty->assign('defaults', $defaults);
        $this->smarty->assign('value', $value);
        $this->smarty->assign('description', $description);
        $this->smarty->assign('default', $default);
        
        $this->smarty->assign('pageActive', 'system_default');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function manageAction()
    {
        //gets the long language name out of the short name
        $lang = getDefaultLanguage();
        $languages = getLanguageList();
        foreach($languages as $language) {
            if($language->shortname == $lang) {
                $lang = $language->name;
                break;
            }
        }
        
        $this->smarty->assign("defaults", getSystemDefaults());
        $this->smarty->assign("defaultBiller", getDefaultBiller());
        $this->smarty->assign("defaultCustomer", getDefaultCustomer());
        $this->smarty->assign("defaultTax", getDefaultTax());
        $this->smarty->assign("defaultPreference", getDefaultPreference());
        $this->smarty->assign("defaultPaymentType", getDefaultPaymentType());
        $this->smarty->assign("defaultDelete", getDefaultDelete());
        $this->smarty->assign("defaultLogging", getDefaultLogging());
        $this->smarty->assign("defaultInventory", getDefaultInventory());
        $this->smarty->assign("defaultProductAttributes", getDefaultProductAttributes());$pref = getPreference($defaults['preference']);
            $preferences = getActivePreferences();
        
            if ($preferences == null) {
                //no records
                $display_block_preferences = "<p><em>{$LANG['no_preferences']}</em></p>";
        
            } else {
                $default = "preference";
                //has records, so display them
                $display_block_preferences = <<<EOD
	        <select name="value">
        
                <option value='0'> </option>
EOD;
        
                foreach($preferences as $preference) {
        
                    $selected = $preference['pref_id'] == $defaults['preference']?"selected style='font-weight: bold'":"";
        
                    $escaped = htmlsafe($preference['pref_description']);
                    $display_block_preferences .= <<<EOD
			<option $selected value="{$preference['pref_id']}">
	                        $escaped</option>
EOD;
                }
            }
        
            $value = $display_block_preferences;
            $description = "{$LANG['inv_pref']}";
        $this->smarty->assign("defaultLargeDataset", getDefaultLargeDataset());
        $this->smarty->assign("defaultLanguage", $lang);
        $this->smarty->assign('pageActive', 'system_default');
        $this->smarty->assign('active_tab', '#setting');
    }
    
    public function saveAction()
    {
        $saved = false;
        
        if (isset($_POST['op']) && $_POST['op'] == 'update_system_defaults' ) {
            $saved = updateDefault($_POST['name'],$_POST['value']);
        }
        
        $this->smarty->assign('saved',      $saved);
        $this->smarty->assign('pageActive', 'system_default');
        $this->smarty->assign('active_tab', '#setting');
    }
}