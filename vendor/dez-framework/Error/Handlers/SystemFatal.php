<?php

    namespace Dez\Error\Handlers;

    use \Dez\Error\ErrorHandler,
        \Dez\Core;

    class SystemFatal extends ErrorHandler {
        protected function _message( $message = null ){
            ob_clean();
            $view   = new Core\View( __DIR__ . DS . '..' . DS . 'pages', 'php' );
            $output = $view->render( 'error_custom', array(
                'error' => $message
            ) );
            die( $output );
        }
    }