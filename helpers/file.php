<?php

class FileHelper {
    public static $allowedTypes = array();

    public $name;
    public $type;

    function __construct($file) {
        $this->name = md5(sha1(date('d-m-y H:i:s').$file['tmp_name']));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $this->type = end((explode('/', finfo_file($finfo, $file['tmp_name']))));
    }

    public function save($dir) {
        $dir_array = explode('/', $dir);
        end($dir_array);
        $file_name = prev($dir_array);
        $final_path = $dir.$this->name.".".$this->type;
        if (!move_uploaded_file($_FILES[$file_name]['tmp_name'], $final_path))
            return false;
    }

    public static function validateType($file) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $file['tmp_name']);
        return in_array($type, static::$allowedTypes);
    }
}

?>
