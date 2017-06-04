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

var_dump(get_option( 'template' ));

$load->get_resource_template_part( 'wrapper-start' );

    if (have_posts()) :

        while (have_posts()) : the_post();

        ?>
        <div class="post">
            <h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
        </div>
        <?php
        endwhile;

    endif;

$load->get_resource_template_part( 'wrapper-end' );

get_footer(); ?>