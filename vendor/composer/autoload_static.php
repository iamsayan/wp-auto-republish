<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0ca75cc57cf56088d3266a28d92f6120
{
    public static $files = array (
        '6116bb3c2e739c8baa180dc51ba4c9fe' => __DIR__ . '/..' . '/woocommerce/action-scheduler/action-scheduler.php',
        '1a19a63a1a4b80ac8fcb008711e9450e' => __DIR__ . '/../..' . '/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'RevivePress\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'RevivePress\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'RevivePress\\Api\\Callbacks\\AdminCallbacks' => __DIR__ . '/../..' . '/includes/Api/Callbacks/AdminCallbacks.php',
        'RevivePress\\Api\\Callbacks\\ManagerCallbacks' => __DIR__ . '/../..' . '/includes/Api/Callbacks/ManagerCallbacks.php',
        'RevivePress\\Api\\SettingsApi' => __DIR__ . '/../..' . '/includes/Api/SettingsApi.php',
        'RevivePress\\Base\\Actions' => __DIR__ . '/../..' . '/includes/Base/Actions.php',
        'RevivePress\\Base\\Activate' => __DIR__ . '/../..' . '/includes/Base/Activate.php',
        'RevivePress\\Base\\Admin' => __DIR__ . '/../..' . '/includes/Base/Admin.php',
        'RevivePress\\Base\\BaseController' => __DIR__ . '/../..' . '/includes/Base/BaseController.php',
        'RevivePress\\Base\\Deactivate' => __DIR__ . '/../..' . '/includes/Base/Deactivate.php',
        'RevivePress\\Base\\Enqueue' => __DIR__ . '/../..' . '/includes/Base/Enqueue.php',
        'RevivePress\\Base\\Localization' => __DIR__ . '/../..' . '/includes/Base/Localization.php',
        'RevivePress\\Base\\RatingNotice' => __DIR__ . '/../..' . '/includes/Base/RatingNotice.php',
        'RevivePress\\Base\\Uninstall' => __DIR__ . '/../..' . '/includes/Base/Uninstall.php',
        'RevivePress\\Core\\FetchPosts' => __DIR__ . '/../..' . '/includes/Core/FetchPosts.php',
        'RevivePress\\Core\\PostRepublish' => __DIR__ . '/../..' . '/includes/Core/PostRepublish.php',
        'RevivePress\\Core\\RepublishInfo' => __DIR__ . '/../..' . '/includes/Core/RepublishInfo.php',
        'RevivePress\\Core\\RewritePermainks' => __DIR__ . '/../..' . '/includes/Core/RewritePermainks.php',
        'RevivePress\\Core\\SiteCache' => __DIR__ . '/../..' . '/includes/Core/SiteCache.php',
        'RevivePress\\Helpers\\Ajax' => __DIR__ . '/../..' . '/includes/Helpers/Ajax.php',
        'RevivePress\\Helpers\\Fields' => __DIR__ . '/../..' . '/includes/Helpers/Fields.php',
        'RevivePress\\Helpers\\HelperFunctions' => __DIR__ . '/../..' . '/includes/Helpers/HelperFunctions.php',
        'RevivePress\\Helpers\\Hooker' => __DIR__ . '/../..' . '/includes/Helpers/Hooker.php',
        'RevivePress\\Helpers\\Scheduler' => __DIR__ . '/../..' . '/includes/Helpers/Scheduler.php',
        'RevivePress\\Helpers\\SettingsData' => __DIR__ . '/../..' . '/includes/Helpers/SettingsData.php',
        'RevivePress\\Helpers\\Sitepress' => __DIR__ . '/../..' . '/includes/Helpers/Sitepress.php',
        'RevivePress\\Loader' => __DIR__ . '/../..' . '/includes/Loader.php',
        'RevivePress\\Pages\\Dashboard' => __DIR__ . '/../..' . '/includes/Pages/Dashboard.php',
        'RevivePress\\Tools\\Database' => __DIR__ . '/../..' . '/includes/Tools/Database.php',
        'RevivePress\\Tools\\Updates' => __DIR__ . '/../..' . '/includes/Tools/Updates.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0ca75cc57cf56088d3266a28d92f6120::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0ca75cc57cf56088d3266a28d92f6120::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0ca75cc57cf56088d3266a28d92f6120::$classMap;

        }, null, ClassLoader::class);
    }
}
