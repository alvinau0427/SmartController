<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit49744027e7284508c7674d045f43d037
{
    public static $files = array (
        '253c157292f75eb38082b5acb06f3f01' => __DIR__ . '/..' . '/nikic/fast-route/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Slim\\Views\\' => 11,
            'Slim\\' => 5,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
        'I' => 
        array (
            'Interop\\Container\\' => 18,
        ),
        'F' => 
        array (
            'FastRoute\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Slim\\Views\\' => 
        array (
            0 => __DIR__ . '/..' . '/slim/twig-view/src',
        ),
        'Slim\\' => 
        array (
            0 => __DIR__ . '/..' . '/slim/slim/Slim',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Interop\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/container-interop/container-interop/src/Interop/Container',
        ),
        'FastRoute\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/fast-route/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
        'P' => 
        array (
            'Pimple' => 
            array (
                0 => __DIR__ . '/..' . '/pimple/pimple/src',
            ),
        ),
    );

    public static $fallbackDirsPsr0 = array (
        0 => __DIR__ . '/../..' . '/src',
    );

    public static $classMap = array (
        'Pusher' => __DIR__ . '/..' . '/pusher/pusher-php-server/lib/Pusher.php',
        'PusherException' => __DIR__ . '/..' . '/pusher/pusher-php-server/lib/Pusher.php',
        'PusherInstance' => __DIR__ . '/..' . '/pusher/pusher-php-server/lib/Pusher.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit49744027e7284508c7674d045f43d037::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit49744027e7284508c7674d045f43d037::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit49744027e7284508c7674d045f43d037::$prefixesPsr0;
            $loader->fallbackDirsPsr0 = ComposerStaticInit49744027e7284508c7674d045f43d037::$fallbackDirsPsr0;
            $loader->classMap = ComposerStaticInit49744027e7284508c7674d045f43d037::$classMap;

        }, null, ClassLoader::class);
    }
}
