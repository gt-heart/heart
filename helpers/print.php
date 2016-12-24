<?php
    class Prints {

        /**
         *
         */
        public static function it($obj, $property, $type ='', $default = '') {
            if (is_array($obj)) (object)$obj;
            self::switchType($type, $obj, $property);
            echo (!empty($obj->$property))? $obj->$property: $default;
        }

        /**
         *
         */
        private static function switchType($type, &$obj, $property) {
            if (!empty($type) && !empty($obj->$property)) {
                $exp = explode('/', $type);
                $func = 'a'.ucfirst($exp[0]);
                $obj->$property = self::$func($obj->$property, $exp[1]);
            }
        }

        /**
         *
         */
        public static function aDate($notStoned, $language) {
            $language = strtolower($language);
            $date = date_create_from_format('Y-m-d', $notStoned);
            switch ($language) {
                case 'br':
                    return date($date->format('d/m/Y'));
                    break;

                default:
                    return $date;
                    break;
            }
        }

    }
