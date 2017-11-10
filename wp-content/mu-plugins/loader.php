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

if ( ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) ) {
	return;
}

\add_action( 'pre_current_active_plugins', function () {

	global $plugins, $wp_list_table;

	$mu_plugins = \get_mu_plugins();

	// Add our own mu-plugins to the page.
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
});

\add_filter( 'network_admin_plugin_action_links', function ( $actions, $plugin_file, $plugin_data, $context ) {

	$mu_plugins = \get_mu_plugins();

	if ( $context !== 'mustuse' || ! array_key_exists( $plugin_file, $mu_plugins, true ) ) {
		return $actions;
	}

	$actions[] = sprintf( '<span style="color:#333">File: <code>%s</code></span>', $plugin_file );

	return $actions;
}, 10, 4 );
