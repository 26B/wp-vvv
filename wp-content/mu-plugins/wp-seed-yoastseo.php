<?php
/**
 * @wordpress-plugin
 * Plugin Name: Yoast SEO power-up
 * Description: Improves and fixes Yoast SEO.
 * Version:     1.1.0
 * Author:      WP-Seed
 * Author URI:  https://github.com/WP-Seed/
 * License:     GPL-2.0+
 */

namespace WPSeed\WP\Plugin\YoastSEO;

/**
 * Move the Yoast SEO metabox to the bottom of the admin pages.
 *
 * @since 1.1.0
 */
function wpseo_metabox_prio() {
	return 'low';
}
\add_filter( 'wpseo_metabox_prio', '\WPSeed\WP\Plugin\YoastSEO\wpseo_metabox_prio' );
