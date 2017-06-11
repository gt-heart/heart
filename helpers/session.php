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
        public static function is_up() {
            if (!isset($_SESSION['on'])) {
                $session = new Session();
                $session->logout('is_up');
            }
            return new Session();
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
         * Prints the current error message
         * @return void
         */
        public static function errorPrint() {
            (isset($_SESSION['error']))? $error = $_SESSION['error']:  $error = $_SESSION['error'] = null;
            unset($_SESSION['error']);
            echo $error;
        }

        /**
         * Verify location to active link on menu
         *
         * @param  string  $page
         *
         * @return boolean
         */
        public static function is_active($pages) {
            $uri = explode('/', $_SERVER['REQUEST_URI']);
            $active = str_replace('.php', '', array_pop($uri));
            $active = explode('?', $active);
            $active = $active[0];

            if (is_array($pages)) {
                foreach ($pages as $page) {
                    if ($active == $page) {
                        echo ' class="active"';
                        break;
                    }
                }
            } else {
                if ($active == $pages) echo ' class="active"';
            }
        }
    }

    $session = new Session();
