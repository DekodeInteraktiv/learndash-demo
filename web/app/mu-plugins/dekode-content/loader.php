<?php
/**
 * Plugin Name: Dekode Content
 * Version: 0.1
 * Description: Setup post types, taxonomies and Advanced Custom Fields
 * Author: Dekode
 * Author URI: https://dekode.no/
 */

defined( 'ABSPATH' ) || die( 'Shame on you' );

define( 'DEKODE_CONTENT_DIR', trailingslashit( __DIR__ ) );

/**
 * Extend rest endpoints
 */
function dekode_include_rest_handlers() {
    include DEKODE_CONTENT_DIR . 'rest-handlers/extend-sfwd-courses.php';
}
add_action( 'rest_api_init', 'dekode_include_rest_handlers' );
