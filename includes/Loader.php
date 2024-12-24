<?php

/**
 * Register all classes
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Core
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress;

/**
 * Main Class.
 */
final class Loader
{
    /**
     * Store all the classes inside an array
     * 
     * @return array Full list of classes
     */
    public static function get_services()
    {
        $services = array(
            Pages\Dashboard::class,
            Base\Enqueue::class,
            Base\Admin::class,
            Base\Actions::class,
            Base\Localization::class,
            Base\RatingNotice::class,
            Core\FetchPosts::class,
            Core\PostRepublish::class,
            Core\RewritePermalinks::class,
            Core\SiteCache::class,
            Core\RepublishInfo::class,
            Tools\Database::class,
            Tools\Updates::class,
        );
        return $services;
    }
    
    /**
     * Loop through the classes, initialize them, 
     * and call the register() method if it exists
     */
    public static function register_services()
    {
        foreach ( self::get_services() as $class ) {
            $service = self::instantiate( $class );
            if ( method_exists( $service, 'register' ) ) {
                $service->register();
            }
        }
    }
    
    /**
     * Initialize the class
     * 
     * @param  class $class    class from the services array
     * @return class instance  new instance of the class
     */
    private static function instantiate( $class )
    {
        $service = new $class();
        return $service;
    }
}