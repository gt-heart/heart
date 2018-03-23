<?php
    namespace Heart;
    require_once(__DIR__.'/rAtrium.php');

    use \Heart\rAtrium as rAtrium;
    use \Heart\lAtrium as lAtrium;

    final class lAtrium {

        private $rAtrium;

        public function __construct() {
            $this->rAtrium = new rAtrium();
        }

        public function getArterialBlood() {
            return $this->rAtrium->arterialBlood;
        }
        
        /*
         * Ops, Heart can't die. It'll save classes with this function.
        */

        public function cancerFill( $class, $location ) {
            if ( $this->rAtrium->arterialBlood['autoLoad'] )
                $this->rAtrium->diagnoseCancer($class, $location);
        }
        
        /*
         * This function returns all classes and locations that Heart remember about your project.
        */

        public function getDieBlood() {
            return $this->rAtrium->dieBlood;
        }

        static public function lAtriumObj() {
            return new lAtrium();
        }
    }
