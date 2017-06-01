<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://davidlaietta.com
 * @since      1.0.0
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/public
 * @author     David Laietta <david@orangeblossommedia.com>
 */
class Resource_Toolbox_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Resource_Toolbox_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Resource_Toolbox_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/resource-toolbox-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Resource_Toolbox_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Resource_Toolbox_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/resource-toolbox-public.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Locate template.
	 *
	 * Locate the called template.
	 * Search Order:
	 * 1. /themes/{theme}/resource-toolbox/$template_name
	 * 2. /themes/{theme}/$template_name
	 * 3. /plugins/resource-toolbox/public/templates/$template_name.
	 *
	 * @since 1.0.0
	 *
	 * @param 	string 	$template_name			Template to load
	 * @param 	string 	$string $template_path	Path to templates
	 * @param 	string	$default_path			Default path to template files
	 * @return 	string 							Path to the template file
	 */
	function locate_template( $template_name, $template_path = '', $default_path = '' ) {

		// Set variable to search in resource-toolbox folder of theme
		if ( ! $template_path ) {
			$template_path = 'resource-toolbox/';
		}

		// Set default plugin templates path
		if ( ! $default_path ) {
			// Path to the template folder
			$default_path = plugin_dir_path( __FILE__ ) . 'templates/';
		}

		// Search template file in theme folder
		$template = locate_template( array(
			$template_path . $template_name,
			$template_name
		) );

		// Get plugins template file
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		return apply_filters( 'locate_template', $template, $template_name, $template_path, $default_path );

	}

	/**
	 * Get template.
	 *
	 * Search for the template and include the file.
	 *
	 * @since 1.0.0
	 *
	 * @see locate_template()
	 *
	 * @param string 	$template_name			Template to load.
	 * @param array 	$args					Args passed for the template file.
	 * @param string 	$string $template_path	Path to templates.
	 * @param string	$default_path			Default path to template files.
	 */
	function get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {

		if ( is_array( $args ) && isset( $args ) ) :
			extract( $args );
		endif;

		$template_file = locate_template( $template_name, $tempate_path, $default_path );

		if ( ! file_exists( $template_file ) ) :
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
			return;
		endif;

		include $template_file;

	}


	/**
	 * Template loader.
	 *
	 * The template loader will check if WP is loading a template
	 * for a specific Post Type and will try to load the template
	 * from out 'templates' directory.
	 *
	 * @since 1.0.0
	 *
	 * @see locate_template()
	 *
	 * @param	string	$template	Template file that is being loaded.
	 * @return	string				Template file that should be loaded.
	 */
	function template_loader( $template ) {

		$find = array();

		$file = '';

		if ( is_singular( 'resource_toolbox' ) ) {

			$file = 'single-resource.php';

		}

		if ( file_exists( $this->locate_template( $file ) ) ) {

			$template = $this->locate_template( $file );

		}


		return $template;

	}


	/**
	 * Adds extra content before/after the post for single resources
	 *
	 * @param string $content
	 * @return string
	 */
	public function resource_content( $content ) {

		global $post;

		if ( ! is_singular( 'resource_toolbox' ) || 'resource_toolbox' !== $post->post_type ) {
			return $content;
		}

		ob_start();

		do_action( 'resource_content_start' );

		// @todo resource content

		do_action( 'resource_content_end' );

		return apply_filters( 'resource_toolbox_single_resource_content', ob_get_clean(), $post );
	}

}
