<?php
/**
 * @wordpress-plugin
 * Plugin Name: Autoload Composer dependencies
 * Description: Autoloads Composer dependencies at project root.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

if ( file_exists( ABSPATH . '/vendor/autoload.php' ) ) {
	require_once ABSPATH . '/vendor/autoload.php';
}
