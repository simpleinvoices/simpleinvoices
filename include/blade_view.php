<?php
/**
 * Blade view wrapper with assign(), display(), fetch() for compatibility with existing module code.
 * Template paths like "templates/default/header.blade.php" are resolved to the Blade
 * view name "templates.default.header".
 */

use Illuminate\Container\Container;
use Illuminate\View\ViewException;
use Jenssegers\Blade\Blade;
use Jenssegers\Blade\Container as BladeContainer;

class BladeView
{
    /** @var Blade */
    protected $blade;

    /** @var BladeContainer */
    protected $container;

    /** @var string */
    protected $cachePath;

    /** @var array */
    protected $assigns = [];

    /** @var array View path roots for resolving template names */
    protected $viewPaths = [];

    public function __construct(array $viewPaths, $cachePath)
    {
        $this->viewPaths = $viewPaths;
        $this->cachePath = rtrim($cachePath, '/');
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, true);
        }
        // Use a single container and set it as the global instance so that when
        // Illuminate's ViewServiceProvider::registerBladeEngine() resolves the
        // blade engine it gets the same container (via Container::getInstance())
        // that has 'blade.compiler' bound.
        $this->container = new BladeContainer();
        Container::setInstance($this->container);
        $this->blade = new Blade($viewPaths, $this->cachePath, $this->container);
        $this->registerDirectives();
    }

    /**
     * Store a variable for the next display/fetch.
     */
    public function assign($key, $value = null)
    {
        if (is_array($key)) {
            $this->assigns = array_merge($this->assigns, $key);
        } else {
            $this->assigns[$key] = $value;
        }
    }

    /**
     * Strip .blade.php (or legacy .tpl) suffix and convert path to Blade view name.
     * e.g. "templates/default/header.blade.php" -> "templates.default.header"
     */
    protected function pathToViewName($path)
    {
        $path = preg_replace('/\.(tpl|blade\.php)$/i', '', $path);
        $path = str_replace('\\', '/', $path);
        return str_replace('/', '.', trim($path, '/'));
    }

    /**
     * Resolve path to actual .blade.php file (check custom, then default).
     */
    protected function resolveBladePath($path)
    {
        $path = str_replace('\\', '/', ltrim($path, './'));
        $base = preg_replace('/\.(tpl|blade\.php)$/i', '', $path);
        $bladePath = $base . '.blade.php';
        foreach ($this->viewPaths as $root) {
            $root = rtrim($root, '/\\');
            $full = ($root === '.' || $root === '') ? $bladePath : $root . '/' . $bladePath;
            if (file_exists($full)) {
                return $full;
            }
        }
        return null;
    }

    /**
     * Get data array for Blade (module assigns only). Use helpers in blade_helpers.php
     * for request data, e.g. get('id'), post('name'), form_submitted().
     */
    protected function getData()
    {
        return $this->assigns;
    }

    /**
     * Render a template and output it.
     */
    public function display($path)
    {
        echo $this->fetch($path);
    }

    /**
     * Render a template and return the string.
     */
    public function fetch($path)
    {
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, './');
        $bladePath = $this->resolveBladePath($path);
        if (!$bladePath) {
            $viewName = $this->pathToViewName($path);
            $msg = "Blade template not found: {$viewName} (looked for .blade.php from path: {$path})";
            if (function_exists('simpleInvoicesError')) {
                trigger_error($msg, E_USER_WARNING);
            }
            return '<!-- ' . htmlspecialchars($msg) . ' -->';
        }
        $viewName = $this->pathToViewName($path);
        try {
            return $this->blade->render($viewName, $this->getData());
        } catch (ViewException $e) {
            // Stale or invalid compiled cache (e.g. after fixing a template). Clear and retry once.
            $prev = $e->getPrevious();
            if ($prev instanceof \ParseError) {
                foreach (glob($this->cachePath . '/*.php') ?: [] as $file) {
                    @unlink($file);
                }
                try {
                    return $this->blade->render($viewName, $this->getData());
                } catch (\Throwable $e2) {
                    throw $e2;
                }
            }
            throw $e;
        }
    }

    /**
     * Register Blade directives and precompilers.
     *
     * Legacy tag precompilers convert {merge_address ...}, {print_if_not_null ...},
     * {section}, {$var|modifier}) so existing templates work during migration. New templates
     * should use native Blade ({{ }}, @if, @foreach) and the pipe modifier only where needed.
     * Precompiler order matters: each transforms the template text before Blade compilation.
     */
    protected function registerDirectives()
    {
        $compiler = $this->blade->compiler();

        // Load Blade helpers (template helpers that return strings).
        $helpersPath = __DIR__ . '/blade_helpers.php';
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }
        // Invoice template plugin (online payment link) – still uses legacy name for compatibility.
        $defaultInvoicePlugins = __DIR__ . '/../templates/invoices/default/plugins';
        if (!function_exists('smarty_function_online_payment_link')) {
            $path = $defaultInvoicePlugins . '/function.online_payment_link.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }

        // Parse legacy tag params (name=value) into a PHP array string for blade_*(params).
        $parseSmartyTagParams = function ($inner) {
            $params = [];
            $pattern = '/(\w+)\s*=\s*("(?:[^"\\\\]|\\\\.)*"|\'(?:[^\'\\\\]|\\\\.)*\'|\$[^}\s]+|true|false)/';
            if (preg_match_all($pattern, $inner, $paramMatches, PREG_SET_ORDER)) {
                foreach ($paramMatches as $pm) {
                    $key = $pm[1];
                    $val = trim($pm[2]);
                    if ($val === '""' || $val === "''") {
                        $params[$key] = "''";
                    } else {
                        $params[$key] = $val;
                    }
                }
            }
            $arr = [];
            foreach ($params as $k => $v) {
                $arr[] = "'" . $k . "' => " . $v;
            }
            return '[' . implode(', ', $arr) . ']';
        };

        // --- PRECOMPILER: legacy {merge_address ...} → echo blade_merge_address() ---
        $compiler->precompiler(function ($template) use ($parseSmartyTagParams) {
            if (!function_exists('blade_merge_address')) {
                return $template;
            }
            return preg_replace_callback(
                '/\{merge_address\s+([^}]+)\}/',
                function ($m) use ($parseSmartyTagParams) {
                    $paramsPhp = $parseSmartyTagParams($m[1]);
                    return "<?php echo blade_merge_address({$paramsPhp}); ?>";
                },
                $template
            );
        });

        // --- PRECOMPILER: legacy {inv_itemised_cf ...} → echo blade_inv_itemised_cf() ---
        $compiler->precompiler(function ($template) use ($parseSmartyTagParams) {
            if (!function_exists('blade_inv_itemised_cf')) {
                return $template;
            }
            return preg_replace_callback(
                '/\{inv_itemised_cf\s+([^}]+)\}/',
                function ($m) use ($parseSmartyTagParams) {
                    $paramsPhp = $parseSmartyTagParams($m[1]);
                    return "<?php echo blade_inv_itemised_cf({$paramsPhp}); ?>";
                },
                $template
            );
        });

        // --- PRECOMPILER: legacy {print_if_not_null ...} → echo blade_print_if_not_null() ---
        $compiler->precompiler(function ($template) use ($parseSmartyTagParams) {
            if (!function_exists('blade_print_if_not_null')) {
                return $template;
            }
            return preg_replace_callback(
                '/\{print_if_not_null\s+([^}]+)\}/',
                function ($m) use ($parseSmartyTagParams) {
                    $paramsPhp = $parseSmartyTagParams($m[1]);
                    return "<?php echo blade_print_if_not_null({$paramsPhp}); ?>";
                },
                $template
            );
        });

        // --- PRECOMPILER: legacy {do_tr ...} → echo blade_do_tr() ---
        $compiler->precompiler(function ($template) use ($parseSmartyTagParams) {
            if (!function_exists('blade_do_tr')) {
                return $template;
            }
            return preg_replace_callback(
                '/\{do_tr\s+([^}]+)\}/',
                function ($m) use ($parseSmartyTagParams) {
                    $paramsPhp = $parseSmartyTagParams($m[1]);
                    return "<?php echo blade_do_tr({$paramsPhp}); ?>";
                },
                $template
            );
        });

        // --- PRECOMPILER: legacy {showCustomFields ...} → echo blade_show_custom_fields() ---
        $compiler->precompiler(function ($template) use ($parseSmartyTagParams) {
            if (!function_exists('blade_show_custom_fields')) {
                return $template;
            }
            return preg_replace_callback(
                '/\{showCustomFields\s+([^}]+)\}/',
                function ($m) use ($parseSmartyTagParams) {
                    $paramsPhp = $parseSmartyTagParams($m[1]);
                    return "<?php echo blade_show_custom_fields({$paramsPhp}); ?>";
                },
                $template
            );
        });

        // --- PRECOMPILER: legacy {online_payment_link ...} (invoice plugin) ---
        $compiler->precompiler(function ($template) use ($parseSmartyTagParams) {
            if (!function_exists('smarty_function_online_payment_link')) {
                return $template;
            }
            return preg_replace_callback(
                '/\{online_payment_link\s+([^}]+)\}/s',
                function ($m) use ($parseSmartyTagParams) {
                    $paramsPhp = $parseSmartyTagParams($m[1]);
                    return "<?php smarty_function_online_payment_link({$paramsPhp}, null); ?>";
                },
                $template
            );
        });

        // --- PRECOMPILER: legacy {html_options ...} → echo blade_html_options() ---
        // Params: name, options (assoc), or values+output, selected; optional class, id, etc. Value can be $var, "s", 's', digit, or word.
        $compiler->precompiler(function ($template) {
            if (!function_exists('blade_html_options')) {
                return $template;
            }
            return preg_replace_callback(
                '/\{html_options\s+([^}]+)\}/',
                function ($m) {
                    $inner = $m[1];
                    $params = [];
                    $pattern = '/(\w+)\s*=\s*("(?:[^"\\\\]|\\\\.)*"|\'(?:[^\'\\\\]|\\\\.)*\'|\$[^}\s]+|\d+|\w+)/';
                    if (preg_match_all($pattern, $inner, $paramMatches, PREG_SET_ORDER)) {
                        foreach ($paramMatches as $pm) {
                            $key = $pm[1];
                            $val = trim($pm[2]);
                            if ($val === '""' || $val === "''") {
                                $params[$key] = "''";
                            } elseif ($val[0] === '"' || $val[0] === "'" || $val[0] === '$') {
                                $params[$key] = $val;
                            } elseif (preg_match('/^\d+$/', $val)) {
                                $params[$key] = $val;
                            } elseif (preg_match('/^\w+$/', $val)) {
                                $params[$key] = "'" . $val . "'";
                            } else {
                                $params[$key] = $val;
                            }
                        }
                    }
                    $arr = [];
                    foreach ($params as $k => $v) {
                        $arr[] = "'" . $k . "' => " . $v;
                    }
                    $paramsPhp = '[' . implode(', ', $arr) . ']';
                    return "<?php echo blade_html_options({$paramsPhp}); ?>";
                },
                $template
            );
        });

        // Helper: convert Smarty dot-notation in a string to PHP bracket access.
        $dotToBracket = function ($template) {
            return preg_replace_callback(
                '/\$(\w+)((?:\.(?:\$?\w+))+)/',
                function ($m) {
                    $result = '$' . $m[1];
                    $parts = preg_split('/\./', $m[2], -1, PREG_SPLIT_NO_EMPTY);
                    foreach ($parts as $part) {
                        if ($part[0] === '$') {
                            $result .= "[{$part}]";
                        } elseif (ctype_digit($part)) {
                            $result .= "[{$part}]";
                        } else {
                            $result .= "['{$part}']";
                        }
                    }
                    return $result;
                },
                $template
            );
        };

        // --- PRECOMPILER 1: Smarty {section} → @foreach ---
        // Converts {section name=x loop=$var}...{/section} and rewrites body references
        // so that $var[x].prop becomes $x.prop (dots are converted by precompiler 3).
        $compiler->precompiler(function ($template) {
            // Collect all section definitions to rewrite body references
            preg_match_all(
                '/\{section\s+name=(\w+)\s+(?:start=\d+\s+)?loop=(\S+?)(?:\s+step=\d+)?\s*\}/',
                $template, $matches, PREG_SET_ORDER
            );
            foreach ($matches as $match) {
                $name = $match[1];
                $source = $match[2];
                // Replace $source[name] with $name throughout the template body.
                // The source may contain regex-special chars like $ and [, so escape it.
                $pattern = '/' . preg_quote($source, '/') . '\[' . preg_quote($name, '/') . '\]/';
                $template = preg_replace($pattern, '\$' . $name, $template);
            }
            // Convert {section} tags to @foreach
            $template = preg_replace_callback(
                '/\{section\s+name=(\w+)\s+(?:start=\d+\s+)?loop=(\S+?)(?:\s+step=\d+)?\s*\}/',
                function ($m) {
                    return "@foreach({$m[2]} as \${$m[1]}Key => \${$m[1]})";
                },
                $template
            );
            return str_replace('{/section}', '@endforeach', $template);
        });

        // --- PRECOMPILER 2: Smarty @foreach(from=...) → Blade @foreach ---
        // Converts @foreach(from=$source item=name) to @foreach($source as $name).
        // Handles from/item/key parameters in any order.
        $compiler->precompiler(function ($template) {
            return preg_replace_callback(
                '/@foreach\s*\(([^)]*(?:from|item)\s*=[^)]*)\)/',
                function ($m) {
                    $params = $m[1];
                    $from = $item = $key = '';
                    if (preg_match('/from\s*=\s*(\S+)/', $params, $fm)) $from = $fm[1];
                    if (preg_match('/item\s*=\s*(\w+)/', $params, $im)) $item = '$' . $im[1];
                    if (preg_match('/key\s*=\s*(\w+)/', $params, $km)) $key = '$' . $km[1];
                    if ($from && $item) {
                        if ($key) {
                            return "@foreach({$from} as {$key} => {$item})";
                        }
                        return "@foreach({$from} as {$item})";
                    }
                    return $m[0];
                },
                $template
            );
        });

        // --- PRECOMPILER 3: Smarty dot notation → PHP bracket access ---
        // Converts $var.key to $var['key'], $var.a.b to $var['a']['b'], $var.$key to $var[$key].
        $compiler->precompiler($dotToBracket);

        // --- PRECOMPILER 4: Standalone Smarty variables {$var} and {$var|modifier} → Blade output ---
        // Converts {$var} to {{ $var }}, {$var|htmlsafe} to {!! htmlsafe($var) !!}, etc.
        // Lookbehind/lookahead prevent matching Blade {{ }} and {!! !!}.
        $compiler->precompiler(function ($template) {
            return preg_replace_callback(
                '/(?<![{!])\{\s*(\$[^}|]+?)(?:\s*\|\s*(\w+)(?::([^}]*))?)?\s*\}(?![}!])/',
                function ($m) {
                    $expr = trim($m[1]);
                    if (!isset($m[2]) || $m[2] === '') {
                        return '{{ ' . $expr . ' }}';
                    }
                    $modifier = $m[2];
                    if ($modifier === 'htmlsafe') {
                        return '{{ ' . $expr . ' }}';
                    }
                    $fnMap = [
                        'urlsafe'                  => 'urlsafe(%s)',
                        'outhtml'                  => 'outhtml(%s)',
                        'siLocal_number'           => 'siLocal::number(%s)',
                        'siLocal_number_clean'     => 'siLocal::number_clean(%s)',
                        'siLocal_number_trim'      => 'siLocal::number_trim(%s)',
                        'siLocal_number_formatted' => 'siLocal::number(%s)',
                        'siLocal_date'             => 'siLocal::date(%s)',
                        'unescape'                 => 'outhtml(%s)',
                        'urlencode'                => 'urlencode(%s)',
                    ];
                    if (isset($fnMap[$modifier])) {
                        return '{!! ' . sprintf($fnMap[$modifier], $expr) . ' !!}';
                    }
                    if ($modifier === 'number_format') {
                        $decimals = isset($m[3]) ? trim($m[3]) : '2';
                        return '{!! number_format(' . $expr . ', ' . $decimals . ') !!}';
                    }
                    if ($modifier === 'truncate' && isset($m[3])) {
                        $args = array_map('trim', explode(':', $m[3]));
                        $len = $args[0] ?? 80;
                        $etc = $args[1] ?? '"..."';
                        return '{{ mb_strimwidth(' . $expr . ', 0, ' . $len . ', ' . $etc . ') }}';
                    }
                    return '{{ ' . $expr . ' }}';
                },
                $template
            );
        });

        // --- PRECOMPILER 5: Ensure word boundaries before Blade directives ---
        // Blade uses \B@ to match directives. When a directive like @endif immediately
        // follows a word character (e.g. "selected@endif"), \B won't match.
        $compiler->precompiler(function ($template) {
            return preg_replace(
                '/(\w)@(if|else|elseif|endif|foreach|endforeach|for|endfor|while|endwhile|unless|endunless|switch|endswitch|case|break|continue|isset|empty|php|endphp|verbatim|endverbatim)\b/',
                '$1 @$2',
                $template
            );
        });

        // --- PRECOMPILER 6: Pipe modifiers on PHP expressions anywhere in template ---
        // Converts $expr|modifier or $expr|modifier:args patterns to function calls.
        // Runs before Blade {{ }} pipe handling to catch modifiers in @if, @foreach, etc.
        $knownMods = [
            'urlsafe'          => 'urlsafe(%s)',
            'outhtml'          => 'outhtml(%s)',
            'siLocal_number'   => 'siLocal::number(%s)',
            'siLocal_number_clean' => 'siLocal::number_clean(%s)',
            'siLocal_number_trim'  => 'siLocal::number_trim(%s)',
            'siLocal_number_formatted' => 'siLocal::number(%s)',
            'siLocal_date'     => 'siLocal::date(%s)',
            'unescape'         => 'outhtml(%s)',
            'urlencode'        => 'urlencode(%s)',
            'nl2br'            => 'nl2br(%s)',
            'strip_tags'       => 'strip_tags(%s)',
            'count_characters' => 'strlen(%s)',
        ];
        $knownModNames = implode('|', array_keys($knownMods));
        $compiler->precompiler(function ($template) use ($knownMods, $knownModNames) {
            return preg_replace_callback(
                '/(\$\w+(?:\[[^\]]*\])*)\s*\|\s*(' . $knownModNames . ')\b(?::([^)}\s,]*))?/',
                function ($m) use ($knownMods) {
                    $expr = $m[1];
                    $mod = $m[2];
                    $args = isset($m[3]) ? trim($m[3]) : '';
                    if ($mod === 'htmlsafe') {
                        return $expr;
                    }
                    if (isset($knownMods[$mod])) {
                        return sprintf($knownMods[$mod], $expr);
                    }
                    return $m[0];
                },
                $template
            );
        });

        // Also handle number_format and truncate with colon args on raw expressions
        $compiler->precompiler(function ($template) {
            // $expr|number_format:N
            $template = preg_replace_callback(
                '/(\$\w+(?:\[[^\]]*\])*)\s*\|\s*number_format(?::(\d+))?/',
                function ($m) {
                    $decimals = isset($m[2]) ? $m[2] : '2';
                    return 'number_format(' . $m[1] . ', ' . $decimals . ')';
                },
                $template
            );
            // $expr|truncate:N:"...":true
            $template = preg_replace_callback(
                '/(\$\w+(?:\[[^\]]*\])*)\s*\|\s*truncate(?::([^)}\s|]*))?/',
                function ($m) {
                    if (isset($m[2]) && $m[2] !== '') {
                        $parts = array_map('trim', explode(':', $m[2]));
                        $len = $parts[0] ?? 80;
                        $etc = $parts[1] ?? '"..."';
                        return 'mb_strimwidth(' . $m[1] . ', 0, ' . $len . ', ' . $etc . ')';
                    }
                    return 'mb_strimwidth(' . $m[1] . ', 0, 80, "...")';
                },
                $template
            );
            return $template;
        });

        // --- PRECOMPILER 6: Pipe modifiers inside {{ }} → function calls ---
        // Handles both simple modifiers {{ $var | htmlsafe }} and parameterized {{ $var | truncate:80:"..." }}.
        $simpleMods = [
            'urlsafe'                  => 'urlsafe(%s)',
            'outhtml'                  => 'outhtml(%s)',
            'siLocal_number'           => 'siLocal::number(%s)',
            'siLocal_number_clean'     => 'siLocal::number_clean(%s)',
            'siLocal_number_trim'      => 'siLocal::number_trim(%s)',
            'siLocal_number_formatted' => 'siLocal::number(%s)',
            'siLocal_date'             => 'siLocal::date(%s)',
            'unescape'                 => 'outhtml(%s)',
            'urlencode'                => 'urlencode(%s)',
            'nl2br'                    => 'nl2br(%s)',
            'strip_tags'               => 'strip_tags(%s)',
            'count_characters'         => 'strlen(%s)',
        ];
        $compiler->precompiler(function ($template) use ($simpleMods) {
            return preg_replace_callback(
                '/\{\{\s*([^|}]+?)\s*\|\s*(\w+)((?::[^}]*?)?)\s*\}\}/',
                function ($m) use ($simpleMods) {
                    $expr = trim($m[1]);
                    $modifier = $m[2];
                    $args = isset($m[3]) ? trim($m[3], ':') : '';
                    if ($modifier === 'htmlsafe' && $args === '') {
                        return '{{ ' . $expr . ' }}';
                    }
                    if (isset($simpleMods[$modifier]) && $args === '') {
                        return '{!! ' . sprintf($simpleMods[$modifier], $expr) . ' !!}';
                    }
                    if ($modifier === 'number_format') {
                        $decimals = $args !== '' ? $args : '2';
                        return '{!! number_format(' . $expr . ', ' . $decimals . ') !!}';
                    }
                    if ($modifier === 'truncate') {
                        $parts = $args !== '' ? array_map('trim', explode(':', $args)) : ['80'];
                        $len = $parts[0] ?? 80;
                        $etc = $parts[1] ?? '"..."';
                        return '{{ mb_strimwidth(' . $expr . ', 0, ' . $len . ', ' . $etc . ') }}';
                    }
                    if ($modifier === 'count_characters') {
                        return '{!! strlen(' . $expr . ') !!}';
                    }
                    if (isset($simpleMods[$modifier])) {
                        return '{!! ' . sprintf($simpleMods[$modifier], $expr) . ' !!}';
                    }
                    return '{{ ' . $expr . ' }}';
                },
                $template
            );
        });

        $compiler->directive('urlsafe', function ($expression) {
            return "<?php echo urlsafe({$expression}); ?>";
        });
        $compiler->directive('outhtml', function ($expression) {
            return "<?php echo outhtml({$expression}); ?>";
        });
        $compiler->directive('siLocal_number', function ($expression) {
            return "<?php echo siLocal::number({$expression}); ?>";
        });
        $compiler->directive('siLocal_number_clean', function ($expression) {
            return "<?php echo siLocal::number_clean({$expression}); ?>";
        });
        $compiler->directive('siLocal_number_trim', function ($expression) {
            return "<?php echo siLocal::number_trim({$expression}); ?>";
        });
        $compiler->directive('siLocal_number_formatted', function ($expression) {
            return "<?php echo siLocal::number({$expression}); ?>";
        });
        $compiler->directive('siLocal_date', function ($expression) {
            return "<?php echo siLocal::date({$expression}); ?>";
        });
        $compiler->directive('showCustomFields', function ($expression) {
            return "<?php echo showCustomFieldsForBlade({$expression}); ?>";
        });
    }

    /** Legacy: plugins_dir for code that sets it (e.g. invoice template plugins path). */
    public $plugins_dir;
}
