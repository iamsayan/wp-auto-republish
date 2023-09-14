<?php

/**
 * The Updates routine for version 1.3.2
 *
 * @since      1.3.2
 * @package    RevivePress
 * @subpackage RevivePress\Tools\updates
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
defined( 'ABSPATH' ) || exit;
/**
 * v1.3.2 migration
 */
function revivepress_1_3_2_migration()
{
    as_unschedule_all_actions( 'wpar/global_republish_fetch_posts' );
    $permalink_structure = get_option( 'permalink_structure' );
    $permalink_structure = str_replace( '%wpar_', '%rvp_', $permalink_structure );
    update_option( 'permalink_structure', $permalink_structure );
    flush_rewrite_rules();
}

revivepress_1_3_2_migration();