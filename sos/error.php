<?php
    namespace SOS;

    class ErrorController {
        public $type;
        public $message;
        public $file;
        public $line;

        public function __construct($attributes = array()) {
            foreach ($attributes as $key => $value) {
                if (!empty($value)) {
                    $this->$key = $value;
                }
            }
            $this->type = self::getErrorType($this->type);
            $this->message = self::getErrorMessage($this->message);
        }

        public function htmlfyError() {

            return "<div class=\"error\">
                <div class=\"component\">
                    <h2>O quê?</h2>
                    <p>{$this->type}</p>
                </div>
                <div class=\"component\">
                    <h2>Por quê?</h2>
                    <p>{$this->message}</p>
                </div>
                <div class=\"component\">
                    <h2>Onde?</h2>
                    <p>{$this->file}</p>
                    <p>Na linha <b>{$this->line}</b>.</p>
                </div>
            </div>";
        }

        private function getErrorMessage($message) {
            if (strpos($message, 'Stack trace:') !== false) {
                $message = explode('Stack trace:', $message);
                $traces = explode('#', $message[1]);
                $message = "<p>{$message[0]}</p><h3>Stack Trace:</h3>";
                $count = -1;
                foreach ($traces as $trace) {
                    $replace = "<b>{$count} => </b>";
                    $trace = str_replace($count.' ', $replace, $trace);
                    $message .= "<p>{$trace}</p>";
                    $count++;
                }
            }

            return $message;
        }

        private function getErrorType($type)
        {
            switch($type)
            {
                case E_ERROR: // 1 //
                    return 'E_ERROR';
                case E_WARNING: // 2 //
                    return 'E_WARNING';
                case E_PARSE: // 4 //
                    return 'E_PARSE';
                case E_NOTICE: // 8 //
                    return 'E_NOTICE';
                case E_CORE_ERROR: // 16 //
                    return 'E_CORE_ERROR';
                case E_CORE_WARNING: // 32 //
                    return 'E_CORE_WARNING';
                case E_COMPILE_ERROR: // 64 //
                    return 'E_COMPILE_ERROR';
                case E_COMPILE_WARNING: // 128 //
                    return 'E_COMPILE_WARNING';
                case E_USER_ERROR: // 256 //
                    return 'E_USER_ERROR';
                case E_USER_WARNING: // 512 //
                    return 'E_USER_WARNING';
                case E_USER_NOTICE: // 1024 //
                    return 'E_USER_NOTICE';
                case E_STRICT: // 2048 //
                    return 'E_STRICT';
                case E_RECOVERABLE_ERROR: // 4096 //
                    return 'E_RECOVERABLE_ERROR';
                case E_DEPRECATED: // 8192 //
                    return 'E_DEPRECATED';
                case E_USER_DEPRECATED: // 16384 //
                    return 'E_USER_DEPRECATED';
            }
            return $type;
        }
    }
