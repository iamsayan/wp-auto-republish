<?php

/**
 * Helper functions.
 *
 * @since      1.1.3
 * @package    RevivePress
 * @subpackage RevivePress\Helpers
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Helpers;

use  DateTime ;
use  RevivePress\Helpers\Sitepress ;
use  RevivePress\Helpers\SettingsData ;
defined( 'ABSPATH' ) || exit;
/**
 * Helper Functions class.
 */
trait HelperFunctions
{
    use  SettingsData ;

    /**
     * Get all registered public post types.
     *
     * @return array
     */
    protected function get_post_types( $only_types = false )
    {
        $args = array(
            'public'   => true,
            '_builtin' => true,
        );
        $data = array();
        $post_types = \get_post_types( $args, 'objects' );
        foreach ( $post_types as $post_type ) {
            if ( ! is_object( $post_type ) ) {
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
            
            $data[ $post_type->name ] = $label;
        }
        return ( $only_types ? array_keys( $data ) : $data );
    }
    
    /**
     * Get all registered taxonomies.
     *
     * @param array $args    Query args.
     * @param bool  $public  Builtin post types True or False.
     * @param bool  $hide    Hide empty taxonomies True or False.
     * @return array
     */
    protected function get_taxonomies( $args, $hide = false, $builtin = true )
    {
        /**
         * Remove WPML filters while getting terms, to get all languages
         */
        Sitepress::get()->remove_term_filters();
        $post_types = \get_post_types( $args, 'objects' );
        $post_types = ( is_array( $post_types ) ? $post_types : array() );
        $data = $attribute_taxonomy_array = array();
        
        if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_attribute_taxonomies' ) ) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            foreach ( $attribute_taxonomies as $attribute_taxonomy ) {
                $attribute_taxonomy_array[] = "pa_" . $attribute_taxonomy->attribute_name;
            }
        }
        
