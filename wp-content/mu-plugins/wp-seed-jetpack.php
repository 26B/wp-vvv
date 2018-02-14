<?php
/**
 * @wordpress-plugin
 * Plugin Name: Jetpack power-up
 * Description: Improves and fixes Jetpack.
 * Version:     1.0.0
 * Author:      WP-Seed
 * Author URI:  https://github.com/WP-Seed/
 * License:     GPL-2.0+
 */

namespace WPSeed\WP\Plugin\Jetpack;

/**
 * Dequeue Jetpack junk.
 *
 * @since  1.0.0
 * @return void
 */
function wp_enqueue_scripts() {
	\wp_dequeue_script( 'devicepx' );
}
\add_action( 'wp_enqueue_scripts', '\WPSeed\WP\Plugin\Jetpack\wp_enqueue_scripts', 99 );

/**
 * Go home, Jetpack, you're drunk.
 *
 * @since  1.0.0
 * @return void
 */
function jetpack_holiday_chance_of_snow() {
	\add_action( 'jetpack_holiday_chance_of_snow', '__return_null' );
}
\add_action( 'jetpack_holiday_chance_of_snow', '\WPSeed\WP\Plugin\Jetpack\jetpack_holiday_chance_of_snow' );
