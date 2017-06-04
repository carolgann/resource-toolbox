<?php
/**
 * Single Resource Template
 *
 * This template can be overriden by copying this file to your-theme/resource-toolbox/single-resource.php
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/public
 * @author     David Laietta <david@orangeblossommedia.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Don't allow direct access

// Get the settings and metadata for this resource
$resource_data = Resource_Toolbox_Templates::get_single_resource_data();

echo '<div class="resource-meta">';

    // Display pricing of resource
    if ( isset( $resource_data ) && '' !== $resource_data['resource_meta']['resource_information_resource-cost'][0] ) {
        $cost = $resource_data['resource_meta']['resource_information_resource-cost'][0];
        echo "<div class='resource-cost'><strong>Cost:</strong> {$cost}</div>";
    }

    // Display pricing of resource
    if ( isset( $resource_data ) && '' !== $resource_data['resource_meta']['resource_information_resource-url'][0] ) {
        $url = esc_url( $resource_data['resource_meta']['resource_information_resource-url'][0], array( 'http', 'https' ) );
        echo "<div class='resource-url'><strong>Website:</strong> <a href='{$url}' rel='nofollow'>{$url}</a></div>";
    }

    echo '<div class="resource-terms">';

        // Display Free badge if this resource is marked as free
        if ( isset( $resource_data ) && true == $resource_data['resource_meta']['resource_information_free-resource'][0] ) {
            echo '<a class="resource-term" href="#">Free</a>';
        }

        // Display resource categories
        $terms = wp_get_post_terms( get_the_ID(), 'resource_category' );
        if ( null !== $terms ) {

            foreach ( $terms as $term ) {
                $term_link = get_term_link( (int) $term->term_id, 'resource_category' );
                echo "<a class='resource-term' href='{$term_link}'>{$term->name}</a>";
            }

        }

    echo '</div>'; // <!-- .resource-terms -->

echo '</div>'; // <!-- .resource-meta -->

// Display content description of resource
echo '<div class="resource-content">';
    echo get_the_content();
echo '</div>'; // <!-- .resource-content -->

