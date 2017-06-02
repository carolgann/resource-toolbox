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

global $post;

// @todo Resource Content

$settings = get_option( 'resource_toolbox_single_resource_settings' );

var_dump($settings);