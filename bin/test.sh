#!/usr/bin/env bash

# =====
# Base variables
# =====
CURRENT_DIR="$(dirname $0)"

# =====
# Includes
# =====
source ${CURRENT_DIR}/common/colors.sh
source ${CURRENT_DIR}/common/functions.sh

# Run PHPCS
section_header "PHPCS"
docker-compose exec --user root php-fpm /bin/bash -c "cd wp-content && /var/www/html/wp-content/vendor/bin/phpcs --config-set installed_paths /var/www/html/wp-content/vendor/wp-coding-standards/wpcs"
docker-compose exec --user root php-fpm /bin/bash -c "cd wp-content && ./vendor/bin/phpcs"