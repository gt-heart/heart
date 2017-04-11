<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
require_once(__DIR__.'/http/response.php');

abstract class API {
    use \Http\Response;

    public $contexts = [];
    public $project = null;

    public $controller, $uri, $entity, $rEntity, $context, $method, $function;
    public $where = null, $id = null, $order = null;
    public $request;

    function __construct($project = null) {
        if (!empty($project)) $this->project = $project;
        $_REQUEST = $this->request = json_decode(file_get_contents("php://input"), true);

        self::readUrl();
    }

    public function explodeUrl() {
        $uri = str_replace($this->project, '', $_SERVER['REQUEST_URI']);
        $uri = trim(parse_url($uri)['path'], '/');
        if (strpos($uri, "heart/sos/ops.php")) include('heart/sos/ops.php');
        $this->uri = explode('/', $uri);
    }

    public function distributeLayers() {
        $this->entity = $this->uri[0];
        $this->context = rtrim($this->entity, 's');
        $this->id = (!empty($this->uri[1]) && is_numeric($this->uri[1]))? $this->uri[1]: null;

        if (!empty($this->uri[1]) && !is_numeric($this->uri[1])) self::distributeComands();
    }

    public function distributeComands() {
        if (array_key_exists($this->uri[1], $this->contexts)) self::loadRelationalContext();
        elseif (in_array($this->uri[1], $this->contexts[$this->context]['functions']) ||
         array_key_exists($this->uri[1], $this->contexts[$this->context]['functions'])) $this->function = $this->uri[1];
        else $this->context = null;
    }

    public function loadRelationalContext() {
        $this->rEntity = $this->uri[1];
        $this->where = (!empty($this->uri[2]) && is_numeric($this->uri[2])) ? $this->rEntity.'s_id='.$this->uri[2]: null;
        // print_r($this->where);
        $this->order = (!empty($this->uri[2]) && is_numeric($this->uri[2]) && !empty($this->uri[3]))? $this->uri[3]: 'id';
    }

    public function getMethod() {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        if ($this->method == 'get' && (empty($this->uri[1]) || !empty($this->where))) $this->method = 'getAll';
    }

    function verifyPermissions() {
        $error = 0;

        if (!array_key_exists($this->context, $this->contexts) ||
                (!in_array($this->method, $this->contexts[$this->context]['methods'])
                && !in_array('resources', $this->contexts[$this->context]['methods']) && "options" != $this->method)
        ) {
            return self::code(404);
        }
        return $error;
    }

    public function readUrl() {
        self::explodeUrl();
        if ($this->uri[0] == 'heart') {include('heart/sos/ops.php'); return false;}
        self::distributeLayers();
        self::getMethod();
        self::pulseIt();
    }

    public function pulseIt() {
        $call = self::call();
        header('Content-Type: application/json');
        print_r($call);
    }

    public function call() {
        $error = self::verifyPermissions();

        if (!empty($error)) return json_encode($error);
        if (!empty($this->function)) return json_encode(self::execute($this->function, true));

        $execute = $this->method.'Api';
        return self::$execute();
    }

    public function execute($execute, $request = false) {
        self::prepare();
        $result = ($request == true)? $this->controller->$execute($this->request) : $this->controller->$execute();
        return $result;
    }

    public function prepare() {
        $contexts = [ $this->context ];
        require_once('heart/pulse.php');
        $_REQUEST = $this->request;
        self::getController();
    }

    public function getController() {
        $controller = ucfirst($this->context).'_controller';
        $this->controller = new $controller();
    }

    public function optionsApi() {
        return true;
    }

    /**
    * Calls delete function from controller
    * @return [type] [description]
    */
    public function deleteApi() {
        if (is_numeric($this->uri[1])) $this->request['delete'] = $this->uri[1];
        self::execute('delete', true);
    }

    public function getAllApi() {
        self::prepare();
        return json_encode($this->controller->loadAll($this->order, $this->where));
    }

    public function getApi() {
        self::prepare();
        return json_encode($this->controller->one($this->id));
    }

    public function putApi() {
        $_REQUEST = json_decode(file_get_contents("php://input"), true);
        $result = self::execute('store');
        return ($result)? json_encode($this->controller->one($_REQUEST['id'])): self::code(401);
    }

    public function postApi() {
        $_REQUEST = json_decode(file_get_contents("php://input"), true);
        return self::execute('store');
    }
}
