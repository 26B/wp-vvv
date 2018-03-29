<?php
/**
 * @wordpress-plugin
 * Plugin Name: WordPress power-up
 * Description: Improves and fixes WordPress core.
 * Version:     1.3.0
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
 * @since  1.1.0
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
\add_filter( 'wp_resource_hints', '\WPSeed\WP\Plugin\Core\disable_emojis_remove_dns_prefetch', 10, 2 );

/**
 * Hide WP version strings from generator meta tag.
 *
 * @since  1.2.0
 * @return string
 */
\add_filter( 'the_generator', '__return_empty_string' );

/**
 * Hide WP version strings from scripts and styles.
 *
 * @since  1.2.0
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
\add_filter( 'script_loader_src', '\WPSeed\WP\Plugin\Core\remove_wp_version_strings' );
\add_filter( 'style_loader_src',  '\WPSeed\WP\Plugin\Core\remove_wp_version_strings' );

/**
 * Limit post revisions to 5.
 *
 * @since 1.2.0
 */
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
	define( 'WP_POST_REVISIONS', 5 );
}

/**
 * Disable author archives.
 *
 * @since  1.2.0
 * @return void
 */
function disable_author_archives() {
	global $wp_query;

	if ( \is_author() ) {
		$wp_query->set_404();
		\status_header( 404 );
	}
}
\add_action( 'template_redirect', '\WPSeed\WP\Plugin\Core\disable_author_archives', 0 );

/**
 * Optimize heartbeat settings.
 *
 * @since  1.2.0
 * @param  array $settings Heartbeat settings.
 * @return array
 */
function optimize_heartbeat_settings( $settings ) {
	$settings['autostart'] = false;
	$settings['interval'] = 60;
	return $settings;
}
\add_filter( 'heartbeat_settings', '\WPSeed\WP\Plugin\Core\optimize_heartbeat_settings' );

/**
 * Disable heartbeat unless post edit screen.
 *
 * @since  1.2.0
 * @return void
 */
function disable_heartbeat_unless_post_edit_screen() {
	global $pagenow;

	if ( $pagenow !== 'post.php' && $pagenow !== 'post-new.php' ) {
		\wp_deregister_script( 'heartbeat' );
	}
}
\add_action( 'init', '\WPSeed\WP\Plugin\Core\disable_heartbeat_unless_post_edit_screen', 1 );

/**
 * Remove Custom CSS section from the Customizer.
 *
 * @since 1.2.0
 * @param \WP_Customize_Manager $wp_customize \WP_Customize_Manager instance.
 */
function disable_custom_css_section( $wp_customize ) {
	$wp_customize->remove_section( 'custom_css' );
}
\add_action( 'customize_register', '\WPSeed\WP\Plugin\Core\disable_custom_css_section', 20 );

/**
 * Disable Custom CSS in the frontend head.
 *
 * @since 1.2.0
 */
\remove_action( 'wp_head', 'wp_custom_css_cb', 11 );
\remove_action( 'wp_head', 'wp_custom_css_cb', 101 );

/**
 * Dequeue jQuery migrate.
 *
 * @since  1.2.0
 * @param  \WP_Scripts $scripts \WP_Scripts object.
 * @return void
 */
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
\add_action( 'wp_default_scripts', '\WPSeed\WP\Plugin\Core\dequeue_jquery_migrate' );

/**
 * Disable auto embed script.
 *
 * @since  1.3.0
 * @return void
 */
function disable_oembed() {
	\wp_deregister_script( 'wp-embed' );
}
\add_action( 'wp_footer', '\WPSeed\WP\Plugin\Core\disable_oembed' );
