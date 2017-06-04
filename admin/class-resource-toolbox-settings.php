<?php

/**
 * The settings functionality for the plugin.
 *
 * @link       https://davidlaietta.com
 * @since      1.0.0
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/admin
 */

/**
 * The settings functionality for the plugin.
 *
 * Creates a settings page and tabs for resources management.
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/admin
 * @author     David Laietta <david@orangeblossommedia.com>
 */
class Resource_Toolbox_Settings {

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
     * Creates main settings menu page, as well as submenu page
     */
    public function setup_plugin_options_menu() {

        add_submenu_page(
            'edit.php?post_type=resource_toolbox',
            __( 'Resource Toolbox Settings', $this->plugin_name ),
            __( 'Resource Toolbox', $this->plugin_name ),
            'manage_options',
            'resource_toolbox_settings',
            array( $this, 'render_settings_page_content' )
        );

    }

    /**
     * Provide default values for the general settings
     *
     * @return array
     */
    static function default_general_settings() {

        $defaults = array(
            'archive_title' => 'Resources',
        );

        update_option( 'resource_toolbox_general_settings', $defaults );

    }

    /**
     * Provide default values for the single resource settings
     *
     * @return array
     */
    static function default_single_resource_settings() {

        $defaults = array(
            'enable_excerpts'  => '0',
            'enable_discussion'  => '0',
            'enable_last_updated'  => '1',
        );

        update_option( 'resource_toolbox_single_resource_settings', $defaults );

    }

    /**
     * Provide default values for the resource loop settings
     *
     * @return array
     */
    static function default_resource_loop_settings() {

        $defaults = array(
            'images_in_loop'  => '1',
            'sort_loop'  => '1',
        );

        update_option( 'resource_toolbox_resource_loop_settings', $defaults );

    }


    /**
     * Renders a simple page to display for the theme menu defined above.
     */
    public function render_settings_page_content( $active_tab = '' ) {
        ?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class="wrap">

            <h2><?php _e( 'Resource Toolbox Settings', $this->plugin_name ); ?></h2>
            <?php
            // Display any settings errors registered to the settings_error hook
            settings_errors();

            $tabs = array(
                'general' => array(
                    'slug'  => 'general_settings',
                    'title' => 'General',
                    ),
                'single_resource' => array(
                    'slug'  => 'single_resource_settings',
                    'title' => 'Single Resource',
                    ),
                'resource_loop' => array(
                    'slug'  => 'resource_loop_settings',
                    'title' => 'Resource Loop',
                    ),
                );

            // Determine which tab we're on
            if ( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = $_GET[ 'tab' ];
            } else if ( $active_tab == $tabs['general']['slug'] ) {
                $active_tab = $tabs['general']['slug'];
            } else if ( $active_tab == $tabs['single_resource']['slug'] ) {
                $active_tab = $tabs['single_resource']['slug'];
            } else if ( $active_tab == $tabs['resource_loop']['slug'] ) {
                $active_tab = $tabs['resource_loop']['slug'];
            } else {
                $active_tab = $tabs['general']['slug'];
            }
            ?>

            <h2 class="nav-tab-wrapper">
                <?php
                foreach ( $tabs as $tab ) {

                    if ( $active_tab == $tab['slug'] ) {
                        $active_class = 'nav-tab-active';
                    } else {
                        $active_class = '';
                    }

                    echo "<a href='?post_type=resource_toolbox&page=resource_toolbox_settings&tab={$tab['slug']}' class='nav-tab {$active_class}'>" . __( $tab['title'], $this->plugin_name ) . "</a>";

                }
                ?>
            </h2>

            <form method="post" action="options.php">
                <?php

                if ( $active_tab == $tabs['general']['slug'] ) {

                    settings_fields( 'resource_toolbox_general_settings' );
                    do_settings_sections( 'resource_toolbox_general_settings' );

                } elseif ( $active_tab == $tabs['single_resource']['slug'] ) {

                    settings_fields( 'resource_toolbox_single_resource_settings' );
                    do_settings_sections( 'resource_toolbox_single_resource_settings' );

                } elseif ( $active_tab == $tabs['resource_loop']['slug'] ) {

                    settings_fields( 'resource_toolbox_resource_loop_settings' );
                    do_settings_sections( 'resource_toolbox_resource_loop_settings' );

                } else {

                    settings_fields( 'resource_toolbox_general_settings' );
                    do_settings_sections( 'resource_toolbox_general_settings' );

                }

                submit_button();

                ?>
            </form>

        </div><!-- /.wrap -->
    <?php
    }


