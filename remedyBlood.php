<?php
require_once (__DIR__.'/lAtrium.php');

//This Function will find Classes that the Heart can't see in your project. The heart feels bad :(.

spl_autoload_register( function($className) { 
        $blood = \Heart\lAtrium::lAtriumObj()->getDieBlood();
        $bloodWater = \Heart\lAtrium::lAtriumObj()->getArterialBlood();
        if ( array_key_exists( $className, $blood) && $bloodWater['autoLoad'] ) { 
            $path = $bloodWater['bodyPath'] . $blood[$className];
            require_once $path;
            return true; 
        }
    });
