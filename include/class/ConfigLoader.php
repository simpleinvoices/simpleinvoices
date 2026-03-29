<?php

class ConfigData extends ArrayObject
{
    public function __construct(array $array = [])
    {
        parent::__construct($array, ArrayObject::ARRAY_AS_PROPS);
    }

    public static function fromArray(array $array): self
    {
        $converted = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $converted[$key] = self::fromArray($value);
            } else {
                $converted[$key] = $value;
            }
        }
        return new self($converted);
    }
}

class ConfigLoader
{
    public static function load(string $path, string $environment): ConfigData
    {
        $sections = self::parseIniFile($path);
        $default = array_key_first($sections);
        $target = $environment !== '' && isset($sections[$environment]) ? $environment : $default;
        $combined = self::resolveSection($sections, $target);
        return ConfigData::fromArray($combined);
    }

    private static function resolveSection(array $sections, string $name, array &$cache = []): array
    {
        if (isset($cache[$name])) {
            return $cache[$name];
        }
        if (!isset($sections[$name])) {
            return [];
        }
        $parent = $sections[$name]['parent'];
        $data = [];
        if ($parent !== null) {
            $data = self::resolveSection($sections, $parent, $cache);
        }
        $data = array_replace_recursive($data, $sections[$name]['values'] ?? []);
        return $cache[$name] = $data;
    }

    private static function parseIniFile(string $path): array
    {
        $raw = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $sections = [];
        $current = null;
        foreach ($raw as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, ';')) {
                continue;
            }
            if (preg_match('/^\[([^\]:]+)(?:\s*:\s*([^\]]+))?\]$/', $line, $matches)) {
                $current = trim($matches[1]);
                $parent = isset($matches[2]) ? trim($matches[2]) : null;
                $sections[$current] = ['parent' => $parent, 'buffer' => ''];
                continue;
            }
            if ($current === null) {
                continue;
            }
            $sections[$current]['buffer'] .= $line . "\n";
        }

        foreach ($sections as $name => $section) {
            $values = parse_ini_string($section['buffer'], false, INI_SCANNER_TYPED) ?: [];
            $nested = [];
            foreach ($values as $key => $value) {
                self::setNested($nested, $key, $value);
            }
            $sections[$name]['values'] = $nested;
            unset($sections[$name]['buffer']);
        }

        return $sections;
    }

    private static function setNested(array &$target, string $key, $value): void
    {
        $parts = explode('.', $key);
        $ref = &$target;
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }
            if (!isset($ref[$part]) || !is_array($ref[$part])) {
                $ref[$part] = [];
            }
            $ref = &$ref[$part];
        }
        $ref = $value;
    }
}
