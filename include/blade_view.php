<?php
/**
 * Smarty-compatible wrapper for Laravel Blade.
 * Provides assign(), display(), fetch() so existing $smarty->assign/display/fetch code keeps working.
 * Template paths like "templates/default/header.tpl" are resolved to Blade views
 * "templates.default.header" (file: templates/default/header.blade.php).
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

    /** @var array View path roots (same order as Smarty template_dir) */
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
     * Convert a .tpl or .blade.php path to Blade view name.
     * e.g. "templates/default/header.tpl" -> "templates.default.header"
     */
    protected function pathToViewName($path)
    {
        $path = preg_replace('/\.(tpl|blade\.php)$/i', '', $path);
        $path = str_replace('\\', '/', $path);
        return str_replace('/', '.', trim($path, '/'));
    }

    /**
     * Resolve path to actual .blade.php file (check custom, then default, then extensions).
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
     * Get data array for Blade (assigns + smarty compat).
     */
    protected function getData()
    {
        global $auth_session;
        $sessionCopy = [];
        if (isset($auth_session) && is_object($auth_session)) {
            $sessionCopy = method_exists($auth_session, 'getArrayCopy') ? $auth_session->getArrayCopy() : get_object_vars($auth_session);
        }
        $zendAuth = isset($_SESSION['Zend_Auth']) ? (array)$_SESSION['Zend_Auth'] : $sessionCopy;
        // ArrayObject with ARRAY_AS_PROPS supports both $smarty->get->key and $smarty['get']['key']
        $f = ArrayObject::ARRAY_AS_PROPS;
        $smartyCompat = new \ArrayObject([
            'session' => new \ArrayObject(['Zend_Auth' => new \ArrayObject($zendAuth, $f)], $f),
            'get'     => new \ArrayObject($_GET, $f),
            'post'    => new \ArrayObject($_POST ?? [], $f),
            'capture' => new \ArrayObject([], $f),
        ], $f);
        return array_merge($this->assigns, ['smarty' => $smartyCompat]);
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
     * Register Blade directives and precompilers for Smarty-to-Blade compatibility.
     * Precompiler order matters: each transforms the template text before Blade compilation.
     */
    protected function registerDirectives()
    {
        $compiler = $this->blade->compiler();

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
                    $fnMap = [
                        'urlsafe'                  => 'urlsafe(%s)',
                        'htmlsafe'                 => 'htmlsafe(%s)',
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
                        return '{!! htmlsafe(mb_strimwidth(' . $expr . ', 0, ' . $len . ', ' . $etc . ')) !!}';
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
            'htmlsafe'         => 'htmlsafe(%s)',
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
            'htmlsafe'                 => 'htmlsafe(%s)',
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
                        return '{!! htmlsafe(mb_strimwidth(' . $expr . ', 0, ' . $len . ', ' . $etc . ')) !!}';
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

        $compiler->directive('htmlsafe', function ($expression) {
            return "<?php echo htmlsafe({$expression}); ?>";
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

    /** Smarty compatibility: expose plugins_dir for code that sets it (e.g. export.php) */
    public $plugins_dir;
}
