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
class Resource_Toolbox_Templates extends Resource_Toolbox_Public {

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
    public function __construct() {

    }

    /**
     * Modify page title
     *
     * Modifies output of page titles for resources
     *
     * @since 1.0.0
     *
     * @param   string  $title   Title of archive page
     * @return  string              Template file that should be loaded.
     */
    function archive_page_title( $title ) {

        // If there is an archive title setting, display that
        if ( null !== get_option( 'resource_toolbox_general_settings' ) ) {

            $settings = get_option( 'resource_toolbox_general_settings' );
            $archive_title = $settings['archive_title'];

        } else {

            $archive_title = $title;

        }

        // If the page is a term archive, append the term name
        if ( is_tax( 'resource_category') ) {

            // Get the term object for this resource category
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

            $archive_title = $archive_title . ' - ' . $term->name;

        }

        return $archive_title;

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
     * @param   string  $template   Template file that is being loaded.
     * @return  string              Template file that should be loaded.
     */
    function template_loader( $template ) {

        $file = '';

        if ( is_post_type_archive( 'resource_toolbox' ) || is_tax( 'resource_category' ) ) {

            $file = 'resource-loop.php';

        }

        if ( file_exists( $this->locate_resource_template_part( $file ) ) ) {

            $template = $this->locate_resource_template_part( $file );

        }

        return $template;

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
     * @param   string  $template_name          Template to load
     * @param   string  $string $template_path  Path to templates
     * @param   string  $default_path           Default path to template files
     * @return  string                          Path to the template file
     */
    public function locate_resource_template_part( $template_name, $template_path = '', $default_path = '' ) {

        // Make sure we're passing a template name
        if ( null == $template_name ) {
            return;
        }

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
     * Gets template part (for templates in loops).
     *
     * @since 1.0.0
     * @param string      $slug
     * @param string      $name (default: '')
     * @param string      $template_path (default: 'single-resource')
     * @param string|bool $default_path (default: '') False to not load a default
     */
    public function get_resource_template_part( $slug, $name = '' ) {

        $template = '';

        // If template file doesn't exist, look in yourtheme/slug-name.php and yourtheme/resource-toolbox/slug-name.php
        if ( $name ) {
            $template = $this->locate_resource_template_part( "{$slug}-{$name}.php" );
        }

        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/resource-toolbox/slug.php
        if ( ! $template ) {
            $template = $this->locate_resource_template_part( "{$slug}.php" );
        }

        // If we found a template file, load it up!
        if ( $template ) {
            load_template( $template, false );
        }

    }

    /**
     * Gets settings and metadata for single resources
     *
     * @since   1.0.0
     * @param   int     $post_id
     * @return  array   $resource_data
     */
    public static function get_single_resource_data( ) {

        // Get all of the metadata attached to this single resource
        $resource_meta = get_post_meta( get_the_ID() );

        // Get the settings for single resources
        if ( null !== get_option( 'resource_toolbox_single_resource_settings' ) ) {
            $resource_settings = get_option( 'resource_toolbox_single_resource_settings' );
        }

        $resource_data = array(
            'resource_meta'     => $resource_meta,
            'resource_settings' => $resource_settings,
            );

        return $resource_data;

    }

    /**
     * Adds extra content before/after the post for single resources
     *
     * @param string $content
     * @return string
     */
    public function resource_content( $content ) {

        global $post;

        if ( is_singular( 'resource_toolbox' ) ) {

            return $this->resource_single_content();

        } elseif ( is_post_type_archive( 'resource_toolbox' ) || is_tax( 'resource_category' ) ) {

            return $this->resource_loop_single_content();

        } else {

            return $content;

        }

    }

    /**
     * Modifies content for single resource views
     *
     * @param string $content
     * @return string
     */
    public function resource_single_content() {

        global $post;

        ob_start();

        do_action( 'resource_single_content_start' );

        $this->get_resource_template_part( 'single-resource' );

        do_action( 'resource_single_content_end' );

        return apply_filters( 'resource_toolbox_single_resource_content', ob_get_clean(), $post );
    }

    /**
     * Modifies content for each resource in archive loops
     *
     * @param string $content
     * @return string
     */
    public function resource_loop_single_content() {

        global $post;

        ob_start();

        do_action( 'resource_loop_single_content_start' );

        $this->get_resource_template_part( 'resource-loop-single' );

        do_action( 'resource_loop_single_content_end' );

        return apply_filters( 'resource_toolbox_resource_loop_single_content', ob_get_clean(), $post );
    }

}
