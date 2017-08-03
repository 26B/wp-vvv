#!/bin/bash

## CONFIGURATION ##

DOMAIN=`get_primary_host "${VVV_SITE_NAME}".dev`
DOMAINS=`get_hosts "${DOMAIN}"`
SITE_TITLE=`get_config_value 'site_title' "${DOMAIN}"`
WP_VERSION=`get_config_value 'wp_version' 'latest'`
WP_TYPE=`get_config_value 'wp_type' 'single'`
DB_NAME=`get_config_value 'db_name' "${VVV_SITE_NAME}_dev"`
DB_NAME=${DB_NAME//[\\\/\.\<\>\:\"\'\|\?\!\*-]/}
DB_CHARSET=`get_config_value 'db_collate' 'utf8mb4'`
PLUGINS=`get_config_value 'wp_plugins' ''`

echo -e "\n================================"
echo -e "${SITE_TITLE}"
echo -e "================================\n\n"

# Build project dependencies.
echo -e "\nBuilding project dependencies"
noroot ./.scripts/build.sh

# Create a database, if we don't already have one.
echo -e "\nCreating database '${DB_NAME}' (if it doesn't exist)"
mysql -u root --password=root -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME}"
mysql -u root --password=root -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO wp@localhost IDENTIFIED BY 'wp';"
echo -e "\n DB operations done.\n\n"

# Configure WordPress.
if [[ ! -f "${VVV_PATH_TO_SITE}/wp-config-vvv.php" ]]; then
  echo "Configuring WordPress..."

  WP_CACHE_KEY_SALT=`date +%s | sha256sum | head -c 64`

  echo -e "/** Injected definitions. */" >> wp-config-vvv.php

  echo -e "\n/** Define site URLs. */" >> wp-config-vvv.php
  echo -e "define( 'WP_SITEURL', 'http://"${DOMAIN}"/wp-core' );" >> wp-config-vvv.php
  echo -e "define( 'WP_HOME', 'http://"${DOMAIN}"' );" >> wp-config-vvv.php

  echo -e "\n/** Define debug constants. */" >> wp-config-vvv.php
  echo -e "define( 'WP_DEBUG', true );" >> wp-config-vvv.php
  echo -e "define( 'WP_DEBUG_LOG', true );" >> wp-config-vvv.php
  echo -e "define( 'WP_DEBUG_DISPLAY', false );" >> wp-config-vvv.php
  echo -e "@ini_set( 'display_errors', 0 );" >> wp-config-vvv.php
  echo -e "define( 'SAVEQUERIES', false );" >> wp-config-vvv.php

  echo -e "\n/** Define cache constants. */" >> wp-config-vvv.php
  echo -e "define( 'WP_CACHE', true );" >> wp-config-vvv.php
  echo -e "define( 'WP_CACHE_KEY_SALT', '${WP_CACHE_KEY_SALT}' );" >> wp-config-vvv.php

  echo -e "\n/** Define local environment. */" >> wp-config-vvv.php
  echo -e "define( 'WP_ENV', 'development' );" >> wp-config-vvv.php
  echo -e "define( 'WP_LOCAL_DEV', WP_ENV === 'development' );" >> wp-config-vvv.php

  echo -e "\n/** Define MU Plugins folder. */" >> wp-config-vvv.php
  echo -e "define( 'WPMU_PLUGIN_DIR', dirname( __FILE__ ) . '/content/plugins-mu' );" >> wp-config-vvv.php
  echo -e "define( 'WPMU_PLUGIN_URL', WP_HOME . '/content/plugins-mu' );" >> wp-config-vvv.php

  echo -e "\n/** Disallow file editing. */" >> wp-config-vvv.php
  echo -e "define( 'DISALLOW_FILE_EDIT', true );" >> wp-config-vvv.php
  echo -e "define( 'DISALLOW_FILE_MODS', ! WP_LOCAL_DEV );" >> wp-config-vvv.php

  echo -e "\n/** End injected definitions. */" >> wp-config-vvv.php

  noroot wp config create --dbname="${DB_NAME}" --dbuser=wp --dbpass=wp --dbcharset="${DB_CHARSET}" --force --extra-php < wp-config-vvv.php
fi

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

  noroot wp core ${INSTALL_COMMAND} --url="${DOMAIN}" --quiet --title="${SITE_TITLE}" --admin_name=admin --admin_email="admin@wp-seed.dev" --admin_password="password"

else
  echo "Updating WordPress..."
  cd ${VVV_PATH_TO_SITE}
  noroot wp core update --version="${WP_VERSION}"
  noroot wp core update-db
fi

noroot wp plugin activate ${PLUGINS}

echo "$SITE_TITLE site is installed ¯\_(ツ)_/¯"
