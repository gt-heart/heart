<?php
    namespace Heart;

    class rAtrium {

        private $filePath = __DIR__.'/.vBlood';

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

        public function __construct() {
            $this->arterialBlood = self::purifyBlood();
        }

        private function purifyBlood() {
            $lines = self::readVenous();
            foreach ($lines as $venous) {
                $blood = self::cleaner($venous);
                if (in_array($blood[0], $this->varAble)) $arterial[$blood[0]] = $blood[1];
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

        private function verifyBoolean(&$value) {
            if ($value === 'false') $value = false;
            if ($value === 'true') $value = true;
        }

        /**
         *
         */
        private function readVenous() {
            if (self::verifyBloodCirculaion()) {
                $autodetect = ini_get('auto_detect_line_endings');
                ini_set('auto_detect_line_endings', '1');

                $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                ini_set('auto_detect_line_endings', $autodetect);

                return $lines;
            }
        }

        /**
         * Verify if the file with config info exists
         * @return boolean
         */
        private function verifyBloodCirculaion() {
            return (file_exists($this->filePath) && is_readable($this->filePath));
        }
    }
