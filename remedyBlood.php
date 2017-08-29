<?php

//This Function will find Classes that the Heart can't see in your project. The heart feels bad :(.

spl_autoload_register( function($className) {
        $blood = \Heart\lAtrium::getDieBlood();
        $bloodWater = \Heart\lAtrium::getArterialBlood();
        if ( array_key_exists( $className, $blood) && $bloodWater['autoLoad'] ) {
            require_once $blood[$className];
            return true;
        }
    });
