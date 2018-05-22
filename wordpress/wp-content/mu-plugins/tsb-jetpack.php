<?php
/**
 * @wordpress-plugin
 * Plugin Name: Fix Jetpack
 * Description: Fix Jetpack.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\Jetpack;

/**
 * Dequeue Jetpack junk.
 *
 * @since  1.0.0
 * @return void
 */
function wp_enqueue_scripts() {
	\wp_dequeue_script( 'devicepx' );
}
\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\wp_enqueue_scripts', 99 );

/**
 * Go home, Jetpack, you're drunk.
 *
 * @since  1.0.0
 * @return void
 */
function jetpack_holiday_chance_of_snow() {
	\add_action( 'jetpack_holiday_chance_of_snow', '__return_null' );
}
\add_action( 'jetpack_holiday_chance_of_snow', __NAMESPACE__ . '\\jetpack_holiday_chance_of_snow' );
