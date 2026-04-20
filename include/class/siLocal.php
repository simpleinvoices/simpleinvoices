<?php
/* Class: wrapper class for zend locale*/
class siLocal
{
	/** Per-request override (e.g. invoice preference locale for PDF/export). */
	private static ?string $localeOverride = null;

	/**
	 * Override Intl formatting locale for the current request (e.g. from invoice preference).
	 * Pass null to use system default language from si_system_defaults.
	 */
	public static function setLocaleOverride(?string $locale): void
	{
		self::$localeOverride = ($locale !== null && $locale !== '') ? $locale : null;
	}

	private static function localeString($locale): string
	{
		if ($locale !== null && $locale !== '') {
			return (string) $locale;
		}
		if (self::$localeOverride !== null) {
			return self::$localeOverride;
		}
		if (function_exists('getDefaultLanguage')) {
			$lang = getDefaultLanguage();
			if ($lang !== null && $lang !== '') {
				return (string) $lang;
			}
		}
		return 'en_GB';
	}

	private static function getPrecision(): int
	{
		static $precision = null;
		if ($precision === null) {
			global $config;
			if (function_exists('getSystemDefaults')) {
				$defaults = getSystemDefaults();
				$precision = isset($defaults['precision']) ? (int) $defaults['precision'] : (int) ($config->local?->precision ?? 2);
			} else {
				$precision = (int) ($config->local?->precision ?? 2);
			}
		}
		return $precision;
	}

	/*Function: wrapper function using IntlNumberFormatter*/
	public static function number($number, $precision = "", $locale = "")
	{
		$locale = self::localeString($locale);
		$precision = $precision === "" ? self::getPrecision() : (int) $precision;

		$formatter = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
		$formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $precision);

		return $formatter->format((float) $number);
	}
	
    /*
    * Function: number_clean
    * Purpose: Remove trailing and leading zeros - just to return cleaner number in invoice creation from ajax product change
    */
    public static function number_clean($num){

        //remove zeros from end of number ie. 140.00000 becomes 140.
        $clean = rtrim($num, '0');
        //remove zeros from front of number ie. 0.33 becomes .33
        $clean = ltrim($clean, '0');
        //remove decimal point if an integer ie. 140. becomes 140
        $clean = rtrim($clean, '.');

        return $clean;
    }

	public static function number_trim($number)
	{
		$formatted_number = siLocal::number($number);
        $precision = self::getPrecision();
        $position = ($precision + 1) * -1;

        if (substr($formatted_number, $position, 1) === ".") {
		    $formatted_number = rtrim(trim($formatted_number, '0'), '.');
        }
        if (substr($formatted_number, $position, 1) === ",") {
            $formatted_number = rtrim(trim($formatted_number, '0'), ',');
        }	
        return $formatted_number;
	}
	
	/*Function: wrapper function for IntlDateFormatter*/
	public static function date($date, $length = "", $locale = "")
	{
		$locale = self::localeString($locale);

		try {
			$dateTime = $date instanceof \DateTimeInterface ? $date : new \DateTime($date);
		} catch (\Throwable $e) {
			return (string) $date;
		}

		$length = $length ?: 'medium';

		$style = match ($length) {
			'full' => \IntlDateFormatter::FULL,
			'long' => \IntlDateFormatter::LONG,
			'medium' => \IntlDateFormatter::MEDIUM,
			'short' => \IntlDateFormatter::SHORT,
			default => \IntlDateFormatter::SHORT,
		};

		$pattern = null;
		if ($length === 'month') {
			$pattern = 'LLLL';
		} elseif ($length === 'month_short') {
			$pattern = 'LLL';
		}

		if ($pattern !== null) {
			$formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, $dateTime->getTimezone()->getName(), \IntlDateFormatter::GREGORIAN, $pattern);
		} else {
			$formatter = new \IntlDateFormatter($locale, $style, \IntlDateFormatter::NONE, $dateTime->getTimezone()->getName());
		}

		return $formatter->format($dateTime);
	}


	/*
	 * Function: number_formatted
	 * Description: wrapper for php number_format
	 * Purpose: to format numbers for data entry fields - ie invoice edit/ajax where data is in 6 decimial places but only neex x places in edit view
	 */
	public static function number_formatted($number)
	{
		$number_formatted = number_format($number, self::getPrecision(), '.', '');
		return $number_formatted;
	}
}
