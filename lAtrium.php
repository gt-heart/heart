<?php
    namespace Heart;

    require_once(__DIR__.'/rAtrium.php');

    use \Heart\rAtrium as rAtrium;

    final class lAtrium {

        public static function getArterialBlood() {
            $rAtrium = new rAtrium();
            return $rAtrium->arterialBlood;
        }
    }
