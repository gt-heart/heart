<?php

    /*
     * Initalize Heart Framework
     */

    /*
     * Preparing Classes' Loading and Starting Execption Errors
     */

    require_once(__DIR__ . '/controller/base.php');
    require_once(__DIR__ . '/model/base.php');
    require_once(__DIR__.'/rAtrium.php');
    require_once(__DIR__. '/lAtrium.php');

    if ( strpos( $_SERVER['PHP_SELF'], "views" ) !== false ) {
        require_once(__DIR__.'/sos/error.php');
        require_once(__DIR__ . '/helpers/session.php');
        require_once(__DIR__ . '/helpers/print.php');
        require_once(__DIR__.'/sos/drugstore.php');
        require_once( __DIR__ . '/pulse.php');
    }
