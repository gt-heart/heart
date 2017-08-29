<?php

    namespace Heart;

    class rAtrium {

        private $filePath = __DIR__.'/.vBlood';

        /**
         * This file only show and save all classes that Heart can need. This's important!
        */
        private $fileCancer = __DIR__.'/.cBlood';

        private $varAble = [
            'host',
            'database',
            'user',
            'password',
            'useDbViews',
            'errorPage',
            'rootPage',
            'homePage',
            'escapePage',
            'isDebug',
            'uploads',
            'uploadPath',
            'uploadsOn',
            'autoLoad'
        ];

        public $arterialBlood = [];

        /**
         * This lists all the Heart remember about your project, so relax!
        */

        public $dieBlood = [];

        public function __construct() {
            if ( isset($this->arterialBlood) )
                $this->arterialBlood = self::purifyBlood(false);
            if ( $this->arterialBlood['autoLoad'] )
                $this->dieBlood = self::purifyBlood(true);
        }

        /**
         * Hard? NO! If dieChange is true the function returns $fileCancer else $filePath. Obvious, file was handled.
        */

        private function purifyBlood( $dieChange ) {
                $circulation = null;
                $circulation = $this->readVenous($dieChange);
                if ( isset($circulation) ) {
                    foreach ($circulation as $key => $value) {
                        if ( $value === "true" || $value === "false" )
                            $circulation[$key] = self::verifyBoolean($value);
                    }

                    return $circulation;
                }
        }

        private static function verifyBoolean($value) {
            if ($value === 'false') return false;
            if ($value === 'true') return true;
            return $value;
        }

        /** $rAtrium = new rAtrium();
            $rAtrium = new rAtrium();
         *
         */
        private function readVenous( $dieChange ) {
            if ( self::verifyBloodCirculaion() || $dieChange ) {
                if ( $dieChange && self::verifyBloodDie() )
                    return parse_ini_file($this->fileCancer);
                else
                    return parse_ini_file($this->filePath);
            }
        }
        /**
         *  Hum... If we arrive here it's because the Heart didn't meet some Class. This function save his location. Heart can't feel bad.
        */

        public function diagnoseCancer( $class , $location ) {
            $rAtrium = new rAtrium();

            if ( $rAtrium->dieBlood != null ) {
                if ( !array_key_exists( $class, $rAtrium->dieBlood ) ) {
                    $rAtrium->dieBlood[$class] = $location;
                    file_put_contents($rAtrium->fileCancer, "{$class} = \"{$location}\"\n", FILE_APPEND);
                } else if ( $rAtrium->dieBlood[$class] != $location ) {
                    $rAtrium->dieBlood[$class] = $location;
                    file_put_contents($rAtrium->fileCancer, self::killVirus($rAtrium->dieBlood) );
                }
            } else {
                file_put_contents($rAtrium->fileCancer, "{$class} = \"{$location}\"\n", FILE_APPEND);
            }
        }

        private static function killVirus( $circulation ) {
            return self::registerDiagnose($circulation, false);
        }
        /**
         * Verify if the file with config info exists
         * @return boolean
         */
        private function verifyBloodCirculaion() {
            return (file_exists($this->filePath) && is_readable($this->filePath));
        }

        private function verifyBloodDie() {
            if ( file_exists($this->fileCancer) && is_readable($this->fileCancer) )
                return true;
            else {
                return file_put_contents($this->fileCancer, '');
            }
        }

        private static function registerDiagnose($assoc_arr, $has_sections = false) {
            $content = "";
            if ($has_sections) {
                foreach ($assoc_arr as $key=>$elem) {
                    $content .= "[".$key."]\n";
                    foreach ($elem as $key2=>$elem2) {
                        if(is_array($elem2))
                        {
                            for($i=0;$i<count($elem2);$i++)
                            {
                                $content .= $key2."[] = \"".$elem2[$i]."\"\n";
                            }
                        }
                        elseif ( $elem2=="" )
                            $content .= $key2 . " = \"\"\n";
                        else
                            $content .= $key2." = \"" . $elem2 . "\"\n";
                    }
                }
            } else {
                foreach ($assoc_arr as $key=>$elem) {
                    if(is_array($elem))
                    {
                        for($i=0;$i<count($elem);$i++)
                        {
                            $content .= $key."[] = \"".$elem[$i]."\"\n";
                        }
                    }
                    elseif ( $elem=="")
                        $content .= $key. " = \"\n\"" ;
                    else
                        $content .= $key." = \"".$elem."\"\n";
                }
            }

            return $content;

        }

    }
