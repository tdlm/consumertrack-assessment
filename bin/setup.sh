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

section_header "Sleeping (zzz)"
sleep 60 #TODO: Replace with hearbeat check of some kind
