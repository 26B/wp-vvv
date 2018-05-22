<?php
/**
 * @wordpress-plugin
 * Plugin Name: Fix subdomain or subdirectory instalations
 * Description: Fix subdomain or subdirectory instalations.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\Network;

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
\add_filter( 'network_admin_url', __NAMESPACE__ . '\\network_admin_url', 10, 2 );
