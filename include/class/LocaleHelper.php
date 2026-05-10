<?php

class LocaleHelper
{
    private static ?array $localeMap = null;

    /**
     * Return a map of locale_code => display_label sorted by display label.
     * Label format: "Language (Region) (xx_XX)" e.g. "English (Australia) (en_AU)"
     */
    public static function getLocaleList(): array
    {
        if (self::$localeMap !== null) {
            return self::$localeMap;
        }

        $codes = array_keys(self::hardcodedMap());

        $map = [];
        foreach ($codes as $code) {
            $label = self::displayLabel($code);
            $map[$code] = $label;
        }

        // Sort by display label, case-insensitive
        uasort($map, function ($a, $b) {
            return strcasecmp($a, $b);
        });

        return self::$localeMap = $map;
    }

    private static function displayLabel(string $code): string
    {
        if (extension_loaded('intl')) {
            try {
                $language = \Locale::getDisplayLanguage($code, $code);
                $region   = \Locale::getDisplayRegion($code, $code);
                if ($language !== $code && $region !== '') {
                    return "$language ($region) ($code)";
                }
                if ($language !== $code) {
                    return "$language ($code)";
                }
            } catch (\Throwable $e) {
                // Fall through to hardcoded
            }
        }

        return self::hardcodedMap()[$code] ?? $code;
    }

    private static function hardcodedMap(): array
    {
        return [
            'af_ZA' => 'Afrikaans (South Africa) (af_ZA)',
            'ar_AE' => 'Arabic (UAE) (ar_AE)',
            'ar_EG' => 'Arabic (Egypt) (ar_EG)',
            'ar_SA' => 'Arabic (Saudi Arabia) (ar_SA)',
            'bg_BG' => 'Bulgarian (Bulgaria) (bg_BG)',
            'ca_ES' => 'Catalan (Spain) (ca_ES)',
            'cs_CZ' => 'Czech (Czechia) (cs_CZ)',
            'da_DK' => 'Danish (Denmark) (da_DK)',
            'de_AT' => 'German (Austria) (de_AT)',
            'de_CH' => 'German (Switzerland) (de_CH)',
            'de_DE' => 'German (Germany) (de_DE)',
            'el_GR' => 'Greek (Greece) (el_GR)',
            'en_AU' => 'English (Australia) (en_AU)',
            'en_CA' => 'English (Canada) (en_CA)',
            'en_GB' => 'English (UK) (en_GB)',
            'en_IE' => 'English (Ireland) (en_IE)',
            'en_IN' => 'English (India) (en_IN)',
            'en_NZ' => 'English (New Zealand) (en_NZ)',
            'en_US' => 'English (US) (en_US)',
            'en_ZA' => 'English (South Africa) (en_ZA)',
            'es_AR' => 'Spanish (Argentina) (es_AR)',
            'es_CL' => 'Spanish (Chile) (es_CL)',
            'es_CO' => 'Spanish (Colombia) (es_CO)',
            'es_ES' => 'Spanish (Spain) (es_ES)',
            'es_MX' => 'Spanish (Mexico) (es_MX)',
            'es_PE' => 'Spanish (Peru) (es_PE)',
            'et_EE' => 'Estonian (Estonia) (et_EE)',
            'eu_ES' => 'Basque (Spain) (eu_ES)',
            'fi_FI' => 'Finnish (Finland) (fi_FI)',
            'fr_BE' => 'French (Belgium) (fr_BE)',
            'fr_CA' => 'French (Canada) (fr_CA)',
            'fr_CH' => 'French (Switzerland) (fr_CH)',
            'fr_FR' => 'French (France) (fr_FR)',
            'gl_ES' => 'Galician (Spain) (gl_ES)',
            'he_IL' => 'Hebrew (Israel) (he_IL)',
            'hi_IN' => 'Hindi (India) (hi_IN)',
            'hr_HR' => 'Croatian (Croatia) (hr_HR)',
            'hu_HU' => 'Hungarian (Hungary) (hu_HU)',
            'id_ID' => 'Indonesian (Indonesia) (id_ID)',
            'it_CH' => 'Italian (Switzerland) (it_CH)',
            'it_IT' => 'Italian (Italy) (it_IT)',
            'ja_JP' => 'Japanese (Japan) (ja_JP)',
            'ko_KR' => 'Korean (South Korea) (ko_KR)',
            'lv_LV' => 'Latvian (Latvia) (lv_LV)',
            'mt_MT' => 'Maltese (Malta) (mt_MT)',
            'nb_NO' => 'Norwegian Bokmål (Norway) (nb_NO)',
            'nl_BE' => 'Dutch (Belgium) (nl_BE)',
            'nl_NL' => 'Dutch (Netherlands) (nl_NL)',
            'oc_FR' => 'Occitan (France) (oc_FR)',
            'pl_PL' => 'Polish (Poland) (pl_PL)',
            'pt_BR' => 'Portuguese (Brazil) (pt_BR)',
            'pt_PT' => 'Portuguese (Portugal) (pt_PT)',
            'ro_RO' => 'Romanian (Romania) (ro_RO)',
            'ru_RU' => 'Russian (Russia) (ru_RU)',
            'sk_SK' => 'Slovak (Slovakia) (sk_SK)',
            'sl_SI' => 'Slovenian (Slovenia) (sl_SI)',
            'sq_AL' => 'Albanian (Albania) (sq_AL)',
            'sr_Latn_RS' => 'Serbian (Serbia) (sr_Latn_RS)',
            'sr_RS' => 'Serbian (Serbia) (sr_RS)',
            'sv_SE' => 'Swedish (Sweden) (sv_SE)',
            'ta_IN' => 'Tamil (India) (ta_IN)',
            'th_TH' => 'Thai (Thailand) (th_TH)',
            'tr_TR' => 'Turkish (Turkey) (tr_TR)',
            'uk_UA' => 'Ukrainian (Ukraine) (uk_UA)',
            'vi_VN' => 'Vietnamese (Vietnam) (vi_VN)',
            'zh_CN' => 'Chinese (China) (zh_CN)',
            'zh_HK' => 'Chinese (Hong Kong) (zh_HK)',
            'zh_TW' => 'Chinese (Taiwan) (zh_TW)',
        ];
    }
}
