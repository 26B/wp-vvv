<?php
/**
 * @wordpress-plugin
 * Plugin Name: Fix heartbeat
 * Description: Fix heartbeat.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\Heartbeat;

/**
 * Optimize heartbeat settings.
 *
 * @since  1.0.0
 * @param  array $settings Heartbeat settings.
 * @return array
 */
function optimize_heartbeat_settings( $settings ) {
	$settings['autostart'] = false;
	$settings['interval'] = 60;
	return $settings;
}
\add_filter( 'heartbeat_settings', __NAMESPACE__ . '\\optimize_heartbeat_settings' );

/**
 * Disable heartbeat unless post edit screen.
 *
 * @since  1.0.0
 * @return void
 */
function disable_heartbeat_unless_post_edit_screen() {
	global $pagenow;

	if ( $pagenow !== 'post.php' && $pagenow !== 'post-new.php' ) {
		\wp_deregister_script( 'heartbeat' );
	}
}
\add_action( 'init', __NAMESPACE__ . '\\disable_heartbeat_unless_post_edit_screen', 1 );
