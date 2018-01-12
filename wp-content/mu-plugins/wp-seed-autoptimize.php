<?php
/**
 * @wordpress-plugin
 * Plugin Name: Autoptimize power-up
 * Description: Improves and fixes Autoptimize.
 * Version:     1.0.0
 * Author:      WP-Seed
 * Author URI:  https://github.com/WP-Seed/
 * License:     GPL-2.0+
 */

namespace WPSeed\WP\Plugin\Autoptimize;

/**
 * Do not concatenate CSS inline assets.
 *
 * @since  1.0.0
 * @return false
 */
\add_filter( 'autoptimize_css_include_inline', '__return_false' );

/**
 * Do not concatenate JavaScript inline assets.
 *
 * @since  1.0.0
 * @return false
 */
\add_filter( 'autoptimize_js_include_inline', '__return_false' );

/**
 * Excludes JavaScript assets from optimization.
 *
 * @since  1.0.0
 * @param  string $exclude JavaScript assets to exclude in a comma-separated list.
 * @return string          Filtered JavaScript assets to exclude in a comma-separated list.
 */
function autoptimize_filter_js_exclude( $exclude ) {
	return implode( ',', array(
		'jquery.js',
		$exclude,
	) );
}
\add_filter( 'autoptimize_filter_js_exclude', '\WPSeed\WP\Plugin\Autoptimize\autoptimize_filter_js_exclude' );

/**
 * Excludes CSS assets from optimization.
 *
 * @since  1.0.0
 * @param  string $exclude CSS assets to exclude in a comma-separated list.
 * @return string          Filtered CSS assets to exclude in a comma-separated list.
 */
function autoptimize_filter_css_exclude( $exclude ) {
	return implode( ',', array(
		'customize-support',
		$exclude,
	) );
}
\add_filter( 'autoptimize_filter_css_exclude', '\WPSeed\WP\Plugin\Autoptimize\autoptimize_filter_css_exclude' );
