<?php
/**
 * @wordpress-plugin
 * Plugin Name: Dequeue jQuery migrate
 * Description: Dequeue jQuery migrate.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\jQuery;

function dequeue_jquery_migrate( $scripts ) {

	if ( \is_admin() ) {
		return;
	}

	if ( empty( $scripts->registered['jquery'] ) ) {
		return;
	}

	$jquery_dependencies = $scripts->registered['jquery']->deps;
	$scripts->registered['jquery']->deps = array_diff( $jquery_dependencies, array( 'jquery-migrate' ) );
}
\add_action( 'wp_default_scripts', __NAMESPACE__ . '\\dequeue_jquery_migrate' );
