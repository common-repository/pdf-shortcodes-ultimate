<?php
/**
 * Plugin Name: PDF Shortcodes Ultimate
 * Version: 1.0.0
 * Plugin URI: https://git.open-dsi.fr/wordpress-plugin/pdf-shortcodes-ultimate
 * Description: Embed PDF documents in your article or page with this "PDF" shortcode for Shortcodes Ultimate.
 * Author: Open-DSI
 * Author URI: https://www.open-dsi.fr/
 * Requires at least: 3.9
 * Tested up to: 4.8.2
 *
 * Text Domain: pdf-shortcodes-ultimate
 * Domain Path: /lang/
 *
 * @package PDF Shortcodes Ultimate
 * @author Open-DSI
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Load plugin class files.
require_once 'includes/class-pdf-shortcodes-ultimate.php';
// require_once 'includes/class-pdf-shortcodes-ultimate-settings.php';

// Load plugin libraries.
// require_once 'includes/lib/class-pdf-shortcodes-ultimate-admin-api.php';
// require_once 'includes/lib/class-pdf-shortcodes-ultimate-post-type.php';
// require_once 'includes/lib/class-pdf-shortcodes-ultimate-taxonomy.php';

/**
 * Returns the main instance of PDF_Shortcodes_Ultimate to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object PDF_Shortcodes_Ultimate
 */
function PDF_Shortcodes_Ultimate() {
	$instance = PDF_Shortcodes_Ultimate::instance( __FILE__, '1.0.0' );

	/*if ( is_null( $instance->settings ) ) {
		$instance->settings = PDF_Shortcodes_Ultimate_Settings::instance( $instance );
	}*/

	return $instance;
}

PDF_Shortcodes_Ultimate();
