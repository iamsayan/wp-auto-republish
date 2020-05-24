<?php

/**
 * Helper functions.
 *
 * @since      1.1.3
 * @package    WP Auto Republish
 * @subpackage Wpar\Helpers
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Wpar\Helpers;

defined( 'ABSPATH' ) || exit;
/**
 * Meta & Option class.
 */
trait HelperFunctions
{
    /**
     * Get all registered public post types.
     *
     * @param bool $public Public type True or False.
     * @return array
     */
    protected function get_post_types( $public = true )
    {
        $post_types = get_post_types( [
            'public' => $public,
        ], 'objects' );
        $data = [];
        foreach ( $post_types as $post_type ) {
            if ( !is_object( $post_type ) ) {
                continue;
            }
            
            if ( isset( $post_type->labels ) ) {
                $label = ( $post_type->labels->name ? $post_type->labels->name : $post_type->name );
            } else {
                $label = $post_type->name;
            }
            
            if ( $label == 'Media' || $label == 'media' || $post_type->name == 'elementor_library' ) {
                continue;
            }
            // skip media
            $data[$post_type->name] = $label;
        }
        return $data;
    }
    
    /**
     * Get all registered taxonomies.
     *
     * @param bool  $public  Builtin post types True or False.
     * @param bool  $hide    Hide empty taxonomies True or False.
     * @return array
     */
    protected function get_all_taxonomies( $builtin = true, $hide = true )
    {
        $post_types = get_post_types( [
            'public'   => true,
            '_builtin' => $builtin,
        ], 'objects' );
        $post_types = ( is_array( $post_types ) ? $post_types : array() );
        $data = $attribute_taxonomy_array = [];
        
        if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_attribute_taxonomies' ) ) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            foreach ( $attribute_taxonomies as $attribute_taxonomy ) {
                $attribute_taxonomy_array[] = "pa_" . $attribute_taxonomy->attribute_name;
            }
        }
        
        $wc_taxonomy_array = [
            'product_shipping_class',
            'product_visibility',
            'product_type',
            'post_format'
        ];
        $taxonomy_array = array_merge( $attribute_taxonomy_array, $wc_taxonomy_array );
        // If $post_types value is not empty
        if ( !empty($post_types) ) {
            foreach ( $post_types as $post_type ) {
                if ( !is_object( $post_type ) ) {
                    continue;
                }
                
                if ( isset( $post_type->labels ) ) {
                    $label = ( $post_type->labels->name ? $post_type->labels->name : $post_type->name );
                } else {
                    $label = $post_type->name;
                }
                
                $post_type = $post_type->name;
                $categories_array = [];
                if ( $label == 'Media' || $label == 'media' ) {
                    continue;
                }
                // skip media
                $taxonomies = get_object_taxonomies( $post_type, 'objects' );
                // Loop on all taxonomies
                foreach ( $taxonomies as $taxonomy ) {
                    
                    if ( is_object( $taxonomy ) && !in_array( $taxonomy->name, $taxonomy_array ) ) {
                        $categories = get_terms( $taxonomy->name, [
                            'hide_empty' => $hide,
                        ] );
                        // Get categories
                        foreach ( $categories as $category ) {
                            $categories_array[$post_type . '|' . $taxonomy->name . '|' . $category->term_id] = ucwords( $taxonomy->label ) . ': ' . $category->name;
                        }
                    }
                
                }
                
                if ( !empty($categories_array) ) {
                    $data[$post_type]['label'] = $label;
                    $data[$post_type]['categories'] = $categories_array;
                    unset( $categories_array );
                }
            
            }
        }
        return $data;
    }

}