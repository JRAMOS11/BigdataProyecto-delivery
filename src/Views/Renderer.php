<?php

namespace Views;

/**
 * Minimal template engine.
 *
 * Template syntax:
 *   {{VAR}}              - output variable from current scope
 *   {{~VAR}}             - output variable from root (global) scope
 *   {{foreach LIST}}     - loop over an array
 *     {{this}}           - current item (scalar list)
 *     {{FIELD}}          - field of current item (associative list)
 *     {{~GLOBAL}}        - root-scope var inside loop
 *   {{endfor LIST}}
 *   {{if VAR}}           - render block if VAR is truthy
 *   {{endif VAR}}
 *   {{ifnot VAR}}        - render block if VAR is falsy
 *   {{endifnot VAR}}
 *   {{{page_content}}}   - layout injection point for the view body
 *   {{include path/to/partial}} - inline another .view.tpl file
 */
class Renderer
{
    private static string $viewsPath = 'src/Views/templates/';

    private function __construct() {}

    /**
     * Render a view inside a layout.
     *
     * @param string $view       Template name (without .view.tpl)
     * @param array  $data       View-specific data
     * @param string $layoutFile Layout template name (without .view.tpl)
     * @param bool   $render     Echo when true, return string when false
     */
    public static function render(
        string $view,
        array $data,
        string $layoutFile = 'layout',
        bool $render = true
    ): ?string {
        // Merge global context + session + local data (local wins)
        $global = \Utilities\Context::getContext();
        if (is_array($global)) {
            $data = array_merge($global, $data);
        }
        $data = array_merge($_SESSION ?? [], $data);

        // Allow controllers to override the layout via context
        $ctxLayout = $data['layoutFile'] ?? '';
        if ($ctxLayout !== '' && $layoutFile === 'layout') {
            $layoutFile = $ctxLayout;
        }

        // Strip .view.tpl suffix if already present, then re-add
        $layoutFile = self::stripSuffix($layoutFile);
        $view       = self::stripSuffix($view);

        $layoutPath = self::$viewsPath . $layoutFile . '.view.tpl';
        $viewPath   = self::$viewsPath . $view . '.view.tpl';

        if (!file_exists($layoutPath)) {
            http_response_code(500);
            die("Renderer: layout not found: $layoutPath");
        }
        if (!file_exists($viewPath)) {
            http_response_code(500);
            die("Renderer: view not found: $viewPath");
        }

        // Inject view body into layout
        $html = file_get_contents($layoutPath);
        $body = file_get_contents($viewPath);
        $html = str_replace('{{{page_content}}}', $body, $html);

        // Resolve {{include ...}} partials
        $html = self::loadPartials($html);

        // Strip whitespace outside <pre> blocks
        if (strpos($html, '<pre>') === false) {
            $html = preg_replace('/\s+/', ' ', $html);
        }

        // Parse & render
        $tokens = self::parse($html);
        $result = self::evaluate($tokens, $data, $data);

        if ($render) {
            echo $result;
            return null;
        }
        return $result;
    }

    // ---------------------------------------------------------------
    // Private helpers
    // ---------------------------------------------------------------

    private static function stripSuffix(string $name): string
    {
        return str_replace('.view.tpl', '', $name);
    }

    /** Replace {{include path/to/partial}} with the file contents. */
    private static function loadPartials(string $html): string
    {
        return preg_replace_callback(
            '/\{\{include\s+([\w\/]+)\}\}/',
            function (array $m): string {
                $path = self::$viewsPath . $m[1] . '.view.tpl';
                return file_exists($path) ? file_get_contents($path) : '';
            },
            $html
        );
    }

