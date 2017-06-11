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
            'uploadsOn'
        ];

        public $arterialBlood = [];
        
        /**
         * This lists all the Heart remember about your project, so relax!
        */

        public $dieBlood = [];

        public function __construct() {
            $this->arterialBlood = self::purifyBlood(false);
            $this->dieBlood = self::purifyBlood(true);
        }

        /**
         * Hard? NO! If dieChange is true the function returns $fileCancer or $filePath. Obvious, file was handled. 
        */

        private function purifyBlood( $dieChange ) {
            $lines = self::readVenous( $dieChange );
            if ( $dieChange ) {
                if ( empty($lines) ) {
                    $arterial['class'] = 'location';
                } else {
                    foreach ($lines as $dieBlood) {
                        $blood = self::cleaner($dieBlood);
                        $arterial[$blood[0]] = $blood[1];
                    }
                }
            } else {
                foreach ($lines as $venous) {
                    $blood = self::cleaner($venous);
                    if (in_array($blood[0], $this->varAble)) $arterial[$blood[0]] = $blood[1];
                }
            }
            return $arterial;
        }

        private function cleaner($venous) {
            $venous = str_replace(' ', '', $venous);
            $venous = explode(':', $venous);
            $value = explode('\'', $venous[1], 3);
            self::verifyBoolean($value[1]);
            $venous[1] = $value[1];
            return $venous;
        }
        
        private function contaminate($bloodFor) {
            $bloodFor = str_replace(' ', '', $bloodFor);
            $bloodFor = explode(':', $bloodFor);
            $bloodVal = explode('\'', $bloodFor[1], 3);
            $bloodFor[1] = $bloodVal[1];
            return $bloodFor;
        }

        private function verifyBoolean(&$value) {
            if ($value === 'false') $value = false;
            if ($value === 'true') $value = true;
        }

        /** $rAtrium = new rAtrium();
            $rAtrium = new rAtrium();

         *
         */
        private function readVenous( $dieChange ) {
            if (self::verifyBloodCirculaion() || $dieChange ) {
                $autodetect = ini_get('auto_detect_line_endings');
                ini_set('auto_detect_line_endings', '1');
                if ( $dieChange && self::verifyBloodDie() )
                    $lines = file($this->fileCancer, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                else
                    $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                
                ini_set('auto_detect_line_endings', $autodetect);

                return $lines;
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
                    file_put_contents($rAtrium->fileCancer, "{$class} : '{$location}'\n", FILE_APPEND);
                } else if ( $rAtrium->dieBlood[$class] != $location ) {
                    $rAtrium->dieBlood[$class] = $location;
                    $data = self::killVirus($rAtrium->dieBlood);
                    file_put_contents($rAtrium->fileCancer, $data);
                }
            } else {
                file_put_contents($rAtrium->fileCancer, "{$class} : '{$location}'\n", FILE_APPEND);
            }
        }
        
        private static function killVirus( $circulation ) {
            $str = null;
            foreach ( $circulation as $key => $value ) {
                $str .= "{$key} : '{$value}'\n";
            }
            return $str;
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
    }