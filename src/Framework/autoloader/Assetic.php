<?php
namespace Framework\Autoloader;

class Assetic
{
    /**
     * Registers Assetic_Autoloader as an SPL autoloader.
     */
    static public function register()
    {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    /**
     * Handles autoloading of classes.
     *
     * @param  string  $class  A class name.
     *
     * @return boolean Returns true if the class has been loaded
     */
    static public function autoload($class)
    {
        if (0 !== strpos($class, 'Assetic')) {
            return;
        }
        $file = dirname(__FILE__).'/../vendor/'.str_replace('\\', '/', $class).'.php';
        if (file_exists($file)) {
            require $file;
        }

    }
}
