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
# Shut Docker Down
# =====
section_header "Shut Down Docker"
docker-compose down -v

# =====
# Remove Built Files
# =====
section_header "Remove Built Files"
rm -f content/debug.log
rm -rf content/vendor
rm -rf data
rm -rf wordpress

# =====
# Done
# =====
section_header "🎉 Done 🎉"