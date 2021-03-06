#!/bin/bash

set -e

ROOT=$(cd "$(dirname "${BASH_SOURCE[0]}")" && cd .. && pwd)
LIVE=0

OPT_COMPOSER=""

RED='\e[0;31m'
GREEN='\e[0;32m'
YELLOW='\e[0;33m'
NC='\e[0m' # No Color

# SETUP AND SANITY CHECKS
# =======================
while getopts l OPTION 2>/dev/null; do
  case $OPTION
  in
    l) LIVE=1;;
  esac
done

if [ $LIVE -eq 1 ]; then
  OPT_COMPOSER="--no-dev --optimize-autoloader"
fi

echo -e "${GREEN}Installing Composer dependencies...${NC}"
composer install --no-interaction $OPT_COMPOSER
