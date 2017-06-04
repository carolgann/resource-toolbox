<?php
/**
 * Resource Loop Template
 *
 * This template can be overriden by copying this file to your-theme/resource-toolbox/wrapper-end.php
 *
 * @package    Resource_Toolbox
 * @subpackage Resource_Toolbox/public
 * @author     David Laietta <david@orangeblossommedia.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Don't allow direct access

$template = get_option( 'template' );

switch ( $template ) {
    case 'twentyeleven' :
        echo '</div>';
        get_sidebar( 'shop' );
        echo '</div>';
        break;
    case 'twentytwelve' :
        echo '</div></div>';
        break;
    case 'twentythirteen' :
        echo '</div></div>';
        break;
    case 'twentyfourteen' :
        echo '</div></div></div>';
        get_sidebar( 'content' );
        break;
    case 'twentyfifteen' :
        echo '</div></article></div></div>';
        break;
    case 'twentysixteen' :
        echo '</main></div>';
        break;
    default :
        echo '</div></div>';
        break;
}