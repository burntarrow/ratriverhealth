#!/usr/bin/env bash
set -euo pipefail

version="${1:-7.1}"
archive_url="https://wordpress.org/wordpress-${version}.tar.gz"
temp_dir="$(mktemp -d)"

cleanup() {
    rm -rf "$temp_dir"
}
trap cleanup EXIT

echo "Downloading WordPress ${version}..."
curl --fail --location --silent --show-error --retry 3 \
    "$archive_url" \
    --output "$temp_dir/wordpress.tar.gz"

tar -xzf "$temp_dir/wordpress.tar.gz" -C "$temp_dir"
source_dir="$temp_dir/wordpress"

package_version="$({
    php -r 'require $argv[1]; echo $wp_version;' "$source_dir/wp-includes/version.php"
} 2>/dev/null)"

if [[ "$package_version" != "$version" ]]; then
    echo "Expected WordPress ${version}, downloaded ${package_version:-unknown}." >&2
    exit 1
fi

# WordPress recommends replacing wp-admin and wp-includes completely during a
# manual core upgrade. wp-content and wp-config.php are deliberately preserved.
rm -rf wp-admin wp-includes
cp -a "$source_dir/wp-admin" ./wp-admin
cp -a "$source_dir/wp-includes" ./wp-includes

while IFS= read -r -d '' core_file; do
    cp "$core_file" "./$(basename "$core_file")"
done < <(find "$source_dir" -maxdepth 1 -type f -print0)

installed_version="$({
    php -r 'require "wp-includes/version.php"; echo $wp_version;'
} 2>/dev/null)"

if [[ "$installed_version" != "$version" ]]; then
    echo "Core replacement verification failed: found ${installed_version:-unknown}." >&2
    exit 1
fi

echo "WordPress core files updated to ${installed_version}."
