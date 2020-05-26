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

    # [[ $CREATE_CONFIG == *"Success"* ]] && break
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