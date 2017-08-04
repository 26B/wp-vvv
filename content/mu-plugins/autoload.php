<?php
/**
 * @wordpress-plugin
 * Plugin Name: Autoload Composer Dependencies
 * Description: Autoloads Composer dependencies at project root.
 * Version:     1.0.0
 * Author:      Pedro Duarte
 * Author URI:  https://github.com/xipasduarte/
 * License:     GPL-2.0+
 */

if ( file_exists( ABSPATH . '/vendor/autoload.php' ) ) {
	require_once ABSPATH . '/vendor/autoload.php';
}
