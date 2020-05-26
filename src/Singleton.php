<?php

namespace fernandoSa;

class Singleton
{
    protected static $instances = [];

    public static function instance($className)
    {
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new $className;
        }
            
        return self::$instances[$className];
    }
}
