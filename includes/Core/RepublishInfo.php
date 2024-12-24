<?php

/**
 * Show Original Republish Data.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Core
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Core;

use  RevivePress\Helpers\Hooker ;
use  RevivePress\Helpers\SettingsData ;
defined( 'ABSPATH' ) || exit;
/**
 * Republish info class.
 */
class RepublishInfo
{
    use  Hooker ;
    use  SettingsData ;

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
        // get WordPress date time format
        $get_df = get_option( 'date_format' );
        $get_tf = get_option( 'time_format' );
        $format = $get_df . ' @ ' . $get_tf;
        $wpar_show_pubdate = $this->get_data( 'wpar_republish_position' );
        $wpar_text = wp_kses_post( $this->get_data( 'wpar_republish_position_text' ) );
        $wpar_original_pub_date = $this->get_meta( get_the_ID(), '_wpar_original_pub_date' );
        
        if ( ! empty($wpar_original_pub_date) ) {
            $local_date = date_i18n( $this->do_filter( 'published_date_format', $format ), strtotime( $wpar_original_pub_date ) );
            $dateline = '<p id="wpar-pubdate" class="wpar-pubdate wpar-pubdate-container">';
            $dateline .= '<span class="wpar-label">' . $wpar_text . '</span><span class="wpar-time">' . $local_date;
            $dateline .= '</p>';
        }
        
        if ( ! isset( $dateline ) ) {
            return $content;
        }
        
        if ( $wpar_show_pubdate === 'before_content' ) {
            $content = $dateline . $content;
        } elseif ( $wpar_show_pubdate === 'after_content' ) {
            $content = $content . $dateline;
        }
        
        return $content;
    }
}