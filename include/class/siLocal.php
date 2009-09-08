<?php
/* Class: wrapper class for zend locale*/
class siLocal 
{
	/*Function: wrapper function for zend_locale_format::toNumber*/
	public static function number($number,$precision="",$locale="")
	{
		global $config;
		
		$locale == "" ? $locale = new Zend_Locale($config->local->locale) : $locale = $locale;
		$load_precision = $config->local->precision; 
		
		$precision == "" ? $precision = $load_precision : $precision = $precision;
		$formatted_number = Zend_Locale_Format::toNumber($number, array('precision' => $precision, 'locale' => $locale));
		
		//trim zeros from decimal point if enabled
		//if ($config->local->trim_zeros == "y") { $formatted_number = rtrim(trim($formatted_number, '0'), '.'); }
		
		return $formatted_number;
	}
	
	public static function number_trim($number)
	{
		$formatted_number = siLocal::number($number);
		$formatted_number = rtrim(trim($formatted_number, '0'), '.');
        $formatted_number = rtrim(trim($formatted_number, '0'), ','); /* Added to deal with "," */	
        return $formatted_number;
	}
	
	/*Function: wrapper function for zend_date*/
	public static function date($date,$length="",$locale="")
	{
		global $config;
		
		$locale == "" ? $locale = new Zend_Locale($config->local->locale) : $locale = $locale;
		$length == "" ? $length = "medium" : $lenght = $length;
		/*
		 * Length can be any of the Zend_Date lenghts - FULL, LONG, MEDIUM, SHORT
		 */

		$formatted_date = new Zend_Date($date,'yyyy-MM-dd');
		
		switch ($length) {
			case "full":
			    return $formatted_date->get(Zend_Date::DATE_FULL,$locale);
			    break;
			case "long":
			    return $formatted_date->get(Zend_Date::DATE_LONG,$locale);
			    break;
			case "medium":
			    return $formatted_date->get(Zend_Date::DATE_MEDIUM,$locale);
			    break;
			case "short":
			    return $formatted_date->get(Zend_Date::DATE_SHORT,$locale);
			    break;
			default:
				return $formatted_date->get(Zend_Date::DATE_SHORT,$locale);
		}
		
	}
	/*
	 * Function: number_formatted
	 * Description: wrapper for php number_format
	 * Purpose: to format numbers for data entry fields - ie invoice edit/ajax where data is in 6 decimial places but only neex x places in edit view
	 */
	public static function number_formatted($number)
	{
		global $config;
		$number_formatted = number_format($number, $config->local->precision, '.', '');
		return $number_formatted;
	}
}
