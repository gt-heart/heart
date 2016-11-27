<?php
    class Prints {

        /**
         * 
         */
        public static function it($obj, $property, $default = '') {
            echo (!empty($obj->$property))? $obj->$property: $default;
        }

    }
