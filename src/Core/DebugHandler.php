<?php

namespace MockEcoleDirecteApi\Core;

class DebugHandler
{
    public static function dump($input)
    {
        //ob_start();
        echo "<pre>";
        var_dump($input);
        echo "</pre>";
        //return ob_get_clean();
    }
}