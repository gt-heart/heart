<?php


    (!Pulse::controller())?: require_once(Pulse::controller());


    $helpers = scandir(__DIR__.'/helpers');


    require_once (__DIR__.'/helpers/session.php');




    class Pulse {
        private $uri;

        private function __construct() {
            $this->uri = explode('/', $_SERVER['PHP_SELF']);
        }

        public static function controller() {
            $self = new Pulse;
            $controller = __DIR__ . '/../controllers/' . $self->clearFile() . '_controller.php';
            return (!is_file($controller))? false : $controller;
        }

        private function clearFile() {
            $file = $this->uri[array_search('views', $this->uri) + 1];

            if ($file == $this->uri[1]) $file = array_pop($this->uri);

            $ctrl = str_replace('s.php', '', $file);
            $ctrl = str_replace('edit-', '', $ctrl);
            $ctrl = str_replace('new-', '', $ctrl);

            return $ctrl;
        }
    }
