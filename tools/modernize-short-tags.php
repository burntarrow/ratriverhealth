<?php
/**
 * Replace legacy PHP short open tags in site-owned PHP files.
 *
 * `<?=` remains valid on PHP 8.4 and XML declarations are excluded. The script
 * intentionally targets the custom theme and custom plugin only; third-party
 * packages should be upgraded from their publishers instead of rewritten.
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$paths = array(
    $root . '/wp-content/themes/the-cause',
    $root . '/wp-content/plugins/rat_river_widgets',
);

$updated = 0;

foreach ($paths as $path) {
    if (!is_dir($path)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (!$file->isFile() || 'php' !== strtolower($file->getExtension())) {
            continue;
        }

        $file_path = $file->getPathname();
        $contents = file_get_contents($file_path);
        if (false === $contents) {
            fwrite(STDERR, "Unable to read {$file_path}.\n");
            exit(1);
        }

        $modernized = preg_replace('/<\?(?!php\b|=|xml\b)/i', '<?php ', $contents);
        if (null === $modernized) {
            fwrite(STDERR, "Unable to process {$file_path}.\n");
            exit(1);
        }

        if ($modernized === $contents) {
            continue;
        }

        if (false === file_put_contents($file_path, $modernized)) {
            fwrite(STDERR, "Unable to update {$file_path}.\n");
            exit(1);
        }

        ++$updated;
        echo 'Modernized ' . str_replace($root . '/', '', $file_path) . "\n";
    }
}

echo "Updated {$updated} files containing legacy short tags.\n";
