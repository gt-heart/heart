<?php
    namespace SOS;

    class Drugstore {
      public function __construct() {
        register_shutdown_function(array($this, 'shutdown'));
      }

      public function shutdown() {
        $blood = \Heart\lAtrium::lAtriumObj()->getArterialBlood();

        $lastError = error_get_last();

        if ($blood['isDebug'] && !empty($lastError)) {
            $error = new \SOS\ErrorController($lastError);
            setcookie("HeartError", $error->htmlfyError(), 0, "/");
        }

        if (!empty($lastError)) {
            setcookie('HeartMsg', 'fail>OPA! Cuidado como brinca!', 0, "/");
            header('Location:'.$blood['errorPage']);
        }
      }
    }

    new \SOS\Drugstore();
