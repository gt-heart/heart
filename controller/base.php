<?php
/**
 * Important: To use this class and its functions and properties correctly,
 * you have to name your Controller's class
 * with the same name of their correspondent Model concatenated to '_controller'.
 * Sample:
 * - Model: User
 * - Correspondent controller: User_controller
 */

    namespace Controller;

    require_once ( __DIR__ . '/../remedyBlood.php');

    use \Heart\lAtrium as lAtrium;

    abstract class Base {

        /**
         * Stores the address to redirect user after action
         *
         * It'll be overloaded by each controller
         *
         * @var string
         */
        public $location = null;

        /**
         * Stores valid actions to use on system
         *
         * It can be overloaded by each controller
         *
         */
        public $actions = ['delete', 'store', 'logout', 'storeExt'];

        /**
         *
         */
        public $offlineActions = [];

        /**
         * Stores required fills to validate actions
         *
         * It can be overloaded by each controller
         *
         */
        public $fillneeded = [];

        /**
         * Stores relationals entity name
         *
         * It can be overloaded by each controller
         *
         */
        public $relationals = [];

        /**
         * Stores the Model class name
         *
         * @var string
         */
        public $model;

        /**
         * Generic construct for controllers
         */
        function __construct() {
            $this->model = self::get_model();
            self::action();
            
            $reflector = new \ReflectionClass(get_class($this));
            lAtrium::cancerFill( get_class($this), $reflector->getFileName() );
        }

        /**
         * Gets model name, based on controller name
         *
         * It'll be used to do new class instances from Model
         *
         * @return string
         */
        public static function get_model() {
            return str_replace('_controller', '', get_called_class());
        }

        /**
         *
         */
        public function loadAll($order = null, $where = null) {
            return ($order)? $this->model::all($order, $where) : $this->model::all();
        }

        /**
         *
         */
        public function one($id = null) {
            $id = isset($_GET['id']) ? $_GET['id'] : $id;
            return ($id) ? $this->model::one($id) : null;
        }

        /**
         *
         */
        public function selectIt($clauses = [], $order = null, $limit = null) {
            return (!empty($clauses))? $this->model::selectIt($clauses, $order, $limit): null;
        }

        /**
         * Verifies if passed informations are sufficient to validate the query
         *
         * It'll be overloaded by each controller
         *
         * @return boolean
         */
        public function is_valid() {
            foreach ($this->fillneeded as $key => $value) {

                $_SESSION['msg'] = 'O campo "'.$value.'" é obrigatório.';

                $key = (is_numeric($key))? $value: $key;

                if (empty($_REQUEST[$key])) return false;
            }

            return true;
        }

        /**
         * Controls what happens on system when an database line is inserted or updated
         *
         * @return void
         */
        public function store() {
            if (self::is_valid()) {
                $obj = new $this->model($_REQUEST);
                try {
                    $result = (is_null($obj->id)) ? $obj->insert() : $obj->update();
                    $_SESSION['msg'] = 'success">Operação realizada com sucesso!';
                } catch(\PDOException $e) {
                    $_SESSION['msg'] = 'fail">Houve um erro. Por favor, confira as informações inseridas.';
                }
            } else {
                $_SESSION['msg'] = 'fail">Por favor, preencha os campos obrigatórios. ' . $_SESSION['msg'];
            }
            header('Location:'.$this->location);
        }
        /**
         * Returns object's ID that modified. This function can use when other controllers modify external objects
         *
         * @return int
         */
        public function storeExt() {
            if (self::is_valid()) {
                $obj = new $this->model($_REQUEST);
                try {
                    $result = (is_null($obj->id)) ? $obj->insert() : $obj->update();
                    $_SESSION['msg'] = 'success">Operação realizada com sucesso!';
                } catch(\PDOException $e) {
                    $_SESSION['msg'] = 'fail">Houve um erro. Por favor, confira as informações inseridas.';
                }
            } else {
                $_SESSION['msg'] = 'fail">Por favor, preencha os campos obrigatórios. ' . $_SESSION['msg'];
            }
            return $result;
        }
        /**
         * Controls what happens on system when an database line is deleted
         *
         * @return void
         */
        public function delete() {
            if ($_REQUEST['delete']) {
                $_REQUEST['id'] = $_REQUEST['delete'];
                $obj = new $this->model($_REQUEST);
                try {
                    $_SESSION['msg'] = 'success">Operação realizada com sucesso!';
                    (!is_null($obj->id)) ? $obj->remove() : $_SESSION['msg'] = 'fail">Houve um erro. Por favor, tente novamente.';
                } catch(\PDOException $e) {
                    $_SESSION['msg'] = 'fail">Houve um erro. Por favor, tente novamente.';
                }
            }
            header('Location:'.$this->location);
        }

        /**
         * Controls what happens on system when an user login have success or failure
         *
         * @return void
         */
        public function login() {
            $blood = lAtrium::getArterialBlood();
            if (isset($_POST)) {
                $obj = new $this->model($_REQUEST);
                $got = $obj->login();
                if ($got) {
                    (session_status() == PHP_SESSION_ACTIVE)?: session_start();
                    $_SESSION['id'] = $got->id;
                    $_SESSION['name'] = $got->name;
                    $_SESSION['level'] = $got->level;
                    $_SESSION['on'] = true;
                    $got = null;
                    header('Location: ' . $blood['homePage']);
                } else {
                    $_SESSION['msg'] = 'fail">Houve um erro. Por favor, tente novamente.';
                    header('Location: ' . $blood['rootPage']);
                }
            }
        }

        /**
         * Log out the current logged in user
         *
         * @return void
         */
        public function logout($location = null) {
            $blood = lAtrium::getArterialBlood();
            $location = empty($location) ? $blood['rootPage'] : $blood['escapePage'];
            (session_status() == PHP_SESSION_ACTIVE)?: session_start();
            session_destroy();
            header('Location: ' . $location);
        }

        /**
         * Verify if logged user have permission to access page
         *
         * @param  int $key
         *
         * @return mixed
         */
        public static function pagePermission($key = null) {
            $blood = lAtrium::getArterialBlood();
            (self::permission($key)) ? true : header('Location: ' . $blood['homePage']);
        }

        /**
         * Verify if logged user have permission to do something
         *
         * @param  int $key
         *
         * @return boolean
         */
        public static function permission($key = null) {
            return (isset($_SESSION['level']) && $_SESSION['level'] >= $key) ? true : false;
        }

        /**
         * Controls what happens on system when an user try to execute an action
         *
         * @return void
         */
        public function action() {
            (session_status() == PHP_SESSION_ACTIVE)?: session_start();
            if (empty($_SESSION['on'])) $this->actions = ['login', 'logout'];

            $this->actions = array_merge($this->actions, $this->offlineActions);

            if (key($_GET) !== null && in_array(key($_GET), $this->actions)) $_REQUEST['action'] = (key($_GET));

            if (isset($_REQUEST['action']) && in_array($_REQUEST['action'], $this->actions)) {
                $action = $_REQUEST['action'];
                if (in_array($action, $this->actions)) $this->$action();
            }
        }
    }
