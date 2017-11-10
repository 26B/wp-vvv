#!/bin/bash

## CONFIGURATION ##

DOMAIN=`get_primary_host "${VVV_SITE_NAME}".dev`
DOMAINS=`get_hosts "${DOMAIN}"`
SITE_TITLE=`get_config_value 'site_title' "${DOMAIN}"`
WP_TYPE=`get_config_value 'wp_type' 'single'`
DB_NAME=`get_config_value 'db_name' "${VVV_SITE_NAME}_dev"`
DB_NAME=${DB_NAME//[\\\/\.\<\>\:\"\'\|\?\!\*-]/}
PLUGINS=`get_config_value 'wp_plugins' ''`
THEME=`get_config_value 'wp_theme' ''`

echo -e "\n================================"
echo -e "${SITE_TITLE}"
echo -e "================================\n\n"

# Setup log files.
mkdir -p ${VVV_PATH_TO_SITE}/log
touch -c ${VVV_PATH_TO_SITE}/log/error.log
touch -c ${VVV_PATH_TO_SITE}/log/access.log

# Create a database, if we don't already have one.
echo -e "\nCreating database '${DB_NAME}' (if it doesn't exist)"
mysql -u root --password=root -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME}"
mysql -u root --password=root -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO wp@localhost IDENTIFIED BY 'wp';"
echo -e "\n DB operations done.\n\n"

# Configure WordPress.
if [[ ! -f "${VVV_PATH_TO_SITE}/wp-config-vvv.php" ]]; then
  export WP_TYPE
  export DOMAIN
  export DB_NAME
  export DB_USER=`get_config_value 'db_user' 'wp'`
  export DB_PASSWORD=`get_config_value 'db_password' 'wp'`
  export DB_HOST=`get_config_value 'db_host' 'localhost'`
  export DB_CHARSET=`get_config_value 'db_charset' 'utf8mb4'`
  export DB_COLLATION=`get_config_value 'db_collate' 'utf8mb4_general_ci'`
  export TABLE_PREFIX=`get_config_value 'table_prefix' 'wp_'`

  noroot ./vvv-wp-config.sh

  noroot mv wp-config-vvv.php ${VVV_PATH_TO_SITE}/wp-config-vvv.php
fi

# Move to base folder.
cd ${VVV_PATH_TO_SITE}

# Build project dependencies.
echo -e "\nBuilding project dependencies"
noroot ./.scripts/build.sh

# Core install.
if ! $(noroot wp core is-installed); then
  echo "Installing WordPress..."

  if [ "${WP_TYPE}" = "subdomain" ]; then
    INSTALL_COMMAND="multisite-install --subdomains"
  elif [ "${WP_TYPE}" = "subdirectory" ]; then
    INSTALL_COMMAND="multisite-install"
  else
    INSTALL_COMMAND="install"
  fi

  noroot wp core ${INSTALL_COMMAND} --url="${DOMAIN}" --quiet --title="${SITE_TITLE}" --admin_name=admin --admin_email="admin@${VVV_SITE_NAME}.dev" --admin_password="password"
else
  echo "Updating WordPress..."
  cd ${VVV_PATH_TO_SITE}
  noroot wp core update
  noroot wp core update-db
fi

if [ "${THEME}" = "" ]; then
  noroot wp theme install --activate twentyseventeen
else
  noroot wp theme activate ${THEME}
fi

noroot wp plugin activate ${PLUGINS}

cp -f "${VVV_PATH_TO_SITE}/provision/vvv-nginx.conf.tmpl" "${VVV_PATH_TO_SITE}/provision/vvv-nginx.conf"
sed -i "s#{{DOMAINS_HERE}}#${DOMAINS}#" "${VVV_PATH_TO_SITE}/provision/vvv-nginx.conf"

echo "$SITE_TITLE site is installed ¯\_(ツ)_/¯"
