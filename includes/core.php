<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Auto Republish
 * @subpackage Includes
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

add_action( 'init', 'wpar_plugin_init' );
add_filter( 'the_content', 'wpar_hook_into_the_content', apply_filters( 'wpar_show_pub_date_priority', '10' ) );

function wpar_plugin_init() {
	if ( wpar_update_time() ) {
		update_option( 'wpar_last_update', time() );
		wpar_republish_old_post();
	}
}

function wpar_custom_post_types_support() {
    $output = array();
    $post_types = apply_filters( 'wpar_supported_post_types', array( 'post' ), $output );
    foreach( $post_types as $post_type ) {
        $output[] = "'$post_type'";
    }
    return $output;
}

function wpar_republish_old_post () {
    global $wpdb;
    
    $wpar_settings = get_option('wpar_plugin_settings');

	$wpar_omit_by_type = $wpar_settings['wpar_exclude_by_type'];
	$wpar_age_limit = $wpar_settings['wpar_republish_post_age'];
	$wpar_method = $wpar_settings['wpar_republish_method'];

	$wpar_omit_id = '';
	$wpar_order_by = 'post_date ASC';
	if ( isset( $wpar_method ) && $wpar_method == 'random' ) {
		$wpar_order_by = 'RAND()';
	}
	
	$sql = "SELECT ID, post_date
            FROM $wpdb->posts
            WHERE post_type IN (" . implode( ',', wpar_custom_post_types_support() ) . ")
                AND post_status = 'publish'
				AND post_date < '" . current_time( 'mysql' ) . "' - INTERVAL " . $wpar_age_limit * 24 . " HOUR 
				";
    if ( isset( $wpar_omit_by_type ) && $wpar_omit_by_type != 'none' ) {

		$wpar_omit = $wpar_settings['wpar_exclude_by'];
		$wpar_omit_id = !empty( $wpar_settings['wpar_exclude_category_tag'] ) ? implode( ',', $wpar_settings['wpar_exclude_category_tag'] ) : '1';
		$wpar_omit_override = $wpar_settings['wpar_override_category_tag'];

		$wpar_omit_type = '';
		if ( isset( $wpar_omit_by_type ) && $wpar_omit_by_type == 'exclude' ) {
			$wpar_omit_type = 'NOT';
		}

    	$sql = $sql."AND $wpar_omit_type(ID IN (SELECT tr.object_id 
                                    FROM $wpdb->terms t 
                                          inner join $wpdb->term_taxonomy tax on t.term_id=tax.term_id and tax.taxonomy='$wpar_omit'
                                          inner join $wpdb->term_relationships tr on tr.term_taxonomy_id=tax.term_taxonomy_id 
									WHERE t.term_id IN (".$wpar_omit_id.")))";
		if ( !empty( $wpar_omit_override ) ) {
			if ( isset( $wpar_omit_by_type ) && $wpar_omit_by_type == 'exclude' ) {
			    $sql = $sql."
			      UNION SELECT ID, post_date FROM $wpdb->posts WHERE ID IN (".$wpar_omit_override.")";
			} else {
				$sql = $sql."
				  AND NOT(ID IN (SELECT ID FROM $wpdb->posts WHERE ID IN (".$wpar_omit_override.")))";
			}
		}
    }            
	$sql = $sql."
	      ORDER BY $wpar_order_by 
		LIMIT 1 ";						

	error_log ( $sql );

	$oldest_post = $wpdb->get_var( $sql, 0, 0 );
	if ( isset( $oldest_post ) ) {
		wpar_update_old_post( $oldest_post );
	}
}

function wpar_update_old_post( $oldest_post ) {
    global $wpdb;
    
	$wpar_settings = get_option('wpar_plugin_settings');
	
    $post = get_post( $oldest_post );
	$wpar_original_pub_date = get_post_meta( $oldest_post, '_wpar_original_pub_date', true ); 

	if ( !isset( $wpar_original_pub_date ) || empty( $wpar_original_pub_date ) ) {
	    $sql = "SELECT post_date from ".$wpdb->posts." WHERE ID = '$oldest_post'";
		$wpar_original_pub_date = $wpdb->get_var( $sql );
		update_post_meta( $oldest_post, '_wpar_original_pub_date', $wpar_original_pub_date );
        $wpar_original_pub_date = get_post_meta( $oldest_post, '_wpar_original_pub_date', true ); 
    }

	if ( isset( $wpar_settings['wpar_republish_post_position'] ) && $wpar_settings['wpar_republish_post_position'] == 1 ) {
		$new_time = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
		$gmt_time = get_gmt_from_date( $new_time );
	} else {
		$lastposts = get_posts( array(
            'numberposts'    => 1,
            'offset'         => 1
        ) );
		foreach ( $lastposts as $lastpost ) {
			$post_date = strtotime( $lastpost->post_date );
			$new_time = date( 'Y-m-d H:i:s', mktime( date("H",$post_date), date("i",$post_date), date("s",$post_date)+1, date("m",$post_date), date("d",$post_date), date("Y",$post_date) ) );
			$gmt_time = get_gmt_from_date( $new_time );
		}
	}

	$sql = "UPDATE $wpdb->posts SET post_date = '$new_time',post_date_gmt = '$gmt_time',post_modified = '$new_time',post_modified_gmt = '$gmt_time' WHERE ID = '$oldest_post'";		
	$wpdb->query( $sql );
    
	require_once plugin_dir_path( __FILE__ ) . 'cache.php';
	
	$permalink = get_permalink( $oldest_post );
	
	do_action( 'wpar_old_post_republished', $oldest_post ); 
}

function wpar_hook_into_the_content( $content ) {
    global $post;
    
	$wpar_settings = get_option('wpar_plugin_settings');
	// get wordpress date time format
    $get_df = get_option( 'date_format' );
	$get_tf = get_option( 'time_format' );

	$wpar_show_pubdate = $wpar_settings['wpar_republish_position'];
	$wpar_text = $wpar_settings['wpar_republish_position_text'];
    
	$wpar_original_pub_date = get_post_meta( $post->ID, '_wpar_original_pub_date', true );
	
	$local_date = date_i18n( apply_filters( 'wpar_published_date_format', $get_df . ' @ ' . $get_tf ), strtotime( $wpar_original_pub_date ) );
    
	$dateline = '';
	if ( isset( $wpar_original_pub_date ) && $wpar_original_pub_date != '' ) {
		$dateline .= '<p id="wpar" class="wpar-pubdate" style="font-size: 12px;">';
		$dateline .= $wpar_text . $local_date;
		$dateline .= '</p>';
	}

	if ( isset( $wpar_show_pubdate ) && $wpar_show_pubdate == 'before_content' ) {
		$content = $dateline . $content;
	} elseif ( isset( $wpar_show_pubdate ) && $wpar_show_pubdate == 'after_content' ) {
		$content = $content . $dateline;
	}
	return $content;
}

function wpar_update_time() {
    $last = get_option( 'wpar_last_update' );
    $wpar_settings = get_option('wpar_plugin_settings');
    $interval = $wpar_settings['wpar_minimun_republish_interval'];
	$slop = $wpar_settings['wpar_random_republish_interval'];
	$time = time();

	if ( false === $last ) {
		$ret = 1;
	} elseif ( is_numeric( $last ) ) { 
		if ( ( $time - $last ) >= ( $interval + rand( 0, $slop ) ) ) {
			$ret = 1;
		} else {
			$ret = 0;
		}
	}
	return $ret;
}