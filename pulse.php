<?php

    require_once (__DIR__.'/sos/drugstore.php');

    if (!empty($contexts))
        foreach ($contexts as $context) (!Pulse::plus($context))?: require_once(Pulse::plus($context));

    (!Pulse::controller())?: require_once(Pulse::controller());

    $helpers = scandir(__DIR__.'/helpers');


    require_once (__DIR__.'/helpers/session.php');
    require_once (__DIR__.'/helpers/print.php');


    class Pulse {
        private $uri;

        private function __construct() {
            $this->uri = explode('/', $_SERVER['PHP_SELF']);
        }

        public static function controller() {
            $self = new Pulse;
            $controller = self::getControllerPath($self->clearFile());
            return (!is_file($controller))? NULL : $controller;
        }

        protected static function getControllerPath($context) {
            return __DIR__ . '/../controllers/' . $context . '_controller.php';
        }

        private function clearFile() {
            $file = $this->uri[array_search('views', $this->uri) + 1];

            if ($file == $this->uri[1]) $file = array_pop($this->uri);

            $ctrl = str_replace('s.php', '', $file);
            $ctrl = str_replace('.php', '', $ctrl);
            $ctrl = str_replace('edit-', '', $ctrl);
            $ctrl = str_replace('new-', '', $ctrl);

            return $ctrl;
        }

        public static function plus($context) {
            $controller = self::getControllerPath($context);
            return (!is_file($controller))? NULL : $controller;
        }
    }
