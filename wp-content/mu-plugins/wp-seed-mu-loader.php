<?php
/**
 * @wordpress-plugin
 * Plugin Name: MU Plugin Loader
 * Description: Loads the MU plugins required to run the site.
 * Version:     1.0.0
 * Author:      WP-Seed
 * Author URI:  https://github.com/WP-Seed/
 * License:     GPL-2.0+
 */

namespace WPSeed\WP\Plugin\Loader;

if ( ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) ) {
	return;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

/**
 * Get list of must-use plugins residing in subdirectories.
 *
 * @since  1.0.0
 * @param  string $cache_key Cache key to store plugins under.
 * @return array             List of plugin files to load.
 */
function get_mu_plugins( $cache_key = 'mu_plugins_cache' ) {

	$mu_plugins = \get_site_transient( $cache_key );
	if ( is_array( $mu_plugins ) ) {
		foreach ( $mu_plugins as $plugin_file => $plugin_data ) {
			if ( ! is_readable( WPMU_PLUGIN_DIR . '/' . $plugin_file ) ) {
				$mu_plugins = array();
				break;
			}
		}

		if ( ! empty( $mu_plugins ) ) {
			return $mu_plugins;
		}
	}

	$plugins = \get_plugins( '/../mu-plugins/' );

	$mu_plugins = array();
	foreach ( array_keys( $plugins ) as $plugin_file ) {
		if ( dirname( $plugin_file ) !== '.' ) {
			$mu_plugins[ $plugin_file ] = \get_plugin_data( WPMU_PLUGIN_DIR . '/' . $plugin_file );
		}
	}

	\set_site_transient( $cache_key, $mu_plugins );

	return $mu_plugins;
}

/**
 * Show our own must-use plugins in the Network Admin.
 *
 * @since 1.0.0
 */
\add_action( 'pre_current_active_plugins', function () {

	global $plugins, $wp_list_table;

	$mu_plugins = get_mu_plugins();
	foreach ( $mu_plugins as $plugin_file => $plugin_data ) {

		if ( empty( $plugin_data['Name'] ) ) {
			$plugin_data['Name'] = $plugin_file;
		}

		$plugins['mustuse'][ $plugin_file ] = $plugin_data;
	}

	// Recount totals.
	$GLOBALS['totals']['mustuse'] = count( $plugins['mustuse'] );

	// Only apply the rest if we're actually looking at the page.
	if ( $GLOBALS['status'] !== 'mustuse' ) {
		return;
	}

	// Reset the list table's data.
	$wp_list_table->items = $plugins['mustuse'];
	foreach ( $wp_list_table->items as $plugin_file => $plugin_data ) {
		$wp_list_table->items[ $plugin_file ] = \_get_plugin_data_markup_translate( $plugin_file, $plugin_data, false, true );
	}

	$total_this_page = $GLOBALS['totals']['mustuse'];
	if ( $GLOBALS['orderby'] ) {
		uasort( $wp_list_table->items, array( $wp_list_table, '_order_callback' ) );
	}

	/**
	 * Force showing all plugins.
	 *
	 * @see https://core.trac.wordpress.org/ticket/27110
	 */
	$plugins_per_page = $total_this_page;
	$wp_list_table->set_pagination_args( array(
		'total_items' => $total_this_page,
		'per_page'    => $plugins_per_page,
	) );
} );

/**
 * Load must-use plugins.
 *
 * @since 1.0.0
 */
\add_action( 'muplugins_loaded', function () {

	$cache_key   = 'mu_plugins_cache';
	$flush_cache = false;

	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$flush_cache = strpos( $_SERVER['REQUEST_URI'], '/wp-admin/plugins.php' ) !== false;
	}

	if ( $flush_cache ) {
		\delete_site_transient( $cache_key );
	}

	foreach ( get_mu_plugins( $cache_key ) as $plugin_file => $plugin_data ) {
		include_once WPMU_PLUGIN_DIR . '/' . $plugin_file;
	}
} );
