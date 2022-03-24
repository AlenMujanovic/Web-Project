<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitac69a61676e731c7ad2408ea255a1c83
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitac69a61676e731c7ad2408ea255a1c83::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitac69a61676e731c7ad2408ea255a1c83::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitac69a61676e731c7ad2408ea255a1c83::$classMap;

        }, null, ClassLoader::class);
    }
}
