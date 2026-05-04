<?php
declare(strict_types=1);

$ROOT = realpath($argv[1] ?? __DIR__);
$DRY  = in_array('--dry', $argv, true);

$EXCLUDE_DIRS = [
    'vendor', 'node_modules', '.git', 'runtime', 'tools/test',
    'assets/static',
    'assets/cache',
    'assets/common/js/bootstrap',
    'assets/common/js/docsify',
    'assets/common/js/editor',
    'assets/common/js/layer',
    'assets/common/js/layui',
    'assets/common/js/masonry',
    'assets/common/js/table',
    'assets/admin/css/themes',
    'app/View/User/Common/Docsify',
    'config/language',
    'config/terms',
];

$EXCLUDE_FILES_BASENAMES = [
    '_.js', '_.css',
    '_layui_lite.min.js', '_vendor_lite.min.js',
    'authtree.js', 'hex.js', 'xm-select.js',
    'jquery.min.js', 'jquery.pjax.js', 'jquery.pjax.min.js',
    'jquery.qrcode.min.js', 'jquery.sliderBar.js', 'jquery.treegrid.min.js',
    'echarts.min.js', 'wangEditor.min.js',
    'bootstrap.min.css', 'select2.min.css', 'toastr.min.css', 'font.min.css',
    'codebase.app.min.js', 'codebase.min.css', 'admin.min.css', 'style.bundle.css',
    'oneui.app.min.js', 'oneui.min.css', 'index.min.css', 'user.min.css',
    'cloudflare-worker-html-cache.js',
];

$changed = [];
$skipped_minified = [];
$errors = [];

function rel(string $path, string $root): string
{
    return ltrim(substr($path, strlen($root)), '/\\');
}

function looks_minified(string $content): bool
{
    if ($content === '') return false;
    $lines = preg_split('/\R/', $content);
    if (count($lines) <= 3) {
        $maxLen = 0;
        foreach ($lines as $l) $maxLen = max($maxLen, strlen($l));
        if ($maxLen > 5000) return true;
    }
    $totalLen = strlen($content);
    $lineCount = max(1, count($lines));
    $avg = $totalLen / $lineCount;
    return $avg > 800;
}

function is_excluded(string $rel, array $dirs, array $files): bool
{
    $rel = str_replace('\\', '/', $rel);
    foreach ($dirs as $d) {
        if ($rel === $d || str_starts_with($rel, $d . '/')) return true;
    }
    $base = basename($rel);
    if (in_array($base, $files, true)) return true;
    if (preg_match('/\.min\.(js|css)$/i', $base)) return true;
    if (str_starts_with($base, '_clean_comments')) return true;
    return false;
}

function clean_php(string $code): string
{
    $tokens = @token_get_all($code);
    if ($tokens === false) return $code;
    $out = '';
    foreach ($tokens as $tok) {
        if (is_array($tok)) {
            [$id, $text] = $tok;
            if ($id === T_COMMENT || $id === T_DOC_COMMENT) {
                if (str_starts_with($text, '#!')) {
                    $out .= $text;
                    continue;
                }
                if (str_ends_with($text, "\n")) {
                    $out .= "\n";
                }
                continue;
            }
            if ($id === T_INLINE_HTML) {
                $out .= clean_html($text);
                continue;
            }
            $out .= $text;
        } else {
            $out .= $tok;
        }
    }
    return $out;
}

