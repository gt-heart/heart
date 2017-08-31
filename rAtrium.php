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
            //if ( !($this->arterialBlood) )
            $this->arterialBlood = self::purifyBlood(false);
            $this->arterialBlood["bodyPath"] = realpath( __DIR__ . '/../' );
            if ( $this->arterialBlood['autoLoad'] ) {
                $this->dieBlood = self::purifyBlood(true);
            }
        }

        /**
         * Hard? NO! If dieChange is true the function returns $fileCancer else $filePath. Obvious, file was handled.
        */

        private function purifyBlood( $dieChange ) {
                $circulation = null;
                $circulation = $this->readVenous($dieChange);
                if ( $dieChange && ( null == $circulation || $this->arterialBlood['forceLoad'] ) ) {
                    $this->forceMedical();
                    $circulation = $this->readVenous($dieChange);
                }
                if ( isset($circulation) ) {
                    foreach ($circulation as $key => $value)
                        $circulation[$key] = self::verifyBoolean($value);
                    return $circulation;
                }
        }

        private function forceMedical() {
            $files = glob($this->arterialBlood["bodyPath"] . "/[^heart][^views]*/*.php");
            unlink($this->fileCancer);
            foreach ($files as $file) {
                $this->diagnoseCancer( ucfirst(basename($file, ".php")), $file);
            }
        }

        private static function verifyBoolean($value) {
            if ( strcasecmp($value, 'false') == 0 )  return false;
            if ( strcasecmp($value, 'true') == 0 ) return true;
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
            $location = str_replace($this->arterialBlood["bodyPath"], "", $location);
            if ( $this->dieBlood != null ) {
                if ( !array_key_exists( $class, $this->dieBlood ) ) {
                    $this->dieBlood[$class] = $location;
                    file_put_contents($this->fileCancer, "{$class} = \"{$location}\"\n", FILE_APPEND);
                } else if ( $this->dieBlood[$class] != $location ) {
                    $this->dieBlood[$class] = $location;
                    file_put_contents($this->fileCancer, self::killVirus($this->dieBlood) );
                }
            } else {
                file_put_contents($this->fileCancer, "{$class} = \"{$location}\"\n", FILE_APPEND);
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