        $wc_taxonomy_array = array(
            'product_shipping_class',
            'product_visibility',
            'product_type',
            'post_format',
        );
        $taxonomy_array = array_merge( $attribute_taxonomy_array, $wc_taxonomy_array );
        // If $post_types value is not empty
        if ( ! empty($post_types) ) {
            foreach ( $post_types as $post_type ) {
                if ( ! is_object( $post_type ) ) {
                    continue;
                }
                
                if ( isset( $post_type->labels ) ) {
                    $label = ( $post_type->labels->name ? $post_type->labels->name : $post_type->name );
                } else {
                    $label = $post_type->name;
                }
                
                $post_type = $post_type->name;
                $terms_array = array();
                
                if ( $label == 'Media' || $label == 'media' || $post_type == 'elementor_library' ) {
                    continue;
                    // skip media
                }
                
                $taxonomies = \get_object_taxonomies( $post_type, 'objects' );
                // Loop on all taxonomies
                foreach ( $taxonomies as $taxonomy ) {
                    
                    if ( is_object( $taxonomy ) && ! in_array( $taxonomy->name, $taxonomy_array ) ) {
                        if ( $builtin && ('post' !== $post_type || ! in_array( $taxonomy->name, array( 'category', 'post_tag' ) )) ) {
                            continue;
                        }
                        $terms_array = array();
                        $terms = \get_terms( array(
                            'taxonomy'   => $taxonomy->name,
                            'hide_empty' => $hide,
                            'lang'       => '',
                        ) );
                        foreach ( $terms as $term ) {
                            $terms_array[ $taxonomy->name . '|' . $term->term_id ] = ucwords( $taxonomy->label ) . ': ' . $term->name;
                        }
                        // append post type names
                        $category_label = ( ! empty($data[ $taxonomy->name ]['label']) ? $data[ $taxonomy->name ]['label'] . ' + ' . $label : $label );
                        // insert data into array
                        $data[ $taxonomy->name ]['label'] = $category_label;
                        $data[ $taxonomy->name ]['categories'] = $terms_array;
                    }                
}
            }
        }
        /**
         * Register WPML filters back
         */
        Sitepress::get()->restore_term_filters();
        return $data;
    }
    
    /**
     * Get posts
     *
     * @param array $args WP_Query args.
     * @return array
     */
    protected function get_posts( $args )
    {
        $current_language = \apply_filters( 'wpml_current_language', null );
        // changes the language of global query to use the specfied language
        \do_action( 'wpml_switch_language', 'all' );
        // prevent cache
        $args = \array_merge( $args, array(
            'cache_results'          => false,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'lang'                   => '',
        ) );
        // get posts
        $posts = \get_posts( $args );
        // set language back to original
        \do_action( 'wpml_switch_language', $current_language );
        return $posts;
    }
    
    /**
     * Check plugin settings if enabled
     * 
     * @return bool
     */
    protected function is_enabled( $name, $prefix = false )
    {
        $name = ( $prefix ? 'wpar_' . $name : $name );
        if ( $this->get_data( $name ) == 1 ) {
            return true;
        }
        return false;
    }
    
    /**
     * Get available post statuses
     * 
     * @since 1.4.8
     * @return array
     */
    protected function get_post_statuses()
    {
        $statuses = get_post_statuses();
        return apply_filters( 'wpar/post_statuses', $statuses );
    }
    
    /**
     * Check current user roles.
     * 
     * @since 1.3.0
     * @return bool
     */
    protected function get_roles( $can_edit_post = false )
    {
        $options = array();
        $roles = \get_editable_roles();
        foreach ( $roles as $role => $details ) {
            if ( $can_edit_post && ( ! isset( $details['capabilities']['edit_posts'] ) || ! $details['capabilities']['edit_posts']) ) {
                continue;
            }
            $options[ $role ] = \translate_user_role( $details['name'] );
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
        $options = array();
        $users = \get_users( array(
            'fields' => array( 'ID', 'display_name' ),
        ) );
        foreach ( $users as $user ) {
            $options[ $user->ID ] = $user->display_name;
        }
        return $options;
    }
    
    /**
     * Insert the plugins settings in proper place.
     *
     * @param  array   $array     Default setting fields.
     * @param  integer $position  Insertion position.
     * @param  array   $insert    Field.
     * @return array
     */
    protected function insert_settings( $array, $position, $insert )
    {
        $array = array_merge( array_slice( $array, 0, $position ), $insert, array_slice( $array, $position ) );
        return $array;
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
                $format = 'MM dd, yy';
                break;
            case 'Y/m/d':
                $format = 'yy/mm/dd';
                break;
            case 'm/d/Y':
                $format = 'mm/dd/yy';
                break;
            case 'd/m/Y':
                $format = 'dd/mm/yy';
                break;
            case 'Y-m-d':
                $format = 'yy-mm-dd';
                break;
            case 'd.m.Y':
                $format = 'dd.mm.yy';
                break;
        }
        return $format;
    }
    
    /**
     * Return Current timestamp
     * 
     * @since 1.2.6
     * @return bool
     */
    protected function current_timestamp( $gmt = false )
    {
        $local_time = current_time( 'timestamp', $gmt );
        // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
        return $local_time;
    }
    
    /**
     * Return transient value
     * 
     * @since 1.3.0
     * @return int
     */
    protected function get_daily_completed()
    {
        $timestamp = $this->current_timestamp();
        $transient_name = 'wpar_daily_' . date( 'Y_m_d', $timestamp );
        $numbers_proceed = get_transient( $transient_name );
        if ( ! $numbers_proceed ) {
            return 0;
        }
        return count( $numbers_proceed );
    }
    
    /**
     * Determines if a post exists based on title, content, date and type.
     *
     * @since 1.3.2
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param string $title   Post title.
     * @param string $content Optional. Post content.
     * @param string $date    Optional. Post date.
     * @param string $type    Optional. Post type.
     * @return int Post ID if post exists, 0 otherwise.
     */
    protected function post_exists(
        $title,
        $content = '',
        $date = '',
        $type = ''
    )
    {
        if ( ! function_exists( 'post_exists' ) ) {
            require_once ABSPATH . 'wp-admin/includes/post.php';
        }
        return \post_exists(
            $title,
            $content,
            $date,
            $type
        );
    }
    
    /**
     * Date Time string to seconds and also from array
     * 
     * @since 1.3.0
     * @return string
     */
    protected function str_to_second( $input )
    {
        $times = explode( ' ', preg_replace( '!\\s+!', ' ', str_replace( array( ',', '-', '_' ), ' ', $input ) ) );
        $total_time = 0;
        foreach ( $times as $time ) {
            $total_time += $this->convert_str_to_second( $time );
        }
        return $total_time;
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
        if ( ! in_array( $res, array(
            'y',
            'm',
            'd',
            'w',
            'h',
            'i',
        ) ) ) {
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
        return (int) $output;
    }
    
    /**
     * Determines whether the current screen is an edit post screen.
     *
     * @since 1.3.2
     * @return bool Whether or not the current screen is editing an existing post.
     */
    protected function is_edit_post_screen()
    {
        if ( ! \is_admin() ) {
            return false;
        }
        $current_screen = \get_current_screen();
        return $current_screen->base === 'post' && $current_screen->action !== 'add';
    }
    
    /**
     * Determines whether the current screen is an new post screen.
     *
     * @since 1.3.2
     * @return bool Whether or not the current screen is editing an new post.
     */
    protected function is_new_post_screen()
    {
        if ( ! \is_admin() ) {
            return false;
        }
        $current_screen = \get_current_screen();
        return $current_screen->base === 'post' && $current_screen->action === 'add';
    }
    
    /**
     * Determines if we are currently editing a post with Classic editor.
     *
     * @since 1.3.2
     * @return bool Whether we are currently editing a post with Classic editor.
     */
    protected function is_classic_editor()
    {
        if ( ! $this->is_edit_post_screen() && ! $this->is_new_post_screen() ) {
            return false;
        }
        $screen = \get_current_screen();
        if ( $screen->is_block_editor() ) {
            return false;
        }
        return true;
    }
    
    /**
     * Determines if we are currently editing a post with Block editor.
     *
     * @since 1.3.2
     * @return bool Whether we are currently editing a post with Block editor.
     */
    protected function is_block_editor()
    {
        $screen = \get_current_screen();
        if ( \method_exists( $screen, 'is_block_editor' ) && $screen->is_block_editor() ) {
            return true;
        }
        return false;
    }
    
    /**
     * Determines whether the passed post type is public and shows an admin bar.
     *
     * @param string $post_type The post_type to copy.
     *
     * @since 1.3.2
     * @return bool Whether or not the post can be copied to a new draft.
     */
    protected function post_type_has_admin_bar( $post_type )
    {
        $post_type_object = \get_post_type_object( $post_type );
        if ( empty($post_type_object) ) {
            return false;
        }
        return $post_type_object->public && $post_type_object->show_in_admin_bar;
    }
    
    /**
     * Convert comma separated post ids to array
     * 
     * @since 1.3.1
     * @return array
     */
    protected function filter_post_ids( $input )
    {
        
        if ( ! is_array( $input ) ) {
            $input = preg_replace( array(
                '/[^\\d,]/',
                '/(?<=,),+/',
                '/^,+/',
                '/,+$/',
            ), '', $input );
            $input = explode( ',', $input );
        }
        
        return array_map( 'intval', $input );
    }
    
    /**
     * Can proceed with external API
     * 
     * @since 1.3.2
     * @return bool
     */
    protected function can_proceed( $action, $args )
    {
        if ( empty($args) || ! is_array( $args ) ) {
            return true;
        }
        if ( isset( $args[ $action ] ) && false === $args[ $action ] ) {
            return false;
        }
        return true;
    }
    
    /**
     * Validate date format
     * 
     * @since 1.3.2
     * @return bool
     */
    protected function validate_date( $date, $format = 'Y-m-d H:i:s' )
    {
        $date_time = DateTime::createFromFormat( $format, $date );
        return $date_time && $date_time->format( $format ) == $date;
    }
    
    /**
     * Process taxonomies from settings.
     * 
     * @since 1.4.9
     * @return array
     */
    protected function process_taxonomy( $taxonomy )
    {
        $data = explode( '|', $taxonomy );
        if ( count( $data ) > 2 ) {
            array_shift( $data );
        }
        return $data;
    }
}