function clean_js(string $src): string
{
    $len = strlen($src);
    $out = '';
    $i = 0;
    $prevSig = '';
    $regexAllowedAfter = ['', '(', ',', '=', ':', '[', '!', '&', '|', '?', '{', '}', ';', '+', '-', '*', '~', '<', '>', '^', '%', "\n"];
    while ($i < $len) {
        $c = $src[$i];
        $next = $i + 1 < $len ? $src[$i + 1] : '';

        if ($c === '/' && $next === '/') {
            $j = $i + 2;
            while ($j < $len && $src[$j] !== "\n" && $src[$j] !== "\r") $j++;
            $i = $j;
            continue;
        }
        if ($c === '/' && $next === '*') {
            $j = $i + 2;
            while ($j < $len - 1 && !($src[$j] === '*' && $src[$j + 1] === '/')) $j++;
            $j = min($j + 2, $len);
            $chunk = substr($src, $i, $j - $i);
            $nl = substr_count($chunk, "\n");
            $out .= str_repeat("\n", $nl);
            $i = $j;
            continue;
        }
        if ($c === '"') {
            $j = $i + 1;
            while ($j < $len) {
                if ($src[$j] === '\\') { $j += 2; continue; }
                if ($src[$j] === '"') { $j++; break; }
                $j++;
            }
            $out .= substr($src, $i, $j - $i);
            $prevSig = '"';
            $i = $j;
            continue;
        }
        if ($c === "'") {
            $j = $i + 1;
            while ($j < $len) {
                if ($src[$j] === '\\') { $j += 2; continue; }
                if ($src[$j] === "'") { $j++; break; }
                $j++;
            }
            $out .= substr($src, $i, $j - $i);
            $prevSig = "'";
            $i = $j;
            continue;
        }
        if ($c === '`') {
            $j = $i + 1;
            $depth = 0;
            while ($j < $len) {
                if ($src[$j] === '\\') { $j += 2; continue; }
                if ($depth === 0 && $src[$j] === '`') { $j++; break; }
                if ($src[$j] === '$' && $j + 1 < $len && $src[$j + 1] === '{') {
                    $depth++;
                    $j += 2;
                    continue;
                }
                if ($depth > 0 && $src[$j] === '}') {
                    $depth--;
                    $j++;
                    continue;
                }
                $j++;
            }
            $out .= substr($src, $i, $j - $i);
            $prevSig = '`';
            $i = $j;
            continue;
        }
        if ($c === '/') {
            $isRegex = false;
            if ($prevSig === '' || in_array($prevSig, $regexAllowedAfter, true)) {
                $isRegex = true;
            } else {
                $tail = '';
                $k = strlen($out) - 1;
                while ($k >= 0 && ctype_alpha($out[$k])) { $tail = $out[$k] . $tail; $k--; }
                if (in_array($tail, ['return', 'typeof', 'in', 'of', 'new', 'void', 'delete', 'throw', 'instanceof', 'yield', 'await', 'case', 'do', 'else'], true)) {
                    $isRegex = true;
                }
            }
            if ($isRegex) {
                $j = $i + 1;
                $inClass = false;
                $closed = false;
                while ($j < $len) {
                    $ch = $src[$j];
                    if ($ch === '\\') { $j += 2; continue; }
                    if ($ch === '[') { $inClass = true; $j++; continue; }
                    if ($ch === ']') { $inClass = false; $j++; continue; }
                    if ($ch === '/' && !$inClass) { $j++; $closed = true; break; }
                    if ($ch === "\n") { break; }
                    $j++;
                }
                if ($closed) {
                    while ($j < $len && ctype_alpha($src[$j])) $j++;
                    $out .= substr($src, $i, $j - $i);
                    $prevSig = '/';
                    $i = $j;
                    continue;
                }
            }
            $out .= $c;
            $prevSig = '/';
            $i++;
            continue;
        }
        $out .= $c;
        if (!ctype_space($c)) $prevSig = $c;
        $i++;
    }
    return $out;
}

function clean_css(string $src): string
{
    $len = strlen($src);
    $out = '';
    $i = 0;
    while ($i < $len) {
        $c = $src[$i];
        $next = $i + 1 < $len ? $src[$i + 1] : '';
        if ($c === '/' && $next === '*') {
            $j = $i + 2;
            while ($j < $len - 1 && !($src[$j] === '*' && $src[$j + 1] === '/')) $j++;
            $j = min($j + 2, $len);
            $chunk = substr($src, $i, $j - $i);
            $nl = substr_count($chunk, "\n");
            $out .= str_repeat("\n", $nl);
            $i = $j;
            continue;
        }
        if ($c === '"' || $c === "'") {
            $q = $c;
            $j = $i + 1;
            while ($j < $len) {
                if ($src[$j] === '\\') { $j += 2; continue; }
                if ($src[$j] === $q) { $j++; break; }
                $j++;
            }
            $out .= substr($src, $i, $j - $i);
            $i = $j;
            continue;
        }
        $out .= $c;
        $i++;
    }
    return $out;
}