    /**
     * This function provides a simple description for the general settings page
     */
    public function general_settings_callback() {

        echo '<p>' . __( 'Modify General Settings', $this->plugin_name ) . '</p>';

    }

    /**
     * This function provides a simple description for the single resource settings page
     */
    public function single_resource_settings_callback() {

        echo '<p>' . __( 'Modify Single Resource Settings', $this->plugin_name ) . '</p>';

    }

    /**
     * This function provides a simple description for the resource loop settings page
     */
    public function resource_loop_settings_callback() {

        echo '<p>' . __( 'Modify Resource Loop Settings', $this->plugin_name ) . '</p>';

    }


    /**
     * Initializes the general settings by registering the Sections, Fields, and Settings.
     *
     * This function is registered with the 'admin_init' hook.
     */
    public function initialize_general_settings() {

        add_settings_section(
            'general_settings_section',                         // ID used to identify this section and with which to register options
            __( 'General Settings', $this->plugin_name ),       // Title to be displayed on the administration page
            array( $this, 'general_settings_callback'),         // Callback used to render the description of the section
            'resource_toolbox_general_settings'                 // Page on which to add this section of options
        );

        add_settings_field(
            'archive_title',
            __( 'Main Resources Page Title', $this->plugin_name ),
            array( $this, 'text_input_callback'),
            'resource_toolbox_general_settings',
            'general_settings_section',
            array( 'label_for' => 'archive_title', 'option_group' => 'resource_toolbox_general_settings', 'option_id' => 'archive_title' )
        );

        register_setting(
            'resource_toolbox_general_settings',
            'resource_toolbox_general_settings'
        );

    }

    /**
     * Initializes the single resource settings by registering the Sections, Fields, and Settings.
     *
     * This function is registered with the 'admin_init' hook.
     */
    public function initialize_single_resource_settings() {

        add_settings_section(
            'single_resource_settings_section',                         // ID used to identify this section and with which to register options
            __( 'Single Resource Settings', $this->plugin_name ),       // Title to be displayed on the administration page
            array( $this, 'single_resource_settings_callback'),         // Callback used to render the description of the section
            'resource_toolbox_single_resource_settings'                 // Page on which to add this section of options
        );

        add_settings_field(
            'enable_excerpts',
            __( 'Should excerpts be enabled on Resources?', $this->plugin_name ),
            array( $this, 'checkbox_input_callback'),
            'resource_toolbox_single_resource_settings',
            'single_resource_settings_section',
            array( 'label_for' => 'enable_excerpts', 'option_group' => 'resource_toolbox_single_resource_settings', 'option_id' => 'enable_excerpts', 'option_description' => 'Enable Excerpts' )
        );

        add_settings_field(
            'enable_discussion',
            __( 'Should comments be enabled on Resources?', $this->plugin_name ),
            array( $this, 'checkbox_input_callback'),
            'resource_toolbox_single_resource_settings',
            'single_resource_settings_section',
            array( 'label_for' => 'enable_discussion', 'option_group' => 'resource_toolbox_single_resource_settings', 'option_id' => 'enable_discussion', 'option_description' => 'Enable Comments' )
        );

        add_settings_field(
            'enable_last_updated',
            __( 'Should the date that this resource was last updated be displayed?', $this->plugin_name ),
            array( $this, 'checkbox_input_callback'),
            'resource_toolbox_single_resource_settings',
            'single_resource_settings_section',
            array( 'label_for' => 'enable_last_updated', 'option_group' => 'resource_toolbox_single_resource_settings', 'option_id' => 'enable_last_updated', 'option_description' => 'Display last updated date' )
        );

        register_setting(
            'resource_toolbox_single_resource_settings',
            'resource_toolbox_single_resource_settings'
        );

    }

