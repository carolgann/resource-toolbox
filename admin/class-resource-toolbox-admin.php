<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://davidlaietta.com
 * @since      1.0.0
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/admin
 * @author     David Laietta <david@orangeblossommedia.com>
 */
class Resource_Toolbox_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/resource-toolbox-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/resource-toolbox-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
     * Create a Custom Post Type to manage Resources
     *
     * @param array $post
     *
     * @return int|WP_Error
     */
    public function create_custom_post_type_resources() {

        // Get options to determine supported features
        $resource_options = get_option( 'resource_toolbox_single_resource_settings' );

        $supports = array( 'title',
            'editor',
            'thumbnail',
            'revisions',
            'author'
            );

        if ( isset( $resource_options['enable_excerpts'] ) && $resource_options['enable_excerpts'] === '1' ) {
            $supports[] = 'excerpt';
        }

        if ( isset( $resource_options['enable_discussion'] ) && $resource_options['enable_discussion'] === '1' ) {
            $supports[] = 'comments';
        }

        // Register Custom Post Type

        $labels = array(
            'name'                  => _x( 'Resources', 'Post Type General Name', $this->plugin_name ),
            'singular_name'         => _x( 'Resource', 'Post Type Singular Name', $this->plugin_name ),
            'menu_name'             => __( 'Resources', $this->plugin_name ),
            'name_admin_bar'        => __( 'Resource', $this->plugin_name ),
            'archives'              => __( 'Resource Archives', $this->plugin_name ),
            'parent_item_colon'     => __( 'Parent Resource:', $this->plugin_name ),
            'all_items'             => __( 'Resources', $this->plugin_name ),
            'add_new_item'          => __( 'Add New Resource', $this->plugin_name ),
            'add_new'               => __( 'Add New', $this->plugin_name ),
            'new_item'              => __( 'New Resource', $this->plugin_name ),
            'edit_item'             => __( 'Edit Resource', $this->plugin_name ),
            'update_item'           => __( 'Update Resource', $this->plugin_name ),
            'view_item'             => __( 'View Resource', $this->plugin_name ),
            'search_items'          => __( 'Search Resource', $this->plugin_name ),
            'not_found'             => __( 'Not found', $this->plugin_name ),
            'not_found_in_trash'    => __( 'Not found in Trash', $this->plugin_name ),
            'featured_image'        => __( 'Featured Image', $this->plugin_name ),
            'set_featured_image'    => __( 'Set featured image', $this->plugin_name ),
            'remove_featured_image' => __( 'Remove featured image', $this->plugin_name ),
            'use_featured_image'    => __( 'Use as featured image', $this->plugin_name ),
            'insert_into_item'      => __( 'Insert into resource', $this->plugin_name ),
            'uploaded_to_this_item' => __( 'Uploaded to this resource', $this->plugin_name ),
            'items_list'            => __( 'Resource', $this->plugin_name ),
            'items_list_navigation' => __( 'Resource navigation', $this->plugin_name ),
            'filter_items_list'     => __( 'Filter resources', $this->plugin_name ),
        );

        $args = array(
            'label'                 => __( 'Resource', $this->plugin_name ),
            'description'           => __( 'Resources', $this->plugin_name ),
            'labels'                => $labels,
            'supports'              => $supports,
            'taxonomies'            => array( 'post_tag' ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-excerpt-view',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
			'query_var'          	=> true,
			'rewrite'            	=> array( 'slug' => 'resources' ),
        );

        register_post_type( 'resource_toolbox', $args );

    }

    /**
     * Create a Custom Taxonomy to manage resource categories
     */
    function create_custom_taxonomy_resource_category() {

        $labels = array(
            'name'                       => _x( 'Resource Categories', 'Taxonomy General Name', $this->plugin_name ),
            'singular_name'              => _x( 'Resource Category', 'Taxonomy Singular Name', $this->plugin_name ),
            'menu_name'                  => __( 'Resource Category', $this->plugin_name ),
            'all_items'                  => __( 'All Categories', $this->plugin_name ),
            'parent_item'                => __( 'Parent Category', $this->plugin_name ),
            'parent_item_colon'          => __( 'Parent Category:', $this->plugin_name ),
            'new_item_name'              => __( 'New Category Name', $this->plugin_name ),
            'add_new_item'               => __( 'Add New Category', $this->plugin_name ),
            'edit_item'                  => __( 'Edit Category', $this->plugin_name ),
            'update_item'                => __( 'Update Category', $this->plugin_name ),
            'view_item'                  => __( 'View Category', $this->plugin_name ),
            'separate_items_with_commas' => __( 'Separate categories with commas', $this->plugin_name ),
            'add_or_remove_items'        => __( 'Add or remove categories', $this->plugin_name ),
            'choose_from_most_used'      => __( 'Choose from the most used', $this->plugin_name ),
            'popular_items'              => __( 'Popular Categories', $this->plugin_name ),
            'search_items'               => __( 'Search Categories', $this->plugin_name ),
            'not_found'                  => __( 'Not Found', $this->plugin_name ),
            'no_terms'                   => __( 'No categories', $this->plugin_name ),
            'items_list'                 => __( 'Categories list', $this->plugin_name ),
            'items_list_navigation'      => __( 'Categories list navigation', $this->plugin_name ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );

        register_taxonomy( 'resource_category', array( 'resource_toolbox' ), $args );

    }

}
