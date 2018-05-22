<?php
/**
 * @wordpress-plugin
 * Plugin Name: Fix customizer
 * Description: Fix customizer.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\Customizer;

/**
 * Remove Custom CSS section from the Customizer.
 *
 * @since 1.0.0
 * @param \WP_Customize_Manager $wp_customize \WP_Customize_Manager instance.
 */
function disable_custom_css_section( $wp_customize ) {
	$wp_customize->remove_section( 'custom_css' );
}
\add_action( 'customize_register', __NAMESPACE__ . '\\disable_custom_css_section', 20 );
