<?php
    namespace Heart;
    require_once(__DIR__.'/rAtrium.php');

    use \Heart\rAtrium as rAtrium;

    final class lAtrium {

        public static function getArterialBlood() {
            $rAtrium = new rAtrium();
            return $rAtrium->arterialBlood;
        }
        
        /*
         * Ops, Heart can't die. It'll save classes with this function.
        */

        public static function cancerFill( $class, $location ) {
            $rAtrium = new rAtrium();
            if ( $rAtrium->arterialBlood['autoLoad'] )
                $rAtrium->diagnoseCancer($class, $location);
        }
        
        /*
         * This function returns all classes and locations that Heart remember about your project.
        */

        public static function getDieBlood() {
            $rAtrium = new rAtrium();
            return $rAtrium->dieBlood;
        }
    }