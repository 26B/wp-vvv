<?php
/**
 * @wordpress-plugin
 * Plugin Name: Dashboard power-up
 * Description: Disable all the default dashboard widgets.
 * Version:     1.0.0
 * Author:      WP-Seed
 * Author URI:  https://github.com/WP-Seed/
 * License:     GPL-2.0+
 */

namespace WPSeed\WP\Plugin\Dashboard;

/**
 * Disable all the default widgets.
 *
 * @since  1.0.0
 * @return void
 */
function wp_dashboard_setup() {
	global $wp_meta_boxes;

	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
}
\add_action( 'wp_dashboard_setup', '\WPSeed\WP\Plugin\Dashboard\wp_dashboard_setup' );

/**
 * Remove the Welcome Panel.
 *
 * @since  1.0.0
 * @return void
 */
\remove_action( 'welcome_panel', 'wp_welcome_panel' );
