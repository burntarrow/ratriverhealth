<?php
/**
 * One-time modernization utility for the legacy The Cause widget classes.
 *
 * The original theme used PHP 4-style constructors and the deprecated
 * WP_Widget::WP_Widget() alias. Both patterns break or warn on current PHP and
 * WordPress versions. This utility preserves class names and widget ID bases.
 */

declare(strict_types=1);

$widget_directory = dirname(__DIR__) . '/wp-content/themes/the-cause/includes/widgets';
$widget_files     = glob($widget_directory . '/*.php');

if (false === $widget_files) {
    fwrite(STDERR, "Unable to enumerate theme widget files.\n");
    exit(1);
}

$updated_files = 0;

foreach ($widget_files as $widget_file) {
    $contents = file_get_contents($widget_file);
    if (false === $contents) {
        fwrite(STDERR, "Unable to read {$widget_file}.\n");
        exit(1);
    }

    $original = $contents;

    preg_match_all(
        '/class\s+([A-Za-z_][A-Za-z0-9_]*)\s+extends\s+WP_Widget\b/i',
        $contents,
        $class_matches
    );

    foreach ($class_matches[1] as $class_name) {
        $constructor_pattern = '/(?:public\s+|protected\s+|private\s+)?function\s+&?'
            . preg_quote($class_name, '/')
            . '\s*\(/i';

        $contents = preg_replace(
            $constructor_pattern,
            'public function __construct(',
            $contents,
            1
        ) ?? $contents;
    }

    $contents = preg_replace(
        '/\$this->WP_Widget\s*\(/',
        'parent::__construct(',
        $contents
    ) ?? $contents;

    $contents = preg_replace(
        '/register_widget\(\s*([\'\"])([A-Za-z_][A-Za-z0-9_]*)\1\s*\)/',
        'register_widget($2::class)',
        $contents
    ) ?? $contents;

    // Some files manually fired widgets_init from init. Register directly on
    // widgets_init instead, avoiding duplicate action execution.
    $contents = preg_replace(
        '/^\s*do_action\(\s*([\'\"])widgets_init\1\s*\);\s*$/m',
        '',
        $contents
    ) ?? $contents;

    $contents = preg_replace_callback(
        '/add_action\(\s*([\'\"])init\1\s*,\s*([\'\"])(tb_register_[A-Za-z0-9_]+)\2\s*(,\s*\d+)?\s*\);/i',
        static function (array $matches): string {
            $priority = $matches[4] ?? '';

            return "add_action('widgets_init', '" . $matches[3] . "'" . $priority . ');';
        },
        $contents
    ) ?? $contents;

    if ($contents === $original) {
        continue;
    }

    if (false === file_put_contents($widget_file, $contents)) {
        fwrite(STDERR, "Unable to update {$widget_file}.\n");
        exit(1);
    }

    ++$updated_files;
    echo 'Modernized ' . basename($widget_file) . "\n";
}

echo "Updated {$updated_files} widget files.\n";
