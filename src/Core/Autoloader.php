<?php

namespace Core;

class Autoloader
{
    public static function registar(string $dir)
    {
        $autoload = function (string $classname) use ($dir)
        {

            $path = str_replace("\\", "/", $classname);
            $path = "$dir/$path.php";

            if (file_exists($path)) {
                require_once $path;
                return true;
            }
            return false;
        };

        spl_autoload_register($autoload);
    }

}