<?php

    namespace Sy\Core;

    class Message {

        use SingletonTrait;

        static protected
            $stack          = [],
            $emptyStack     = [ 'success' => [], 'info' => [] ],
            $session        = null;

        protected function init() {
            $this->session = Session::instance();
            if( ! $this->session->has( 'system_messages' ) ) {
                $this->session->set( 'system_messages', static::$emptyStack );
            }
            static::$stack = & $this->session->get( 'system_messages' );
        }

        public function raiseSuccess( $message = null ) {
            static::$stack['success'][] = $message;
        }

        public function raiseInfo( $message = null ) {
            static::$stack['info'][] = $message;
        }

        static public function success( $message ) {
            static::instance()->raiseSuccess( $message );
        }

        static public function info( $message ) {
            static::instance()->raiseInfo( $message );
        }

        public function render() {
            $stack                  = static::$stack;
            static::$stack          = static::$emptyStack;
            return ( new View( __DIR__ . '/Message/message-template', 'php' ) )
                ->render( 'template', [ 'stack' => $stack ] );
        }

    }