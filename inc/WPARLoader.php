<?php

/**
 * Register all classes
 *
 * @since      1.1.0
 * @package    WP Auto Republish
 * @subpackage Wpar\Core
 * @author     Sayan Datta <hello@sayandatta.in>
 */
namespace Wpar;

/**
 * WPAR Main Class.
 */
final class WPARLoader
{
    /**
     * Store all the classes inside an array
     * 
     * @return array Full list of classes
     */
    public static function get_services()
    {
        $premium_services = [];
        $services = [
            Pages\Dashboard::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Base\Localization::class,
            Base\AdminNotice::class,
            Base\RatingNotice::class,
            Base\DonateNotice::class,
            Core\PostRepublish::class,
            Core\SiteCache::class,
            Core\RepublishInfo::class
        ];
        return array_merge( $services, $premium_services );
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