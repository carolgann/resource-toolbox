<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://davidlaietta.com
 * @since             0.1.0
 * @package           Resource_Toolbox
 *
 * @wordpress-plugin
 * Plugin Name:       Resource Toolbox
 * Plugin URI:        https://orangeblossommedia.com
 * Description:       This plugin adds a Resource custom post type, along with single and archive views, and options for display 
 * Version:           0.1.0
 * Author:            David Laietta
 * Author URI:        https://davidlaietta.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       resource-toolbox
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-resource-toolbox-activator.php
 */
function activate_resource_toolbox() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-resource-toolbox-activator.php';
	Resource_Toolbox_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-resource-toolbox-deactivator.php
 */
function deactivate_resource_toolbox() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-resource-toolbox-deactivator.php';
	Resource_Toolbox_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_resource_toolbox' );
register_deactivation_hook( __FILE__, 'deactivate_resource_toolbox' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-resource-toolbox.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_resource_toolbox() {

	$plugin = new Resource_Toolbox();
	$plugin->run();

}
run_resource_toolbox();
