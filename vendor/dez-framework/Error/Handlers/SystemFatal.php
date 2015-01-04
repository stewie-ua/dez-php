<?php

    namespace Dez\Error\Handlers;

    use \Dez\Error\ErrorHandler,
        \Dez\View\View;

    class SystemFatal extends ErrorHandler {
        protected function _message( $message = null ){
            ob_clean();
            $view   = View::instance()->setPath( __DIR__ . '/../pages' );
            $output = $view->render( 'error_custom', [ 'error' => $message ] );
            die( $output );
        }
    }