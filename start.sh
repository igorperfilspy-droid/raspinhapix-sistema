#!/usr/bin/env sh
set -eu

# Mostra PORT só para log
echo "Starting PHP dev server on 0.0.0.0:${PORT}"

# Inicia e mantém em foreground (não pode sair)
exec php -S 0.0.0.0:${PORT} router.php
