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

# =====
# Check Dependencies
# =====
section_header "Dependency Check"
dependencies=( docker-compose node npm npx )
for dependency in "${dependencies[@]}"; do
  if command -v "${dependency}" >/dev/null 2>&1; then
    echo "Found ${dependency}"
  else
    echo "Missing dependency: ${dependency}"
    exit 1
  fi
done

# =====
# Start Docker and wait 60 seconds
# =====
section_header "Build Docker"
docker-compose up -d

# =====
# Download WordPress
# =====
section_header "Download WordPress"
docker-compose exec --user root php-fpm wp core download --force --allow-root

# =====
# Grant permissions
# =====
section_header "Grant Permissions"
docker-compose exec --user root php-fpm chmod +x -R /var/www/html/wp-content


# =====
# Create WordPress config file, if necessary
# =====
section_header "Create WordPress Config"
if [ ! -f wordpress/wp-config.php ]; then
  n=0
  until [ "$n" -ge 12 ]; do
    CREATE_CONFIG=$(docker-compose exec --user root php-fpm wp config create \
      --dbhost="db:3306" \
      --dbname=wordpress \
      --dbuser=wordpress \
      --dbpass=wordpress \
      --force \
      --allow-root) && break

    echo "Database not created yet. Sleeping 5 seconds..."
    sleep 5
  done
fi

# =====
# Install WordPress
# =====
section_header "Install WordPress"
docker-compose exec --user root php-fpm wp core install \
  --url=localhost \
  --title="Change Me" \
  --admin_user="wordpress" \
  --admin_email="user@domain.com" \
  --admin_password="wordpress" \
  --allow-root

# =====
# Run Composer
# =====
section_header "Run Composer"
docker-compose exec --user root php-fpm /bin/bash -c "cd wp-content && composer install"

# =====
# Remove default plugins.
# =====
section_header "Remove Default Plugins"
docker-compose exec --user root php-fpm wp plugin delete hello akismet --allow-root

# =====
# Enable Composer-installed plugins
# =====
section_header "Enable Composer-installed Plugins"
docker-compose exec --user root php-fpm wp plugin activate --all --allow-root

# =====
# Turn on debugging.
# =====
section_header "Enable Debugging"
docker-compose exec --user root php-fpm wp config set WP_DEBUG true --raw --type="constant" --allow-root
docker-compose exec --user root php-fpm wp config set WP_DEBUG_LOG true --raw --type="constant" --allow-root

# =====
# Set Rewrite Structure
# =====
section_header "Set Rewrite Structure"
docker-compose exec --user root php-fpm wp rewrite structure "/%postname%/" --hard --allow-root

# =====
# Switch Theme
# =====
section_header "Switch Theme"
docker-compose exec --user root php-fpm wp theme activate micronaut --allow-root

# =====
# Delete Other Themes
# =====
section_header "Delete Other Themes"
DELETE_OUTPUT=$(cd ./content/themes/ && rm -rf twenty*)

# =====
# Add Movies
# =====
section_header "Add Movies"
docker-compose exec --user root php-fpm wp movies import --file="imdb-50-top-rated-movies.csv" --allow-root
