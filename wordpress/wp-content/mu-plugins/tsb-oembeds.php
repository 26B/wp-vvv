<?php
/**
 * @wordpress-plugin
 * Plugin Name: Disable auto embed script
 * Description: Disable auto embed script.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\oEmbeds;

function disable_oembed() {
	\wp_deregister_script( 'wp-embed' );
}
\add_action( 'wp_footer', __NAMESPACE__ . '\\disable_oembed' );
