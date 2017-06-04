<?php
/**
 * Resource Loop Single Template
 *
 * This template can be overriden by copying this file to your-theme/resource-toolbox/resource-loop-single.php
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/public
 * @author     David Laietta <david@orangeblossommedia.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Don't allow direct access

// Get the settings and metadata for this resource
$resource_data = Resource_Toolbox_Templates::get_single_resource_data();

?>

<div class="single-resource">

    <a href="<?php the_permalink(); ?>" rel="bookmark">

        <div class="single-resource-image">
            <?php the_post_thumbnail(); ?>
        </div>

        <div class="single-resource-title">
            <?php the_title(); ?>
        </div>

    </a>

    <div class="resource-meta">

        <div class="resource-terms">

            <?php
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
            ?>

        </div> <!-- .resource-terms -->

    </div> <!-- .resource-meta -->

</div> <!-- .single-resource -->

