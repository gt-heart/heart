<?php

class FileHelper {
    public static $allowedTypes = array();

    public $name;
    public $type;
    private $finfo;
    private $tmp_name;
    private $finfo_file;
    private $size;

    function __construct($file, $allowedTypes = []) {
        if ($allowedTypes) static::$allowedTypes = $allowedTypes;
        $this->error = $file['error'];
        if (self::verifyFormErrors()) {
            $this->name = md5(sha1(date('d-m-y H:i:s').$file['tmp_name']));
            $this->finfo = finfo_open(FILEINFO_MIME_TYPE);
            $this->tmp_name = $file['tmp_name'];
            $this->finfo_file = finfo_file($this->finfo, $this->tmp_name);
            $exp = explode('/', $this->finfo_file);
            $this->type = end($exp);
            $this->size = $file['size'];
        }
    }

    public function save($dir) {
        if (!self::validateType()) return false;
        $dir_array = explode('/', $dir);
        end($dir_array);
        $file_name = prev($dir_array);
        $final_path = $dir.'/'.$this->name.".".$this->type;
        try {
            if (!move_uploaded_file($this->tmp_name, $final_path)) {
                throw new RuntimeException('Failed to move uploaded file.');
            }
        } catch (RuntimeException $e) {
            return false;
        }
        return $final_path;
    }

    public function verifyFormErrors() {
        return ($this->error == UPLOAD_ERR_OK);
    }

    public function validateSize($size = 1000000) {
        return ($this->size < $size);
    }

    public function validateType() {
        return (sizeof(static::$allowedTypes) > 0)? in_array($this->type, static::$allowedTypes): true;
    }
}

?>
