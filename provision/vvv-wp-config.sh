#!/bin/bash

## CONFIGURATION ##

SALTS=`curl https://api.wordpress.org/secret-key/1.1/salt/`
WP_CACHE_KEY_SALT=`date +%s | sha256sum | head -c 64`

echo "Configuring WordPress for VVV..."

echo -e "<?php" >> wp-config-vvv.php

echo -e "\n/** MySQL settings. */" >> wp-config-vvv.php
echo -e "define( 'DB_NAME', '${DB_NAME}' );" >> wp-config-vvv.php
echo -e "define( 'DB_USER', '${DB_USER}' );" >> wp-config-vvv.php
echo -e "define( 'DB_PASSWORD', '${DB_PASSWORD}' );" >> wp-config-vvv.php
echo -e "define( 'DB_HOST', '${DB_HOST}' );" >> wp-config-vvv.php
echo -e "define( 'DB_CHARSET', '${DB_CHARSET}' );" >> wp-config-vvv.php
echo -e "define( 'DB_COLLATE', '${DB_COLLATE}' );" >> wp-config-vvv.php

echo -e "\n/** Database table prefix. */" >> wp-config-vvv.php
echo -e "\$table_prefix = '${TABLE_PREFIX}';" >> wp-config-vvv.php

echo -e "\n/** Authentication Unique Keys and Salts. */" >> wp-config-vvv.php
echo -e ${SALTS} >> wp-config-vvv.php

echo -e "\n/** Define site URLs. */" >> wp-config-vvv.php
echo -e "define( 'WP_SITEURL', 'http://"${DOMAIN}"/core' );" >> wp-config-vvv.php
echo -e "define( 'WP_HOME', 'http://"${DOMAIN}"' );" >> wp-config-vvv.php

echo -e "\n/** Define debug constants. */" >> wp-config-vvv.php
echo -e "define( 'WP_DEBUG', true );" >> wp-config-vvv.php
echo -e "define( 'WP_DEBUG_LOG', true );" >> wp-config-vvv.php
echo -e "define( 'WP_DEBUG_DISPLAY', false );" >> wp-config-vvv.php
echo -e "@ini_set( 'display_errors', 0 );" >> wp-config-vvv.php
echo -e "define( 'SAVEQUERIES', false );" >> wp-config-vvv.php

if [ "${WP_TYPE}" != "single" ]; then
  echo -e "\n/** Multisite constants. */" >> wp-config-vvv.php
  echo -e "define( 'WP_ALLOW_MULTISITE', true );" >> wp-config-vvv.php
  echo -e "define( 'DOMAIN_CURRENT_SITE', '${DOMAIN}' );" >> wp-config-vvv.php
  echo -e "define( 'SITE_ID_CURRENT_SITE', 1 );" >> wp-config-vvv.php
fi
if [ "${WP_TYPE}" = "subdomain" ]; then
  echo -e "define( 'SUBDOMAIN_INSTALL', true );" >> wp-config-vvv.php
fi

echo -e "\n/** Define cache constants. */" >> wp-config-vvv.php
echo -e "define( 'WP_CACHE', true );" >> wp-config-vvv.php
echo -e "define( 'WP_CACHE_KEY_SALT', '${WP_CACHE_KEY_SALT}' );" >> wp-config-vvv.php

echo -e "\n/** Define local environment. */" >> wp-config-vvv.php
echo -e "define( 'WP_ENV', 'development' );" >> wp-config-vvv.php
echo -e "define( 'WP_LOCAL_DEV', WP_ENV === 'development' );" >> wp-config-vvv.php

echo -e "\n/** Define content folder. */" >> wp-config-vvv.php
echo -e "define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );" >> wp-config-vvv.php
echo -e "define( 'WP_CONTENT_URL', WP_HOME . '/content' );" >> wp-config-vvv.php

echo -e "\n/** Disallow file editing. */" >> wp-config-vvv.php
echo -e "define( 'DISALLOW_FILE_EDIT', true );" >> wp-config-vvv.php
echo -e "define( 'DISALLOW_FILE_MODS', ! WP_LOCAL_DEV );" >> wp-config-vvv.php
