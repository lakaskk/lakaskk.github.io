<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit5238bc3f91fa2eb34a189e3a4366e639
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit5238bc3f91fa2eb34a189e3a4366e639', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit5238bc3f91fa2eb34a189e3a4366e639', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit5238bc3f91fa2eb34a189e3a4366e639::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
