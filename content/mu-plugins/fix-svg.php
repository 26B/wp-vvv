<?php
/**
 * @wordpress-plugin
 * Plugin Name: SVG
 * Description: Fixes and improves SVG support.
 * Version:     1.0.0
 * Author:      WP-Seed
 * Author URI:  https://github.com/WP-Seed
 * License:     GPL-2.0+
 */

namespace WPSeed\WP\Plugin\SVG;

/**
 * Add svg to the list of allowed mime types and file extensions.
 *
 * @see upload_mimes()
 *
 * @since  1.0.0
 * @param  array $t Mime types keyed by the file extension regex corresponding to
 *                  those types. 'swf' and 'exe' removed from full list. 'htm|html' also
 *                  removed depending on '$user' capabilities.
 * @return array    Possibly-modified allowed mime types and file extensions.
 */
function upload_mimes( $t ) {
	$t['svg']  = 'image/svg+xml';
	$t['svgz'] = 'image/svg+xml';
	return $t;
}
\add_filter( 'upload_mimes', '\WPSeed\WP\Plugin\SVG\upload_mimes' );

/**
 * Fix the "real" file type of the svg file.
 *
 * @see wp_check_filetype_and_ext()
 *
 * @since  1.0.0
 * @param  array  $data     File data array containing 'ext', 'type', and
 *                           'proper_filename' keys.
 * @param  string $file     Full path to the file.
 * @param  string $filename The name of the file (may differ from $file due to
 *                          $file being in a tmp directory).
 * @param  array  $mimes    Key is the file extension with value as the mime type.
 * @return array            Possibly-modified file data array.
 */
function wp_check_filetype_and_ext( $data, $file, $filename, $mimes ) {
	$filetype = wp_check_filetype( $filename, $mimes );
	return array(
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename'],
	);
}
\add_filter( 'wp_check_filetype_and_ext', '\WPSeed\WP\Plugin\SVG\wp_check_filetype_and_ext', 10, 4 );

/**
 * Removes the width and height attributes of <img> tags for svg.
 *
 * @see image_downsize()
 *
 * @since  1.0.0
 * @param  bool $downsize Whether to short-circuit the image downsize. Default false.
 * @param  int  $id       Attachment ID for image.
 * @return bool|array     False if not svg. Array otherwise.
 */
function image_downsize( $out, $id ) {
	$image_url  = wp_get_attachment_url( $id );
	$file_ext   = pathinfo( $image_url, PATHINFO_EXTENSION );

	if ( $file_ext !== 'svg' ) {
		return false;
	}

	return array( $image_url, null, null, false );
}
\add_filter( 'image_downsize', '\WPSeed\WP\Plugin\SVG\image_downsize', 10, 2 );
