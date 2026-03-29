<?php

class LocaleHelper
{
    public static function getLocaleList(): array
    {
        if (class_exists('\ResourceBundle')) {
            try {
                $locales = ResourceBundle::getLocales('');
            } catch (\Throwable $e) {
                $locales = ResourceBundle::getLocales('root');
            }

            sort($locales, SORT_STRING);
            return array_values(array_unique($locales));
        }

        return ['en_US'];
    }
}
