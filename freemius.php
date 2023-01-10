<?php

/**
 * Init Freemius SDK.
 */
defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'revivepress_fs' ) ) {
    function revivepress_fs() {
        global  $revivepress_fs ;
        
        if ( ! isset( $revivepress_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
            $revivepress_fs = fs_dynamic_init( [
                'id'             => '5789',
                'slug'           => 'wp-auto-republish',
                'type'           => 'plugin',
                'public_key'     => 'pk_94e7891c5190ae1f9af5110b0f6eb',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => [
					'slug'        => 'revivepress',
					'support'     => false,
					'affiliation' => false,
				],
                'is_live'        => true,
            ] );
        }
        
        return $revivepress_fs;
    }
}