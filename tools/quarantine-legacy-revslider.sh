#!/usr/bin/env bash
set -euo pipefail

plugin_dir="wp-content/plugins/revslider"

rm -rf "$plugin_dir"
mkdir -p "$plugin_dir"

cat > "$plugin_dir/revslider.php" <<'PHP'
<?php
/**
 * Plugin Name: Slider Revolution Legacy Compatibility Guard
 * Description: Prevents the bundled Slider Revolution 4.5.95 code from causing fatal errors on PHP 8.4. Install a current licensed Slider Revolution package to restore slider output.
 * Version: 1.0.0
 * Requires at least: 6.7
 * Requires PHP: 8.1
 * Network: true
 *
 * @package RatRiverHealth
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Preserve the legacy theme API while the incompatible commercial plugin is
 * quarantined. The original function printed slider markup directly.
 *
 * @param string $alias  Slider alias requested by the theme.
 * @param string $put_in Legacy placement argument.
 * @return void
 */
function putRevSlider($alias, $put_in = ''): void
{
    unset($put_in);

    if (current_user_can('manage_options') && !is_admin()) {
        printf(
            '<!-- Slider Revolution "%s" is disabled until a current licensed version is installed. -->',
            esc_html((string) $alias)
        );
    }
}

/**
 * Preserve the common Slider Revolution shortcode without executing the
 * incompatible legacy package.
 *
 * @param array<int|string, mixed>|string $attributes Shortcode attributes.
 * @return string
 */
function rrh_revslider_compat_shortcode($attributes = array()): string
{
    unset($attributes);
    return '';
}
add_shortcode('rev_slider', 'rrh_revslider_compat_shortcode');

/**
 * Explain the deliberate compatibility guard to site administrators.
 */
function rrh_revslider_compat_admin_notice(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    echo '<div class="notice notice-warning"><p>';
    echo wp_kses_post(
        __(
            '<strong>Slider Revolution is temporarily disabled.</strong> The repository contained version 4.5.95, which is not compatible with PHP 8.4. Install a current licensed Slider Revolution package to restore the sliders; the existing slider data remains in the WordPress database.',
            'ratriverhealth'
        )
    );
    echo '</p></div>';
}
add_action('admin_notices', 'rrh_revslider_compat_admin_notice');
add_action('network_admin_notices', 'rrh_revslider_compat_admin_notice');
PHP

php -l "$plugin_dir/revslider.php"
echo 'Legacy Slider Revolution 4.5.95 has been replaced with a PHP 8.4-safe compatibility guard.'
