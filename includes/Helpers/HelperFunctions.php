<?php

/**
 * Helper functions.
 *
 * @since      1.1.3
 * @package    RevivePress
 * @subpackage Wpar\Helpers
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace Wpar\Helpers;

use  Wpar\Helpers\SettingsData ;
defined( 'ABSPATH' ) || exit;
/**
 * Meta & Option class.
 */
trait HelperFunctions
{
    use  SettingsData ;
    /**
     * Get all registered public post types.
     *
     * @param bool $public Public type True or False.
     * @return array
     */
    protected function get_post_types()
    {
        $post_types = get_post_types( [
            'public'   => true,
            '_builtin' => true,
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
                // skip media
            }
            
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
    protected function get_all_taxonomies( $args, $hide = false, $builtin = true )
    {
        $post_types = get_post_types( $args, 'objects' );
        $post_types = ( is_array( $post_types ) ? $post_types : [] );
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
                
                if ( $label == 'Media' || $label == 'media' || $post_type == 'elementor_library' ) {
                    continue;
                    // skip media
                }
                
                $taxonomies = get_object_taxonomies( $post_type, 'objects' );
                // Loop on all taxonomies
                foreach ( $taxonomies as $taxonomy ) {
                    
                    if ( is_object( $taxonomy ) && !in_array( $taxonomy->name, $taxonomy_array ) ) {
                        if ( $builtin && ($post_type != 'post' || !in_array( $taxonomy->name, [ 'category', 'post_tag' ] )) ) {
                            continue;
                        }
                        $categories = get_terms( $taxonomy->name, [
                            'hide_empty' => $hide,
                        ] );
                        // Get categories
                        foreach ( $categories as $category ) {
                            if ( is_object_in_taxonomy( $post_type, $taxonomy->name ) ) {
                                $categories_array[$post_type . '|' . $taxonomy->name . '|' . $category->term_id] = ucwords( $taxonomy->label ) . ': ' . $category->name;
                            }
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
    
    /**
     * Check plugin settings if enabled
     * 
     * @return bool
     */
    protected function is_enabled( $name, $prefix = false )
    {
        if ( $prefix ) {
            $name = 'wpar_' . $name;
        }
        if ( $this->get_data( $name ) == 1 ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check current user roles.
     * 
     * @since 1.3.0
     * @return bool
     */
    protected function get_roles( $can_edit_post = true )
    {
        $options = [];
        $roles = get_editable_roles();
        foreach ( $roles as $role => $details ) {
            if ( $can_edit_post && (!isset( $details['capabilities']['edit_posts'] ) || !$details['capabilities']['edit_posts']) ) {
                continue;
            }
            $options[$role] = translate_user_role( $details['name'] );
        }
        return $options;
    }
    
    /**
     * Check current user roles.
     * 
     * @return bool
     */
    protected function get_users( $args = array() )
    {
        $options = [];
        $users = get_users( [
            'fields' => [ 'ID', 'display_name' ],
        ] );
        foreach ( $users as $user ) {
            $options[$user->ID] = $user->display_name;
        }
        return $options;
    }
    
    /**
     * Convert PHP date for to JS Date Format
     * 
     * @return string
     */
    protected function php_to_js_date( $format )
    {
        switch ( $format ) {
            case 'F j, Y':
                return 'MM dd, yy';
                break;
            case 'Y/m/d':
                return 'yy/mm/dd';
                break;
            case 'm/d/Y':
                return 'mm/dd/yy';
                break;
            case 'd/m/Y':
                return 'dd/mm/yy';
                break;
            case 'Y-m-d':
                return 'yy-mm-dd';
                break;
            case 'd.m.Y':
                return 'dd.mm.yy';
                break;
        }
    }
    
    /**
     * Return Current timestamp
     * 
     * @since 1.2.6
     * @return bool
     */
    protected function current_timestamp( $gmt = false )
    {
        $local_time = current_time( 'mysql', $gmt );
        return strtotime( $local_time );
    }
    
    /**
     * Return transient name
     * 
     * @since 1.3.0
     * @return bool
     */
    protected function get_daily_allowed( $value = false )
    {
        $timestamp = $this->current_timestamp();
        $transient_name = 'wpar_daily_' . gmdate( 'Y_m_d', $timestamp );
        
        if ( $value ) {
            $transient = get_transient( $transient_name );
            
            if ( !empty($transient) && is_array( $transient ) ) {
                return $transient;
            } else {
                return false;
            }
        
        }
        
        return $transient_name;
    }
    
    /**
     * Date Time string to seconds and also from array
     * 
     * @since 1.3.0
     * @return string
     */
    protected function str_to_second( $input )
    {
        $times = explode( ' ', preg_replace( '!\\s+!', ' ', str_replace( [ ',', '-', '_' ], ' ', $input ) ) );
        $total_time = [];
        foreach ( $times as $time ) {
            $total_time[] = $this->convert_str_to_second( $time );
        }
        return array_sum( $total_time );
    }
    
    /**
     * Convert Date Time string to seconds
     * 
     * @since 1.3.0
     * @return string
     */
    protected function convert_str_to_second( $input )
    {
        $res = preg_replace( "/[^a-z]/i", '', strtolower( $input ) );
        if ( !in_array( $res, [
            'y',
            'm',
            'd',
            'w',
            'h',
            'i'
        ] ) ) {
            return $input * MINUTE_IN_SECONDS;
        }
        $input = preg_replace( "/[^0-9]/", '', $input );
        switch ( $res ) {
            case 'y':
                $output = $input * YEAR_IN_SECONDS;
                break;
            case 'm':
                $output = $input * MONTH_IN_SECONDS;
                break;
            case 'd':
                $output = $input * DAY_IN_SECONDS;
                break;
            case 'w':
                $output = $input * WEEK_IN_SECONDS;
                break;
            case 'h':
                $output = $input * HOUR_IN_SECONDS;
                break;
            case 'i':
                $output = $input * MINUTE_IN_SECONDS;
                break;
            default:
                $output = MINUTE_IN_SECONDS;
        }
        return $output;
    }
    
    /**
     * Convert comma separated post ids to array
     * 
     * @since 1.3.1
     * @return array
     */
    protected function filter_post_ids( $input )
    {
        $input = preg_replace( [
            '/[^\\d,]/',
            '/(?<=,),+/',
            '/^,+/',
            '/,+$/'
        ], '', $input );
        return explode( ',', $input );
    }
    
    /**
     * Create the recurring action event.
     *
     * @param  integer $timestamp            Timestamp.
     * @param  integer $interval_in_seconds  Interval in Seconds.
     * @param  string  $hook                 Action Hook.
     * @param  array   $args                 Parameters.
     * @param  string  $group                Group Name.
     * @return string
     */
    protected function set_recurring_action(
        $timestamp,
        $interval_in_seconds,
        $hook,
        $args = array(),
        $group = 'wp-auto-republish'
    )
    {
        $action_id = \as_schedule_recurring_action(
            $timestamp,
            $interval_in_seconds,
            $hook,
            $args,
            $group
        );
        return $action_id;
    }
    
    /**
     * Create the single action event.
     *
     * @param  integer $timestamp  Timestamp.
     * @param  string  $hook       Hook.
     * @param  array   $arg        Parameter.
     * @param  string  $group      Group Name.
     * @return string
     */
    protected function set_single_action(
        $timestamp,
        $hook,
        $args = array(),
        $group = 'wp-auto-republish'
    )
    {
        $action_id = \as_schedule_single_action(
            $timestamp,
            $hook,
            $args,
            $group
        );
        return $action_id;
    }
    
    /**
     * Unschedule all action events.
     *
     * @param  string  $hook       Hook.
     * @param  array   $arg        Parameter.
     * @param  string  $group      Group Name.
     */
    protected function unschedule_all_actions( $hook, $args = array(), $group = 'wp-auto-republish' )
    {
        \as_unschedule_all_actions( $hook, $args, $group );
    }
    
    /**
     * Unschedule last action event.
     *
     * @param  string  $hook       Hook.
     * @param  array   $arg        Parameter.
     * @param  string  $group      Group Name.
     */
    protected function unschedule_last_action( $hook, $args = array(), $group = 'wp-auto-republish' )
    {
        \as_unschedule_action( $hook, $args, $group );
    }
    
    /**
     * Check if next action is exists.
     *
     * @param  string  $hook   Action Hook.
     * @param  array   $args   Parameters.
     * @param  string  $group  Group Name.
     * @return null|string
     */
    protected function get_next_action( $hook, $args = array(), $group = 'wp-auto-republish' )
    {
        return \as_next_scheduled_action( $hook, $args, $group );
    }
    
    /**
     * Check if next action is exists.
     *
     * @param  string  $hook   Action Hook.
     * @param  array   $args   Parameters.
     * @param  string  $group  Group Name.
     * @return null|string
     */
    protected function has_next_action( $hook, $args = array(), $group = 'wp-auto-republish' )
    {
        if ( !function_exists( 'as_has_scheduled_action' ) ) {
            return \boolval( $this->get_next_action( $hook, $args, $group ) );
        }
        return \as_has_scheduled_action( $hook, $args, $group );
    }
    
    /**
     * Check if next action is exists.
     *
     * @param  string  $hook   Action Hook.
     * @param  array   $args   Parameters.
     * @param  string  $group  Group Name.
     * @return null|string
     */
    protected function get_next_action_by_data(
        $hook,
        $timestamp,
        $args,
        $group = 'wp-auto-republish'
    )
    {
        return \as_get_scheduled_actions( [
            'hook'         => $hook,
            'args'         => $args,
            'date'         => gmdate( 'U', $timestamp ),
            'date_compare' => '=',
            'group'        => $group,
            'status'       => \ActionScheduler_Store::STATUS_PENDING,
            'per_page'     => 1,
            'orderby'      => 'date',
            'order'        => 'ASC',
        ], 'ids' );
    }

}