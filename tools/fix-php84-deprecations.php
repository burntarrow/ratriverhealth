<?php
/**
 * Apply narrowly scoped PHP 8.4 compile-time deprecation fixes to legacy
 * Rat River Health theme code.
 */

declare(strict_types=1);

$root = dirname(__DIR__);

$replacements = array(
    $root . '/wp-content/themes/the-cause/includes/options/f01_display.php' => array(
        'function tbOptionSelect($optionID, $optionName, $optionDesc, $optionSubType, $optionDir = "", $optionValue, $optionStd) {'
            => 'function tbOptionSelect($optionID, $optionName, $optionDesc, $optionSubType, $optionDir, $optionValue, $optionStd) {',
    ),
);

$updated = 0;

foreach ($replacements as $file => $file_replacements) {
    $contents = file_get_contents($file);
    if (false === $contents) {
        fwrite(STDERR, "Unable to read {$file}.\n");
        exit(1);
    }

    $original = $contents;

    foreach ($file_replacements as $search => $replacement) {
        if (!str_contains($contents, $search)) {
            if (str_contains($contents, $replacement)) {
                continue;
            }

            fwrite(STDERR, "Expected legacy code was not found in {$file}.\n");
            exit(1);
        }

        $contents = str_replace($search, $replacement, $contents, $count);
        if (1 !== $count) {
            fwrite(STDERR, "Expected exactly one replacement in {$file}; found {$count}.\n");
            exit(1);
        }
    }

    if ($contents === $original) {
        continue;
    }

    if (false === file_put_contents($file, $contents)) {
        fwrite(STDERR, "Unable to update {$file}.\n");
        exit(1);
    }

    ++$updated;
    echo 'Updated ' . str_replace($root . '/', '', $file) . "\n";
}

echo "Updated {$updated} files.\n";
