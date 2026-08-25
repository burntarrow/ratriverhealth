#!/usr/bin/env bash
set -euo pipefail

plugin_root="wp-content/plugins"
temp_root="$(mktemp -d)"

cleanup() {
    rm -rf "$temp_root"
}
trap cleanup EXIT

update_plugin() {
    local slug="$1"
    local version="$2"
    local target_folder="${3:-$slug}"
    local zip_file="$temp_root/${slug}-${version}.zip"
    local extract_dir="$temp_root/${slug}-${version}"
    local download_url="https://downloads.wordpress.org/plugin/${slug}.${version}.zip"

    echo "Updating ${slug} to ${version}..."
    curl --fail --location --silent --show-error --retry 3 \
        "$download_url" \
        --output "$zip_file"

    mkdir -p "$extract_dir"
    unzip -q "$zip_file" -d "$extract_dir"

    if [[ ! -d "$extract_dir/$slug" ]]; then
        echo "Downloaded archive for ${slug} did not contain the expected folder." >&2
        exit 1
    fi

    rm -rf "$plugin_root/$target_folder"
    mv "$extract_dir/$slug" "$plugin_root/$target_folder"
}

update_plugin better-search-replace 1.4.11
update_plugin polylang 3.8.7
update_plugin soliloquy-lite 2.8.4
update_plugin tinymce-advanced 5.9.2

echo 'Public plugins updated successfully.'
