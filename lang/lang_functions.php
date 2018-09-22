<?php
/**
 * Build array of defined languages
 * @return array
 */
function get_defined_langs()
{
    // The root path of the language files. Change if needed.
    $dir = '.';

    // Open a known directory and proceed to read its contents
    if (!is_dir($dir)) {
        exit("($dir) is not a directory.");
    }

    $langs = array();

    //	Implementation - Forward Compatible
    try {
        foreach (new RegexIterator(new DirectoryIterator($dir), '/^[a-z]{2}(_[A-Z]{2})?$/') as $entry) {
            $langs[] = $entry->getFilename();
        }
    } catch (UnexpectedValueException $e) {
        die($e->getMessage());
    }

    // Sort by lang code.
    sort($langs);

    return $langs;
}


/**
 * Access a language folder and return array with two values:
 *  1) The total strings
 *  2) The total translated strings.
 * @param string $lang_code
 * @return array
 */
function process_lang_file($lang_code)
{

    $lang_file = file("$lang_code/lang.php");

    $count = 0;
    $count_translated = 0;

    foreach ($lang_file as $line) {
        $line = rtrim($line);

        // A string line
        if (preg_match('/^\$LANG\[/', $line)) {
            $count++;
        }
        // Each LANG string in one line only,
        // Accommodate multi-line strings with strict line ending.
        if (preg_match('/^\$LANG\[.*;\s*\/\/\s*1/', $line)) {
            $count_translated++;
        }

    }

    $ret = array($count, $count_translated);
    return $ret;
}

