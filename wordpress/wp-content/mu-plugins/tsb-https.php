<?php
/**
 * @wordpress-plugin
 * Plugin Name: Fix HTTPS support
 * Description: Fix HTTPS support.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\HTTPS;

/**
 * Force URLs in srcset attributes into HTTPS scheme.
 *
 * @since  1.0.0
 * @param  array $sources Source data to include in the 'srcset'.
 * @return array          Possibly-modified source data.
 */
function wp_calculate_image_srcset( $sources ) {

	foreach ( $sources as &$source ) {
		$source['url'] = \set_url_scheme( $source['url'], \is_ssl() ? 'https' : 'http' );
	}

	return $sources;
}
\add_filter( 'wp_calculate_image_srcset', __NAMESPACE__ . '\\wp_calculate_image_srcset', 10, 1 );

/**
 * Replace http:// with https:// in the embed code (before caching).
 *
 * @since 1.0.0
 * @param string $data The returned oEmbed HTML.
 * @param string $url  URL of the content to be embedded.
 * @param array  $args Optional arguments, usually passed from a shortcode.
 */
function secure_oembed_result( $data, $url, $args ) {
	return \is_ssl() ? preg_replace( '/http:\/\//', 'https://', $data ) : $data;
}
\add_filter( 'oembed_result', __NAMESPACE__ . '\\secure_oembed_result',      10, 3 );

/**
 * Replace http:// with https:// in the embed code (after caching).
 *
 * @since 1.0.0
 * @param mixed  $cache   The cached HTML result, stored in post meta.
 * @param string $url     The attempted embed URL.
 * @param array  $attr    An array of shortcode attributes.
 * @param int    $post_id Post ID.
 */
function secure_embed_oembed_html( $cache, $url, $attr, $post_id ) {
	return \is_ssl() ? preg_replace( '/http:\/\//', 'https://', $cache ) : $cache;
}
\add_filter( 'embed_oembed_html', __NAMESPACE__ . '\\secure_embed_oembed_html',  10, 4 );
