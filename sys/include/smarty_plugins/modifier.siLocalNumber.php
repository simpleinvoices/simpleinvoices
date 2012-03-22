<?php
function smarty_modifier_siLocalNumber($number,$precision="",$locale="") {
    $config = Zend_Registry::get('config');
    
    $locale == "" ? $locale = new Zend_Locale($config->local->locale) : $locale = $locale;
    $load_precision = $config->local->precision; 
        
    $precision == "" ? $precision = $load_precision : $precision = $precision;
    $formatted_number = Zend_Locale_Format::toNumber($number, array('precision' => $precision, 'locale' => $locale));
        
    //trim zeros from decimal point if enabled
    //if ($config->local->trim_zeros == "y") { $formatted_number = rtrim(trim($formatted_number, '0'), '.'); }
        
    return $formatted_number;
}
?>
