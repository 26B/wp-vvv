<?php
/**
 * @wordpress-plugin
 * Plugin Name: Fix Yoast SEO
 * Description: Fix Yoast SEO.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\YoastSEO;

/**
 * Move the Yoast SEO metabox to the bottom of the admin pages.
 *
 * @since 1.0.0
 */
function wpseo_metabox_prio() {
	return 'low';
}
\add_filter( 'wpseo_metabox_prio', __NAMESPACE__ . '\\wpseo_metabox_prio' );