function clean_html(string $src): string
{
    $len = strlen($src);
    $out = '';
    $i = 0;
    while ($i < $len) {
        if (substr($src, $i, 4) === '<!--') {
            $end = strpos($src, '-->', $i + 4);
            if ($end === false) { $out .= substr($src, $i); break; }
            $chunk = substr($src, $i, $end + 3 - $i);
            $nl = substr_count($chunk, "\n");
            $out .= str_repeat("\n", $nl);
            $i = $end + 3;
            continue;
        }
        if (substr($src, $i, 2) === '{#') {
            $end = strpos($src, '#}', $i + 2);
            if ($end === false) { $out .= substr($src, $i); break; }
            $chunk = substr($src, $i, $end + 2 - $i);
            $nl = substr_count($chunk, "\n");
            $out .= str_repeat("\n", $nl);
            $i = $end + 2;
            continue;
        }
        if (preg_match('/\G<script\b[^>]*>/i', $src, $m, 0, $i)) {
            $isJs = true;
            if (preg_match('/\btype\s*=\s*"([^"]*)"/i', $m[0], $tm) || preg_match("/\btype\s*=\s*'([^']*)'/i", $m[0], $tm)) {
                $t = strtolower(trim($tm[1]));
                if ($t !== '' && $t !== 'text/javascript' && $t !== 'application/javascript' && $t !== 'module' && $t !== 'application/ecmascript') {
                    $isJs = false;
                }
            }
            $out .= $m[0];
            $i += strlen($m[0]);
            $end = stripos($src, '</script>', $i);
            if ($end === false) { $out .= substr($src, $i); break; }
            $body = substr($src, $i, $end - $i);
            $out .= $isJs ? clean_js($body) : $body;
            $out .= '</script>';
            $i = $end + 9;
            continue;
        }
        if (preg_match('/\G<style\b[^>]*>/i', $src, $m, 0, $i)) {
            $out .= $m[0];
            $i += strlen($m[0]);
            $end = stripos($src, '</style>', $i);
            if ($end === false) { $out .= substr($src, $i); break; }
            $body = substr($src, $i, $end - $i);
            $out .= clean_css($body);
            $out .= '</style>';
            $i = $end + 8;
            continue;
        }
        $out .= $src[$i];
        $i++;
    }
    return $out;
}

function collapse_blank_lines(string $src): string
{
    $eol = "\n";
    if (str_contains($src, "\r\n")) $eol = "\r\n";
    $tmp = str_replace(["\r\n", "\r"], "\n", $src);
    $tmp = preg_replace("/\n[ \t]*\n[ \t]*\n+/", "\n\n", $tmp);
    if ($eol !== "\n") $tmp = str_replace("\n", $eol, $tmp);
    return $tmp;
}

function process_file(string $path, string $root): array
{
    global $changed, $skipped_minified, $errors, $DRY;
    $rel = rel($path, $root);
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (!in_array($ext, ['php', 'js', 'css', 'html'], true)) return [false, ''];
    $orig = file_get_contents($path);
    if ($orig === false) return [false, 'read fail'];
    if ($orig === '') return [false, ''];

    if (in_array($ext, ['js', 'css'], true) && looks_minified($orig)) {
        $skipped_minified[] = $rel;
        return [false, 'minified'];
    }

    try {
        $cleaned = match ($ext) {
            'php'  => clean_php($orig),
            'js'   => clean_js($orig),
            'css'  => clean_css($orig),
            'html' => clean_html($orig),
        };
    } catch (\Throwable $e) {
        $errors[] = $rel . ': ' . $e->getMessage();
        return [false, 'err'];
    }

    $cleaned = collapse_blank_lines($cleaned);

    if ($cleaned === $orig) return [false, 'unchanged'];
    if (!$DRY) file_put_contents($path, $cleaned);
    $changed[] = $rel;
    return [true, ''];
}

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($ROOT, FilesystemIterator::SKIP_DOTS));
foreach ($rii as $file) {
    if (!$file->isFile()) continue;
    $path = $file->getPathname();
    $rel  = rel($path, $ROOT);
    if (is_excluded($rel, $EXCLUDE_DIRS, $EXCLUDE_FILES_BASENAMES)) continue;
    process_file($path, $ROOT);
}

echo "CHANGED=" . count($changed) . PHP_EOL;
echo "SKIPPED_MINIFIED=" . count($skipped_minified) . PHP_EOL;
echo "ERRORS=" . count($errors) . PHP_EOL;
file_put_contents($ROOT . '/_clean_changed.txt', implode("\n", $changed));
file_put_contents($ROOT . '/_clean_skipped_minified.txt', implode("\n", $skipped_minified));
file_put_contents($ROOT . '/_clean_errors.txt', implode("\n", $errors));
