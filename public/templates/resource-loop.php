<?php
/**
 * Resource Loop Template
 *
 * This template can be overriden by copying this file to your-theme/resource-toolbox/resource-loop.php
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/public
 * @author     David Laietta <david@orangeblossommedia.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Don't allow direct access

get_header();

$load = new Resource_Toolbox_Templates();

// Start the wrapper for our resource loop
$load->get_resource_template_part( 'wrapper-start' );

    if ( have_posts() ) :

        echo '<h2 class="entry-title archive-title">' . get_the_archive_title() . '</h2>';

        echo '<div id="resource-loop">';

            while ( have_posts() ) : the_post();

                the_content();

            endwhile;

        echo '</div>';

    endif;

// End the wrapper for our resource loop
$load->get_resource_template_part( 'wrapper-end' );

get_footer(); ?>