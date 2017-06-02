<?php

/**
 * Fired during plugin activation
 *
 * @link       https://davidlaietta.com
 * @since      1.0.0
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/includes
 * @author     David Laietta <david@orangeblossommedia.com>
 */
class Resource_Toolbox_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        require_once plugin_dir_path( __DIR__ ) . 'admin/class-resource-toolbox-settings.php';
        Resource_Toolbox_Settings::default_general_settings();
        Resource_Toolbox_Settings::default_single_resource_settings();
        Resource_Toolbox_Settings::default_resource_loop_settings();

	}

}
