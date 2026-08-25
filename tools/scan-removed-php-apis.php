<?php
/**
 * Scan executable PHP tokens for calls to APIs removed before PHP 8.4.
 *
 * Unlike a regular-expression search, token_get_all() lets this scanner ignore
 * comments and string literals, avoiding false positives in documentation.
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$scan_paths = array(
    $root . '/wp-content/themes/the-cause',
    $root . '/wp-content/plugins',
);

$removed_functions = array_fill_keys(
    array(
        'create_function',
        'each',
        'split',
        'ereg',
        'ereg_replace',
        'get_magic_quotes_gpc',
        '__autoload',
    ),
    true
);

$name_token_ids = array(T_STRING);
foreach (array('T_NAME_FULLY_QUALIFIED', 'T_NAME_QUALIFIED', 'T_NAME_RELATIVE') as $constant_name) {
    if (defined($constant_name)) {
        $name_token_ids[] = constant($constant_name);
    }
}

$ignorable_token_ids = array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT);
$method_context_token_ids = array(T_FUNCTION, T_OBJECT_OPERATOR, T_DOUBLE_COLON);
if (defined('T_NULLSAFE_OBJECT_OPERATOR')) {
    $method_context_token_ids[] = constant('T_NULLSAFE_OBJECT_OPERATOR');
}

$violations = array();

foreach ($scan_paths as $scan_path) {
    if (!is_dir($scan_path)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($scan_path, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (!$file->isFile() || 'php' !== strtolower($file->getExtension())) {
            continue;
        }

        $file_path = $file->getPathname();
        $source = file_get_contents($file_path);
        if (false === $source) {
            fwrite(STDERR, "Unable to read {$file_path}.\n");
            exit(2);
        }

        $tokens = token_get_all($source);
        $token_count = count($tokens);

        for ($index = 0; $index < $token_count; ++$index) {
            $token = $tokens[$index];
            if (!is_array($token) || !in_array($token[0], $name_token_ids, true)) {
                continue;
            }

            $function_name = strtolower(ltrim($token[1], '\\'));
            if (str_contains($function_name, '\\')) {
                $name_parts = explode('\\', $function_name);
                $function_name = (string) end($name_parts);
            }

            $is_removed = isset($removed_functions[$function_name])
                || 1 === preg_match('/^(?:mysql|mcrypt)_[a-z_]+$/', $function_name);

            if (!$is_removed) {
                continue;
            }

            $previous = previous_significant_token($tokens, $index, $ignorable_token_ids);
            if (is_array($previous) && in_array($previous[0], $method_context_token_ids, true)) {
                continue;
            }

            $next = next_significant_token($tokens, $index, $ignorable_token_ids);
            if ('(' !== $next) {
                continue;
            }

            $relative_path = str_replace($root . '/', '', $file_path);
            $violations[] = sprintf('%s:%d: %s()', $relative_path, $token[2], $function_name);
        }
    }
}

if ($violations) {
    fwrite(STDERR, "Removed PHP API calls were found:\n");
    foreach ($violations as $violation) {
        fwrite(STDERR, " - {$violation}\n");
    }
    exit(1);
}

echo "No removed PHP API calls found.\n";

/**
 * Return the nearest meaningful token before the supplied index.
 *
 * @param array<int, array{0:int,1:string,2:int}|string> $tokens
 * @param array<int, int>                                $ignorable_ids
 * @return array{0:int,1:string,2:int}|string|null
 */
function previous_significant_token(array $tokens, int $index, array $ignorable_ids)
{
    for ($cursor = $index - 1; $cursor >= 0; --$cursor) {
        $candidate = $tokens[$cursor];
        if (is_array($candidate) && in_array($candidate[0], $ignorable_ids, true)) {
            continue;
        }

        return $candidate;
    }

    return null;
}

/**
 * Return the nearest meaningful token after the supplied index.
 *
 * @param array<int, array{0:int,1:string,2:int}|string> $tokens
 * @param array<int, int>                                $ignorable_ids
 * @return array{0:int,1:string,2:int}|string|null
 */
function next_significant_token(array $tokens, int $index, array $ignorable_ids)
{
    $token_count = count($tokens);
    for ($cursor = $index + 1; $cursor < $token_count; ++$cursor) {
        $candidate = $tokens[$cursor];
        if (is_array($candidate) && in_array($candidate[0], $ignorable_ids, true)) {
            continue;
        }

        return $candidate;
    }

    return null;
}
