<?php
/**
 * @wordpress-plugin
 * Plugin Name: Disable WordPress emoji's
 * Description: Disable WordPress emoji's.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\Emojis;

/**
 * Disable emoji's.
 *
 * @since  1.0.0
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
\add_action( 'init', __NAMESPACE__ . '\\disable_emojis' );

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
\add_filter( 'tiny_mce_plugins', __NAMESPACE__ . '\\disable_emojis_tinymce' );

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @since  1.0.0
 * @param  array  $urls          URLs to print for resource hints.
 * @param  string $relation_type The relation type the URLs are printed for.
 * @return array
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
\add_filter( 'wp_resource_hints', __NAMESPACE__ . '\\disable_emojis_remove_dns_prefetch', 10, 2 );
