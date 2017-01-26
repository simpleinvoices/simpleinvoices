<?php
/**
 * General functions class
 * @author Rich Rowley
 */
class Funcs {
    /**
     * Break the <b>menu.tpl</b> into sections.
     * @param string $menutpl <b>menu.tpl</b> file contents.
     * @param array $lines Lines from <b>menu.tpl</b> broken by <i>&lt;!-- SECTION:</i> tag.
     * @param array $sections Associative array with the index of each <i>&lt;!-- SECTION:</i> tag name.
     *        Ex: <i>&lt;!-- SECTION:tax_rates&gt;</i> makes <b>tax_rates</b> the <i>key</i> and the <i>values</i> is the
     *            offset in the <b>$lines</b> array for the <b>tax_rates</b> section.
     */
    public static function menuSections($menutpl, &$lines, &$sections) {
        $divs = preg_split ('/(< *div *id=|< *div *class=)/', $menutpl, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $i = 0;
        $sections = array ();
        $lines = array ();
        foreach ($divs as $dsec) {
            $parts = preg_split ('/(<!-- *SECTION:)/', $dsec, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $hit_section = false;
            foreach ($parts as $part) {
                if ($hit_section) {
                    $sects = preg_split ('/ *-->/', $part, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
                    $sections[$sects[0]] = $i;
                    $hit_section = false;
                    $lines[] = $sects[1];
                    $i++;
                } else {
                    if (preg_match ('/^<!-- *SECTION:/', $part)) {
                        $hit_section = true;
                    } else {
                        $lines[] = $part;
                        $i++;
                    }
                }
            }
        }
    }

    /**
     * Merge extension sections with the main <b>menu.tpl<b> file.
     * @param array $ext_names Extension names.
     * @param array $lines Lines from <b>menu.tpl</b> broken by <i>&lt;!-- SECTION:</i> tag.
     * @param array $sections Associative array with the index of each <i>&lt;!-- SECTION:</i> tag name.
     * @return <b>menu.tpl</b> file content with active extension menus merged.
     */
    public static function mergeMenuSections($ext_names, $lines, $sections) {
        global $smarty;
        foreach ($ext_names as $ext_name) {
            if (file_exists ("extensions/$ext_name/templates/default/menu.tpl")) {
                $menu_extension = $smarty->fetch ("extensions/$ext_name/templates/default/menu.tpl");
                $ext_sects = preg_split ('/<!\-\- BEFORE:/', $menu_extension, -1, PREG_SPLIT_NO_EMPTY);
                foreach ($ext_sects as $sect) {
                    $parts = preg_split ('/ *-->/', $sect);
                    $sec_ndx = trim($parts[0]);
                    $pieces = preg_split('/^ *-->/', $parts[1]);
                    $ndx = $sections[$sec_ndx];
                    $lines[$ndx] = $pieces[0] . $lines[$ndx];
                }
            }
        }
        $menutpl = "";
        foreach ($lines as $line) {
            $menutpl .= $line;
        }
        return $menutpl;
    }
}
