<?php
/**
 * @wordpress-plugin
 * Plugin Name: WordPress power-up
 * Description: Improves and fixes WordPress core.
 * Version:     1.1.0
 * Author:      WP-Seed
 * Author URI:  https://github.com/WP-Seed/
 * License:     GPL-2.0+
 */

namespace WPSeed\WP\Plugin\Core;

/**
 * Fix domain mapping urls.
 *
 * @since  1.0.0
 * @return string
 */
if ( defined( 'DOMAIN_MAPPING' ) ) {
	\add_action( 'plugins_loaded', function() {
		\remove_filter( 'plugins_url', 'domain_mapping_plugins_uri', 1 );
		\add_filter( 'plugins_url', function( $full_url ) {
			return str_replace( \get_original_url( 'siteurl' ), \get_option( 'siteurl' ), $full_url );
		}, 1 );
	} );
}

/**
 * Fix network url for subdomain or subdirectory WordPress instalations.
 *
 * @since  1.0.0
 * @return string
 */
function network_admin_url( $url, $path ) {

	if ( ! \is_multisite() ) {
		return $url;
	}

	return sprintf(
		'%s%s',
		\network_site_url( 'wordpress/wp-admin/network/', 'admin' ),
		ltrim( $path, '/' )
	);
}
\add_filter( 'network_admin_url', '\WPSeed\WP\Plugin\Core\network_admin_url', 10, 2 );

/**
 * Clean up the WordPress header output.
 *
 * @since  1.1.0
 * @return void
 */
function clean_head() {
	\remove_action( 'wp_head', 'wp_generator' );
	\remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	\remove_action( 'wp_head', 'wlwmanifest_link' );
	\remove_action( 'wp_head', 'rsd_link' );
}
\add_action( 'init', '\WPSeed\WP\Plugin\Core\clean_head', 10, 2 );

/**
 * Disable XML-RPC.
 *
 * @since  1.1.0
 * @return false
 */
\add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Disable emoji's.
 *
 * @since  1.1.0
 * @return void
 */
function disable_emojis() {
	\remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	\remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	\remove_action( 'wp_print_styles', 'print_emoji_styles' );
	\remove_action( 'admin_print_styles', 'print_emoji_styles' );
	\remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	\remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	\remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
\add_action( 'init', '\WPSeed\WP\Plugin\Core\disable_emojis' );

/**
 * Remove the tinymce emoji plugin.
 *
 * @since  1.0.0
 * @param  array $plugins
 * @return array
 */
function disable_emojis_tinymce( $plugins ) {

	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}

	return array();
}
\add_filter( 'tiny_mce_plugins', '\WPSeed\WP\Plugin\Core\disable_emojis_tinymce' );

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @since  1.1.0
 * @param  array  $urls          URLs to print for resource hints.
 * @param  string $relation_type The relation type the URLs are printed for.
 * @return array                 Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {

	if ( 'dns-prefetch' !== $relation_type ) {
		return $urls;
	}

	$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
	foreach ( $urls as $key => $url ) {
		if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
			unset( $urls[ $key ] );
		}
	}

	return $urls;
}
\add_filter( 'wp_resource_hints', '\WPSeed\WP\Plugin\Core\disable_emojis_remove_dns_prefetch', 10, 2 );
