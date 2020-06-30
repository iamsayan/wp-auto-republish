<?php

/**
 * Show Original Republish Data.
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Wpar\Core
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Wpar\Core;

use  Wpar\Helpers\Hooker ;
use  Wpar\Helpers\SettingsData ;
defined( 'ABSPATH' ) || exit;
/**
 * Republish info class.
 */
class RepublishInfo
{
    use  Hooker, SettingsData ;
    /**
     * Register functions.
     */
    public function register()
    {
        $this->filter( 'the_content', 'show_republish_info', $this->do_filter( 'published_date_priority', 10 ) );
    }
    
    /**
     * Show original publish info.
     * 
     * @param string  $content  Original Content
     * @return string $content  Filtered Content
     */
    public function show_republish_info( $content )
    {
        // get wordpress date time format
        $get_df = get_option( 'date_format' );
        $get_tf = get_option( 'time_format' );
        $wpar_show_pubdate = $this->get_data( 'wpar_republish_position' );
        $wpar_text = wp_kses_post( $this->get_data( 'wpar_republish_position_text' ) );
        $wpar_original_pub_date = $this->get_meta( get_the_ID(), '_wpar_original_pub_date' );
        $local_date = date( $this->do_filter( 'published_date_format', $get_df . ' @ ' . $get_tf ), strtotime( $wpar_original_pub_date ) );
        $dateline = '';
        
        if ( isset( $wpar_original_pub_date ) && $wpar_original_pub_date != '' ) {
            $dateline .= '<p id="wpar" class="wpar-pubdate" style="font-size: 12px;">';
            $dateline .= '<span class="wpar-label">' . $wpar_text . '</span><span class="wpar-time">' . $local_date;
            $dateline .= '</p>';
        }
        
        
        if ( isset( $wpar_show_pubdate ) && $wpar_show_pubdate == 'before_content' ) {
            $content = $dateline . $content;
        } elseif ( isset( $wpar_show_pubdate ) && $wpar_show_pubdate == 'after_content' ) {
            $content = $content . $dateline;
        }
        
        return $content;
    }

}