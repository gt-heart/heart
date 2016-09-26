<?php
    require_once(__DIR__.'/../controller/base.php');

    class Session extends \Controller\Base {
        public $location = '../views/users';
        public $actions = ['login', 'store', 'delete', 'logout'];
        public $fillneeded = ['name', 'email', 'level'];

        /**
         * Verify if an valid login is runing
         *
         * @return void
         */
        public function is_up() {
            if (!isset($_SESSION['on'])) {
                self::logout();
            }
        }

        /**
         * Prints the current session message
         *
         * @return void
         */
        public static function msg() {
            (isset($_SESSION['msg'])) ? $msg='<div class="msg '.$_SESSION['msg'].'</div>' : $msg = $_SESSION['msg'] = null;
            unset($_SESSION['msg']);
            echo $msg;
        }

        /**
         * Verify location to active link on menu
         *
         * @param  string  $page
         *
         * @return boolean
         */
        public static function is_active($page) {
            if (array_pop(explode('/', $_SERVER['REQUEST_URI'])) == $page) {
                echo ' class="active"';
            }
        }
    }

    $session = new Session();
