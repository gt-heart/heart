<?php
    namespace SOS;
    require_once (__DIR__.'/../lAtrium.php');
    require_once (__DIR__.'/error.php');

    class Drugstore {
      public function __construct() {
        register_shutdown_function(array($this, 'shutdown'));
      }

      public function shutdown() {
        $blood = \Heart\lAtrium::getArterialBlood();

        (session_status() == PHP_SESSION_ACTIVE)?: session_start();

        $lastError = error_get_last();


        if ($blood['isDebug'] && !empty($lastError)) {
            $error = new \SOS\ErrorController($lastError);
            $_SESSION['error'] = $error->htmlfyError();
        }

        if (!empty($lastError)) {
            $_SESSION['msg'] = 'fail">OPA! Cuidado como brinca!';

            header('Location:'.$blood['errorPage']);
        }
      }
    }

    new \SOS\Drugstore();
?>
