<?php
/**
 * @wordpress-plugin
 * Plugin Name: Disable XML-RPC
 * Description: Disable XML-RPC.
 * Version:     1.0.0
 * Author:      26B
 * Author URI:  https://github.com/26B/
 * License:     GPL-2.0+
 */

 \add_filter( 'xmlrpc_enabled', '__return_false' );
