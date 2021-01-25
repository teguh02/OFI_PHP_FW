<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfe7ce7bb8c2bdc67d53741ccf5d031c7
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'splitbrain\\phpcli\\' => 18,
        ),
        'o' => 
        array (
            'ofi\\ofi_php_framework\\' => 22,
        ),
        'W' => 
        array (
            'Whoops\\' => 7,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Plasticbrain\\FlashMessages\\' => 27,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'splitbrain\\phpcli\\' => 
        array (
            0 => __DIR__ . '/..' . '/splitbrain/php-cli/src',
        ),
        'ofi\\ofi_php_framework\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
            1 => __DIR__ . '/../..' . '/src',
        ),
        'Whoops\\' => 
        array (
            0 => __DIR__ . '/..' . '/filp/whoops/src/Whoops',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Plasticbrain\\FlashMessages\\' => 
        array (
            0 => __DIR__ . '/..' . '/plasticbrain/php-flash-messages/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfe7ce7bb8c2bdc67d53741ccf5d031c7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfe7ce7bb8c2bdc67d53741ccf5d031c7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitfe7ce7bb8c2bdc67d53741ccf5d031c7::$classMap;

        }, null, ClassLoader::class);
    }
}
