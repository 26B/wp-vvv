<?php
/**
 * @wordpress-plugin
 * Plugin Name: Disable author archives
 * Description: Disable author archives.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

namespace TSB\WP\MUPlugin\Author;

function disable_author_archives() {
	global $wp_query;

	if ( \is_author() ) {
		$wp_query->set_404();
		\status_header( 404 );
	}
}
\add_action( 'template_redirect', __NAMESPACE__ . '\\disable_author_archives', 0 );


