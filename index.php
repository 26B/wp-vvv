<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress NOT to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', false );

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/core/wp-blog-header.php' );
