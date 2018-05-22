<?php
/**
 * @wordpress-plugin
 * Plugin Name: Clean up the WordPress header output
 * Description: Clean up the WordPress header output.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\Header;

/**
 * Clean up the WordPress header output.
 *
 * @since  1.0.0
 * @return void
 */
function clean_head() {
	\remove_action( 'wp_head', 'wp_generator' );
	\remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	\remove_action( 'wp_head', 'wlwmanifest_link' );
	\remove_action( 'wp_head', 'rsd_link' );
}
\add_action( 'init', __NAMESPACE__ . '\\clean_head', 10, 2 );

/**
 * Hide WP version strings from generator meta tag.
 *
 * @since  1.0.0
 * @return string
 */
\add_filter( 'the_generator', '__return_empty_string' );

/**
 * Disable Custom CSS in the frontend head.
 *
 * @since 1.0.0
 */
\remove_action( 'wp_head', 'wp_custom_css_cb', 11 );
\remove_action( 'wp_head', 'wp_custom_css_cb', 101 );

/**
 * Hide WP version strings from scripts and styles.
 *
 * @since  1.0.0
 * @return string
 */
function remove_wp_version_strings( $src ) {
	global $wp_version;

	parse_str( \wp_parse_url( $src, PHP_URL_QUERY ), $query );
	if ( ! empty( $query['ver'] ) && $query['ver'] === $wp_version ) {
		$src = \remove_query_arg( 'ver', $src );
	}

	return $src;
}
\add_filter( 'script_loader_src', __NAMESPACE__ . '\\remove_wp_version_strings' );
\add_filter( 'style_loader_src', __NAMESPACE__ . '\\remove_wp_version_strings' );
