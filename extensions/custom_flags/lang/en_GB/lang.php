<?php
// @formatter:off
$LCL_LANG = array(
  'associated_table' => "Assoc Table",
  'clear_data' => "Clear Data",
  'custom_flags_upper' => "Custom Flags",
  'custom_upper' => "Custom",
  'field_label_upper' => "Field Label",
  'field_help_upper' => "Field Help",
  'fields_upper' => "Fields",
  'flag_number' => "Flag&#35;",
  'flags_upper' => "Flags",
  'help_custom_flags_associated_table' => "This is the name of the database table (without the prefix) that this custom flag
                                           is associated with. For example, the value <strong>products</strong> means the flag
                                           is for the <em>products</em>.",
  'help_custom_flags_field_help' => "Enter help information to explain the meaning of this custom flag. It will be displayed
                                     when the help option is selected for this field on the associated table screen.",  
  'help_custom_flags_field_label' => "Enter the label that will be display for this option on the associated screen.",  
  'help_custom_flags_flag_number' => "This is the number of the this custom flag. The numbers range from 1 to 10 and can be
                                      assigned a meaning of your desire.",  
  'help_custom_flags_products' => "Product Custom Flags allow you to specify a user defined condition for a product.
                                   This feature is used primarily to tailor reports and documents. However, the conditions
                                   can be used in any manner you might need.",
  'help_custom_field_cleanup' => "If the custom field content is cleared (aka deleted), check this box to also remove data from the
                                  associated field in the database.",
  'help_manage_custom_flags' => "This page is used to maintain custom flags. Both the descriptive definition and the
                                 <strong>Enabled&nbsp;/&nbsp;Disabled</strong> setting for a flag can be updated. Note that
                                 the flag numbers are predefined. Therefore you can only update and enable/disable them but
                                 you cannont add them.",
  'help_reset_custom_flags_products' => "If selected, the associated flag in every product will be turned off. This is helpful
                                         if you disable a flag you have been using, or if you enable a flag that you previously
                                         used but are redefining it. Take care that you do not select this option unless you
                                         truly want to clear the flag in all product records.",
  'help_what_are_custom_flags' => "Custom Flags, available in Products only at this time, allow user defined
                                   <strong>true&nbsp;/&nbsp;false</strong> attributes to refine the associated item definition. 
                                   For example, a flag can be used to flag products that are for hourly time versus a straight
                                   dollar amount. The invoice template can be modified to treat print the number as an hourly
                                   value as well as income and other reports to reflect time on job, etc.",
  'no_custom_flags' => "No Custom Flags defined",
  'reset_custom_flags' => "Reset Associated Flag Field",
  'save_custom_flag_failure' => "Something went wrong, please try editing the custom flag again<br />",
  'save_custom_flag_success' => "Custom flag successfully saved, <br /> you will be redirected back to the Manage Custom Flags page",
  'what_are_custom_flags' => "What are Custom Flags"
);
// @formatter:on
$LANG = array_merge($LANG,$LCL_LANG);
