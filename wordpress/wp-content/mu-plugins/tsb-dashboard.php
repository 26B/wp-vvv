<?php
/**
 * @wordpress-plugin
 * Plugin Name: Clean up the WordPress dashboard
 * Description: Clean up the WordPress dashboard.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\Dashboard;

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
\add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\wp_dashboard_setup' );

/**
 * Remove the Welcome Panel.
 *
 * @since  1.0.0
 * @return void
 */
\remove_action( 'welcome_panel', 'wp_welcome_panel' );
