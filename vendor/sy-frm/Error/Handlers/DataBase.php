<?php

    namespace Sy\Error\Handlers;

    use \Sy\Error\ErrorHandler,
        \Sy\Core;

    class DataBase extends ErrorHandler {
        protected function _message( $message = null ){
            $view   = new Core\View( __DIR__ . DS . '..' . DS . 'pages', 'php' );
            $output = $view->render( 'error_db', array(
                'error' => $message
            ) );
            die( $output );
        }
    }