    /**
     * Initializes the resource loop settings by registering the Sections, Fields, and Settings.
     *
     * This function is registered with the 'admin_init' hook.
     */
    public function initialize_resource_loop_settings() {

        add_settings_section(
            'resource_loop_settings_section',                         // ID used to identify this section and with which to register options
            __( 'Resource Loop Settings', $this->plugin_name ),       // Title to be displayed on the administration page
            array( $this, 'resource_loop_settings_callback'),         // Callback used to render the description of the section
            'resource_toolbox_resource_loop_settings'                 // Page on which to add this section of options
        );

        add_settings_field(
            'images_in_loop',
            __( 'Should images be displayed?', $this->plugin_name ),
            array( $this, 'checkbox_input_callback'),
            'resource_toolbox_resource_loop_settings',
            'resource_loop_settings_section',
            array( 'label_for' => 'images_in_loop', 'option_group' => 'resource_toolbox_resource_loop_settings', 'option_id' => 'images_in_loop', 'option_description' => 'Display Images' )
        );

        add_settings_field(
            'sort_loop',
            __( 'Should resource categories be sortable?', $this->plugin_name ),
            array( $this, 'checkbox_input_callback'),
            'resource_toolbox_resource_loop_settings',
            'resource_loop_settings_section',
            array( 'label_for' => 'sort_loop', 'option_group' => 'resource_toolbox_resource_loop_settings', 'option_id' => 'sort_loop', 'option_description' => 'Allow Category Sorting' )
        );

        register_setting(
            'resource_toolbox_resource_loop_settings',
            'resource_toolbox_resource_loop_settings'
        );

    }


    /**
     * Input Callbacks
     */
    public function text_input_callback( $text_input ) {

        // Get arguments from setting
        $option_group = $text_input['option_group'];
        $option_id = $text_input['option_id'];
        $option_name = "{$option_group}[{$option_id}]";

        // Get existing option from database
        $options = get_option( $option_group );
        $option_value = isset( $options[$option_id] ) ? $options[$option_id] : '0';

        // Render the output
        echo "<input type='text' id='{$option_id}' name='{$option_name}' value='{$option_value}' />";

    }

    public function checkbox_input_callback( $checkbox_input ) {

        // Get arguments from setting
        $option_group = $checkbox_input['option_group'];
        $option_id = $checkbox_input['option_id'];
        $option_name = "{$option_group}[{$option_id}]";
        $option_description = $checkbox_input['option_description'];

        // Get existing option from database
        $options = get_option( $option_group );
        $option_value = isset( $options[$option_id] ) ? $options[$option_id] : "";

        // Render the output
        $input = '';
        $input .= "<input type='checkbox' id='{$option_id}' name='{$option_name}' value='1' " . checked( $option_value, 1, false ) . " />";
        $input .= "<label for='{$option_id}'>{$option_description}</label>";

        echo $input;

    }


    // Validate inputs
    public function validate_inputs( $input ) {
        // Create our array for storing the validated options
        $output = array();
        // Loop through each of the incoming options
        foreach( $input as $key => $value ) {
            // Check to see if the current option has a value. If so, process it.
            if ( isset( $input[$key] ) ) {
                // Strip all HTML and PHP tags and properly handle quoted strings
                $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
            }
            // elseif ( $input[$key] === NULL ) {
            //  $output[$key] = '';
            // }
        } // end foreach
        // Return the array processing any additional functions filtered by this action
        return apply_filters( 'validate_inputs', $output, $input );
    }

}