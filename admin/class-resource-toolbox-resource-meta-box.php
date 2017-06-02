<?php
/**
 * The metaboxes for the resource CPT
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/public
 * @author     David Laietta <david@orangeblossommedia.com>
 */
class Resource_Meta_Box {

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

    private $screens = array(
        'resource_toolbox',
    );
    private $fields = array(
        array(
            'id' => 'resource-checkbox',
            'label' => 'resource-checkbox',
            'type' => 'checkbox',
        ),
        array(
            'id' => 'resource-radio',
            'label' => 'resource-radio',
            'type' => 'radio',
            'options' => array(
                'option 1',
                'Option 2',
                'Option three',
            ),
        ),
        array(
            'id' => 'resource-select',
            'label' => 'resource-select',
            'type' => 'select',
            'options' => array(
                'Select 1',
                'select two',
                'select 3',
            ),
        ),
        array(
            'id' => 'resource-text',
            'label' => 'resource-text',
            'type' => 'text',
        ),
        array(
            'id' => 'resource-textarea',
            'label' => 'resource-textarea',
            'type' => 'textarea',
        ),
        array(
            'id' => 'resource-url',
            'label' => 'resource-url',
            'type' => 'url',
        ),
    );

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

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_post' ) );

    }

    /**
     * Hooks into WordPress' add_meta_boxes function.
     * Goes through screens (post types) and adds the meta box.
     */
    public function add_meta_boxes() {

        foreach ( $this->screens as $screen ) {

            add_meta_box(
                'resource-information',
                __( 'Resource Information', $this->plugin_name ),
                array( $this, 'add_meta_box_callback' ),
                $screen,
                'normal',
                'high'
            );

        }

    }

    /**
     * Generates the HTML for the meta box
     *
     * @param object $post WordPress post object
     */
    public function add_meta_box_callback( $post ) {

        wp_nonce_field( 'resource_information_data', 'resource_information_nonce' );
        echo 'Information about the resource that will be visible on the frontend of the site.';
        $this->generate_fields( $post );

    }

    /**
     * Generates the field's HTML for the meta box.
     */
    public function generate_fields( $post ) {

        $output = '';

        foreach ( $this->fields as $field ) {

            $label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
            $db_value = get_post_meta( $post->ID, 'resource_information_' . $field['id'], true );

            switch ( $field['type'] ) {

                case 'checkbox':
                    $input = sprintf(
                        '<input %s id="%s" name="%s" type="checkbox" value="1">',
                        $db_value === '1' ? 'checked' : '',
                        $field['id'],
                        $field['id']
                    );
                    break;

                case 'radio':
                    $input = '<fieldset>';
                    $input .= '<legend class="screen-reader-text">' . $field['label'] . '</legend>';
                    $i = 0;
                    foreach ( $field['options'] as $key => $value ) {
                        $field_value = !is_numeric( $key ) ? $key : $value;
                        $input .= sprintf(
                            '<label><input %s id="%s" name="%s" type="radio" value="%s"> %s</label>%s',
                            $db_value === $field_value ? 'checked' : '',
                            $field['id'],
                            $field['id'],
                            $field_value,
                            $value,
                            $i < count( $field['options'] ) - 1 ? '<br>' : ''
                        );
                        $i++;
                    }
                    $input .= '</fieldset>';
                    break;

                case 'select':
                    $input = sprintf(
                        '<select id="%s" name="%s">',
                        $field['id'],
                        $field['id']
                    );
                    foreach ( $field['options'] as $key => $value ) {
                        $field_value = !is_numeric( $key ) ? $key : $value;
                        $input .= sprintf(
                            '<option %s value="%s">%s</option>',
                            $db_value === $field_value ? 'selected' : '',
                            $field_value,
                            $value
                        );
                    }
                    $input .= '</select>';
                    break;

                case 'textarea':
                    $input = sprintf(
                        '<textarea class="large-text" id="%s" name="%s" rows="5">%s</textarea>',
                        $field['id'],
                        $field['id'],
                        $db_value
                    );
                    break;

                default:
                    $input = sprintf(
                        '<input %s id="%s" name="%s" type="%s" value="%s">',
                        $field['type'] !== 'color' ? 'class="regular-text"' : '',
                        $field['id'],
                        $field['id'],
                        $field['type'],
                        $db_value
                    );

            }

            $output .= $this->row_format( $label, $input );

        }

        echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';

    }

    /**
     * Generates the HTML for table rows.
     */
    public function row_format( $label, $input ) {

        return sprintf(
            '<tr><th scope="row">%s</th><td>%s</td></tr>',
            $label,
            $input
        );

    }
    /**
     * Hooks into WordPress' save_post function
     */
    public function save_post( $post_id ) {

        if ( ! isset( $_POST['resource_information_nonce'] ) )
            return $post_id;

        $nonce = $_POST['resource_information_nonce'];

        if ( !wp_verify_nonce( $nonce, 'resource_information_data' ) )
            return $post_id;

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;

        foreach ( $this->fields as $field ) {

            if ( isset( $_POST[ $field['id'] ] ) ) {

                switch ( $field['type'] ) {

                    case 'email':
                        $_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
                        break;

                    case 'text':
                        $_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
                        break;

                }

                update_post_meta( $post_id, 'resource_information_' . $field['id'], $_POST[ $field['id'] ] );

            } else if ( $field['type'] === 'checkbox' ) {

                update_post_meta( $post_id, 'resource_information_' . $field['id'], '0' );

            }

        }

    }

}