    /**
     * Split HTML into an array of literal strings and template tags.
     * Tags are anything that matches the control structures; everything
     * else is a literal node to be output after variable substitution.
     */
    private static function parse(string $html): array
    {
        $pattern = '/('
            . '\{\{\/?foreach\s[~&]?\w+\}\}'  // foreach / endfor
            . '|\{\{if(?:not)?\s[~&]?\w+\}\}' // if / ifnot
            . '|\{\{end(?:if(?:not)?|for)\s[~&]?\w+\}\}' // endif / endifnot / endfor
            . '|\{\{with\s[~&]?\w+\}\}'
            . '|\{\{endwith\s[~&]?\w+\}\}'
            . ')/';

        return preg_split($pattern, $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Walk the token array and produce the final HTML string.
     *
     * @param array $tokens
     * @param array $context Current scope data
     * @param array $root    Root (global) scope data – accessible via {{~VAR}}
     * @param array $parent  Parent scope – accessible via {{&VAR}} inside nested loops
     */
    private static function evaluate(array $tokens, array $context, array $root, array $parent = []): string
    {
        $out         = '';
        $i           = 0;
        $count       = count($tokens);

        while ($i < $count) {
            $token = $tokens[$i];

            // ---- foreach ----
            if (preg_match('/^\{\{foreach\s([~&]?\w+)\}\}$/', $token, $m)) {
                $key      = $m[1];
                $list     = self::resolve($key, $context, $root, $parent);
                $endTag   = '{{endfor ' . $key . '}}';

                // Collect all tokens until the matching endfor
                [$inner, $skip] = self::collectBlock($tokens, $i + 1, $endTag);
                $i += $skip + 1;

                if (is_array($list)) {
                    foreach ($list as $item) {
                        $itemCtx = is_array($item) ? array_merge($context, $item) : $context;
                        if (!is_array($item)) {
                            $itemCtx['this'] = $item;
                        }
                        $out .= self::evaluate($inner, $itemCtx, $root, $context);
                    }
                }
                continue;
            }

            // ---- if ----
            if (preg_match('/^\{\{if\s([~&]?\w+)\}\}$/', $token, $m)) {
                $key    = $m[1];
                $endTag = '{{endif ' . $key . '}}';
                [$inner, $skip] = self::collectBlock($tokens, $i + 1, $endTag);
                $i += $skip + 1;

                if (self::resolve($key, $context, $root, $parent)) {
                    $out .= self::evaluate($inner, $context, $root, $parent);
                }
                continue;
            }

            // ---- ifnot ----
            if (preg_match('/^\{\{ifnot\s([~&]?\w+)\}\}$/', $token, $m)) {
                $key    = $m[1];
                $endTag = '{{endifnot ' . $key . '}}';
                [$inner, $skip] = self::collectBlock($tokens, $i + 1, $endTag);
                $i += $skip + 1;

                if (!self::resolve($key, $context, $root, $parent)) {
                    $out .= self::evaluate($inner, $context, $root, $parent);
                }
                continue;
            }

            // ---- with ----
            if (preg_match('/^\{\{with\s([~&]?\w+)\}\}$/', $token, $m)) {
                $key    = $m[1];
                $endTag = '{{endwith ' . $key . '}}';
                [$inner, $skip] = self::collectBlock($tokens, $i + 1, $endTag);
                $i += $skip + 1;

                $sub = self::resolve($key, $context, $root, $parent);
                if (is_array($sub)) {
                    $out .= self::evaluate($inner, array_merge($context, $sub), $root, $context);
                }
                continue;
            }

            // ---- literal / variable substitution ----
            $out .= self::substituteVars($token, $context, $root, $parent);
            $i++;
        }

        return $out;
    }

    /**
     * Collect tokens between the current position and the matching close tag.
     * Returns [innerTokens, numberOfTokensConsumed].
     */
 private static function collectBlock(array $tokens, int $start, string $endTag): array
{
    $inner = [];
    $i     = $start;
    $count = count($tokens);

    while ($i < $count && trim($tokens[$i]) !== trim($endTag)) {
        $inner[] = $tokens[$i];
        $i++;
    }

    // +1 to also skip past the end tag itself
    return [$inner, ($i - $start) + 1];
}

    /**
     * Replace {{VAR}}, {{~VAR}}, {{&VAR}} in a literal string.
     */
    private static function substituteVars(string $text, array $context, array $root, array $parent): string
    {
        return preg_replace_callback(
            '/\{\{([~&]?\w+)\}\}/',
            function (array $m) use ($context, $root, $parent): string {
                return (string) self::resolve($m[1], $context, $root, $parent);
            },
            $text
        );
    }

    /**
     * Resolve a variable key against the correct scope.
     *   ~KEY  → root scope
     *   &KEY  → parent scope
     *   KEY   → current scope
     */
    private static function resolve(string $key, array $context, array $root, array $parent)
    {
        if (str_starts_with($key, '~')) {
            $k = ltrim($key, '~');
            return $root[$k] ?? '';
        }
        if (str_starts_with($key, '&')) {
            $k = ltrim($key, '&');
            return $parent[$k] ?? '';
        }
        return $context[$key] ?? '';
    }
}
