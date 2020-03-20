<?php

/**
 * The admin-facing functionality of the plugin.
 *
 * @package    WP Auto Republish
 * @subpackage Includes
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

function wpar_purge_site_cache() {
    # wordpress default cache
    if (function_exists('wp_cache_flush')) {
    	wp_cache_flush();
    }
    	
    # Purge all W3 Total Cache
    if (function_exists('w3tc_pgcache_flush')) {
    	w3tc_pgcache_flush();
    }
    
    # Purge WP Super Cache
    if (function_exists('wp_cache_clear_cache')) {
    	wp_cache_clear_cache();
    }
    
    # Purge WP Rocket
    if (function_exists('rocket_clean_domain')) {
    	rocket_clean_domain();
    }
    
    # Purge Wp Fastest Cache
    if(isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')){
    	$GLOBALS['wp_fastest_cache']->deleteCache();
    }
    
    # Purge Cachify
    if (function_exists('cachify_flush_cache')) {
    	cachify_flush_cache();
    }
    
    # Purge Comet Cache
    if ( class_exists("comet_cache") ) {
    	comet_cache::clear();
    }
    
    # Purge Zen Cache
    if ( class_exists("zencache") ) {
    	zencache::clear();
    }
    
    # Purge LiteSpeed Cache 
    if (class_exists('LiteSpeed_Cache_Tags')) {
    	LiteSpeed_Cache_Tags::add_purge_tag('*');
    }
    
    # Purge SG Optimizer
    if (function_exists('sg_cachepress_purge_cache')) {
    	sg_cachepress_purge_cache();
    }
    
    # Purge Hyper Cache
    if (class_exists( 'HyperCache' )) {
    	do_action( 'autoptimize_action_cachepurged' );
    }
    
    # Purge Godaddy Managed WordPress Hosting (Varnish + APC)
    if (class_exists('WPaaS\Plugin')) {
    	wpar_godaddy_request_purge_varnish('BAN');
    }
    
    # purge cache enabler
    if ( has_action('ce_clear_cache') ) {
        do_action('ce_clear_cache');
    }
    
    # Purge WP Engine
    if (class_exists("WpeCommon")) {
    	if (method_exists('WpeCommon', 'purge_memcached')) { WpeCommon::purge_memcached(); }
    	if (method_exists('WpeCommon', 'clear_maxcdn_cache')) { WpeCommon::clear_maxcdn_cache(); }
    	if (method_exists('WpeCommon', 'purge_varnish_cache')) { WpeCommon::purge_varnish_cache(); }
    }
    
    # add breeze cache purge support
    if (class_exists("Breeze_PurgeCache")) {
    	Breeze_PurgeCache::breeze_cache_flush();
    }

    # swift
    if (class_exists("Swift_Performance_Cache")) {
    	Swift_Performance_Cache::clear_all_cache();
    }
}

# Purge Godaddy Managed WordPress Hosting (Varnish)
# https://github.com/wp-media/wp-rocket/blob/master/inc/3rd-party/hosting/godaddy.php
function wpar_godaddy_request_purge_varnish( $method, $url = null ) {
	$url = empty( $url ) ? home_url() : $url;
	$host = parse_url( $url, PHP_URL_HOST );
	$url = set_url_scheme( str_replace( $host, WPaas\Plugin::vip(), $url ), 'http' );
	wp_cache_flush();
	update_option( 'gd_system_last_cache_flush', time() ); # purge apc
	wp_remote_request( esc_url_raw( $url ), array('method' => $method, 'blocking' => false, 'headers' => array('Host' => $host)) );
}
