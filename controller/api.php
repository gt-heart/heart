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

    require_once(__DIR__.'/../lAtrium.php');

    use \Heart\lAtrium as lAtrium;

    abstract class Api {

        /**
         * Stores valid actions to use on system
         *
         * It can be overloaded by each controller
         *
         */
        public $actions = ['delete', 'store', 'logout'];

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

        public $blood;

        /**
         * Generic construct for controllers
         */
        function __construct() {

            $this->blood = lAtrium::getArterialBlood();
            $this->model = self::get_model(get_called_class());
            self::action();
        }

        /**
         * Gets model name, based on controller name
         *
         * It'll be used to do new class instances from Model
         *
         * @return string
         */
        public static function get_model($controller) {
            return str_replace('_controller', '', $controller);
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
            $result = ($id) ? $this->model::one($id) : null;
            unset($result->password);
            return $result;
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
                    http_response_code(201);
                    $result = (is_null($obj->id)) ? $obj->insert() : $obj->update();
                } catch(\PDOException $e) {
                    http_response_code(422);
                    $result = 'Por favor, confira as informações inseridas.'.$e;
                }
                return $result;
            } else {
                http_response_code(400);
                return $_SESSION['msg'];
            }
        }

        /**
         *
         */
        public function getName4Relation() {
            return lcfirst($this->model).'s_id';
        }

        /**
         *
         */
        public function storeRelationalDatas() {
            if (isset($_REQUEST['password'])) unset($_REQUEST['password']);

            foreach ($this->relationals as $relation) {
                $obj = new $relation($_REQUEST);
                $obj->insert();
            }
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
                    return (!is_null($obj->id)) ? $obj->remove() : $_SESSION['msg'] = 'fail">Houve um erro. Por favor, tente novamente.';
                } catch(\PDOException $e) {
                    return $_SESSION['msg'] = 'Houve um erro. Por favor, tente novamente.';
                }
            }
        }

        /**
         * Controls what happens on system when an user login have success or failure
         *
         * @return void
         */
        public function login() {
            if (isset($_POST)) {
                $obj = new $this->model($_REQUEST);
                $got = $obj->login();
                if ($got) {
                    (session_status() == PHP_SESSION_ACTIVE)?: session_start();
                    $got = self::one($got->id);
                    unset($got->password);
                    $_SESSION['on'] = true;
                    return $got;
                } else {
                    http_response_code(400);
                    return array('message' => 'error' );;
                }
            }
        }

        /**
         * Log out the current logged in user
         *
         * @return void
         */
        public function logout() {
            session_destroy();
            return '{message: Saiu!}';
        }

        /**
         * Verify if logged user have permission to access page
         *
         * @param  int $key
         *
         * @return mixed
         */
        public static function pagePermission($key = null) {
            (self::permission($key)) ? true : header('Location: home');
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
