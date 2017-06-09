<?php
    //Important: To use that class and your properties correctly,
    //you will go need to name your database tables
    //with the same name of their correspondent model concatenated to 's'..
    // Sample:
    // - Model: User
    // - Correspondent database table: users

    namespace Model;

    //This Function believe that you're using the default tree files for yours projects. It'll find Classes that the Heart can't see.
    //While the Framework doesn't set a default to Tree Files, the function accept two ways to find mysterious classes.
    spl_autoload_register( function($className) { 
        $className = strtolower($className);
        if ( file_exists( __DIR__ . '/../../models/' .$className . '.php')) { 
            require_once __DIR__ . '/../../models/' . $className . '.php';
            return true; 
        } else if ( file_exists( __DIR__ . '/../../model/' .$className . '.php')) { 
            require_once __DIR__ . '/../../model/' . $className . '.php';
            return true;
        }
    });

    require_once(__DIR__.'/../lAtrium.php');
    require_once (__DIR__.'/../sos/drugstore.php');

    use \PDO;
    use \Heart\lAtrium as lAtrium;

    abstract class Base {

        /**
         * @var string [Sets the sufix to complete database views name]
         */
        const DB_VIEW = '_vw';

        /**
         * Field list wich defines database fillables.
         * The attributes that are mass assignable.
         * Relationship set the classes can call as attributes.
         * @var array
         */
        protected $fillable = [];
        protected $relationship = [];
        /**
         * Create dynamic objects attributes when it's called.
         * This's works only with simple call between objects.
        */
        function __get($key) {
            if(in_array($key, $this->relationship) || array_key_exists($key, $this->relationship) ) {
                $value = $key . "s_id";
                return ucfirst($key)::one($this->$value);
            } else {
                return $this->$key;
            }
        }
        /**
         * Generic dynamic construct for models
         * @param array $attributes [field list for search on database table/view]
         */
        function __construct($attributes = []) {

            $this->id = isset($attributes['id']) ? $attributes['id'] : null;

            !empty($attributes['password']) ? static::encryptPass($attributes['password']) : true;

            self::purifyAttributes($attributes);

        }
        /**
         * This function converts any object in array.
         */
        protected function objectRayX() {
            $arr = clone $this;
            unset($arr->relationship);
            $arr = (array)$arr;
            return $arr;
        }
        
        private function purifyAttributes($attributes = []) {
            foreach ($attributes as $key => $value) {
                if(in_array($key, $this->fillable) || array_key_exists($key, $this->fillable) && !empty($value)) {
                    $this->$key = self::typeVerify($key, $value);
                }
            }
            unset($this->fillable);
        }

        /**
         * This function converts datas to MYSQL format data.
         * Currently it only converts dates HAHAHAHAHA
         */
        protected function typeVerify($key, $value) {
            $arr = (array_keys($this->fillable, 'date'));
            $date = date_create_from_format('d/m/Y', $value);
            return (in_array($key, $arr))? date($date->format('Y-m-d')): $value;
        }

        /**
         * MySQL database connection
         *
         * @return object \PDO
         */
        protected static function connect() {
            $blood = lAtrium::getArterialBlood();
            try {
                $pdo = new \PDO('mysql:host='.$blood['host'].';dbname='.$blood["database"], $blood['user'], $blood['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (\PDOException $e) {
                //It's need a pretty error page
                echo 'Há algo estranho ocorrendo... Melhor correr!';
                die();
            }
        }

        /**
         * Encryptes an inserted Password
         *
         * @param  string $password
         * @return void
         */
        private function encryptPass(&$password) {
            array_push($this->fillable, 'password');
            $password = md5($password);
        }

        /**
         * Formats entity's name to search database tables
         *
         * @param  boolean $isView [TRUE(default) to search on an view or FALSE to search on an table.]
         * @return string
         */
        protected static function entity($isView = true) {
            $blood = lAtrium::getArterialBlood();

            ($isView && $blood['useDbViews']) ? $suffix = self::DB_VIEW : $suffix = NULL;
            return lcfirst(get_called_class()).'s'.$suffix;
        }

        /**
         * Verify credentials for system access
         *
         * @return object [Informations about the correspondent user.]
         */
        public function login() {
            $connect = self::connect();
            $stm = $connect->prepare('SELECT * FROM `'.self::entity(false).'` WHERE email = :email AND password = :password LIMIT 1');
            $stm->BindValue(':email',$this->email, PDO::PARAM_STR);
            $stm->BindValue(':password',$this->password, PDO::PARAM_STR);
            $stm->execute();
            return $stm->fetch(PDO::FETCH_OBJ);
        }

        /**
         * Querys all lines from the entity's table view
         *
         * @param  string $order [Sets the ORDER BY param and sorting to SQL query.]
         * @return object
         */
        public static function all($order = 'id ASC', $where = null) {
            $connect = self::connect();
            $where = (!$where)? null : ' WHERE '.$where;
            $stm = $connect->query('SELECT * FROM `'.self::entity().'`'.$where.' ORDER BY '.$order);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        }

        /**
         * Querys one line from the entity's table view
         *
         * @param  integer $id [primary key]
         * @return object
         */
        public static function one($id) {
            $connect = self::connect();
            $stm = $connect->prepare('SELECT * FROM `'.self::entity().'` WHERE id = :id LIMIT 1');
            $stm->bindValue(":id", $id, PDO::PARAM_INT);
            $stm->execute();
            return $stm->fetch(PDO::FETCH_OBJ);
        }
        /**
         *
         */
        public static function selectIt($clauses = [], $order = null, $limit = null) {
            $where = null;
            foreach ($clauses as $key => $value) {
                if (!empty($value))
                    $where .= (!empty($where))? ' AND ' . $key . ' = :' . $key: $key . ' = :' . $key;
                else
                    $where .= (!empty($where))? ' AND ' . $key . ' IS NULL' : $key . ' IS NULL';

            }

            (empty($order))?: $order = ' ORDER BY ' . $order;
            (empty($limit))?: $limit = ' LIMIT ' . $limit;

            $connect = self::connect();
            $stm = $connect->prepare('SELECT * FROM `'.self::entity().'` WHERE ' . $where . $order . $limit );
            $stm->execute($clauses);
            return ($limit > 1)? $stm->fetchAll(PDO::FETCH_OBJ): $stm->fetch(PDO::FETCH_OBJ);
        }

        /**
         * Inserts one line in the entity's table
         *
         * @return boolean [TRUE on success or FALSE on failure.]
         */
        public function insert() {
            $connect = self::connect();
            $attr = $this->objectRayX();
            $stm = $connect->prepare('INSERT INTO `'.self::entity(false).'`('.implode(',  ', array_keys($attr)).', createdAt, updatedAt) VALUES (:'.implode(', :', array_keys($attr)).', NOW(), NOW())');
            $stm->execute($attr);
            return $connect->lastInsertId();
        }

        /**
         * Update one line from the entity's table
         *
         * @return boolean [TRUE on success or FALSE on failure.]
         */
        public function update() {
            $connect = self::connect();
            $attr = $this->objectRayX();
            $sets = '';
            //var_dump($this);die;
            //var_dump($this->objectRayX());die;
            //var_dump($this->team);die;
            //var_dump($this->team->name);die;
            foreach ($attr as $key => $value) {
                $sets .= $key . ' = :' . $key . ', ';
            }
            $stm = $connect->prepare('UPDATE `'.self::entity(false).'` SET '.$sets.' updatedAt = NOW() WHERE id = '.$this->id);
            return $stm->execute($attr);
        }

        /**
         * Remove one line from the entity's table
         *
         * @return boolean [TRUE on success or FALSE on failure.]
         */
        public function remove($where = null) {
            if (empty($where)) {
                $where = "id = {$this->id}";
            }
            $where = ($where == "*")? "": ' WHERE ' . $where;

            $connect = self::connect();
            $stm = $connect->prepare('DELETE FROM `'.self::entity(false).'`'.$where);
            return $stm->execute();
        }

        /**
         * Execute any MySQL Query weel-formed
         *
         * @param  string $sql [an MySQL Query weel-formed]
         * @param  array  $attr   [description]
         *
         * @return object's array
         */
        public function query($sql, $attr = array()) {
            $connect = self::connect();
            $stm = $connect->prepare($sql);
            $stm->execute($attr);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        }
    }
