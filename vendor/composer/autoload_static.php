<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit001f2c1675b7740330b59734fb452530
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'System\\' => 7,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'System\\' => 
        array (
            0 => __DIR__ . '/../..' . '/system',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit001f2c1675b7740330b59734fb452530::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit001f2c1675b7740330b59734fb452530::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit001f2c1675b7740330b59734fb452530::$classMap;

        }, null, ClassLoader::class);
    }
}