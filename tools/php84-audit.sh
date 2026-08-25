#!/usr/bin/env bash
set -Eeuo pipefail
root=$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)
cd "$root"
php_bin=${PHP_BIN:-php}
report_dir=${REPORT_DIR:-build/php84}
mkdir -p "$report_dir"
"$php_bin" -v | tee "$report_dir/php-version.txt"
set -o pipefail
find . -type f -name '*.php' \
  -not -path './.git/*' \
  -not -path '*/node_modules/*' \
  -not -path '*/vendor/*' \
  -not -path '*/uploads/*' \
  -not -path '*/cache/*' \
  -print0 \
  | xargs -0 -n1 -P4 "$php_bin" -l \
  | tee "$report_dir/php-lint.txt"
legacy='\b(mysql_[a-z_]+|ereg(i)?|split|spliti|create_function|each|get_magic_quotes_gpc|set_magic_quotes_runtime)\s*\('
if command -v rg >/dev/null 2>&1; then
  rg -n --glob '*.php' --glob '!vendor/**' --glob '!node_modules/**' \
    --glob '!uploads/**' --glob '!cache/**' "$legacy" . \
    > "$report_dir/legacy-api-hits.txt" || true
else
  grep -REn --include='*.php' --exclude-dir=.git --exclude-dir=vendor \
    --exclude-dir=node_modules --exclude-dir=uploads --exclude-dir=cache \
    "$legacy" . > "$report_dir/legacy-api-hits.txt" || true
fi
printf 'Reports written to %s\n' "$report_dir"
