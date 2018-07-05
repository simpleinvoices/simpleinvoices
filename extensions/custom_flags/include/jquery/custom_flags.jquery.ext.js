/**
 * Enable/Disable the clear data check box based on Custom Field Label change.
 */
$("#cf_custom_label_maint").livequery('change',function () {
    if($(this).val() == "")
       $('#clear_data_option').removeAttr('disabled', 'disabled');
    else
       $('#clear_data_option').attr('disabled', 'disabled');  
 });
