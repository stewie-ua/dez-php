<?php

    namespace Dez\Core;

	class Session extends Object {
		
        use SingletonTrait, HasDataTrait;

        static protected
            $data = [];

        protected function init() {
            session_name( \Dez::cfg()->path( 'main.session_name' ) );
            session_id() || session_start();
            static::$data = & $_SESSION;
            $this->initCsrfToken();
        }

        public function getId() { return session_id(); }

        public function validateCsrfToken( $token = null ) {
            return $this->csrfToken == $token;
        }

        public function initCsrfToken() {
            if( ! $this->csrfToken ) {
                $token = $this->generateCsrfToken();
                $this->set( 'csrfToken', $token );
                setcookie( 'csrfToken', $token );
            }
            return $this->csrfToken;
        }

        public function getCsrfToken() {
            return $this->get( 'csrfToken', false );
        }

        public function generateCsrfToken() {
            $stack = array_merge( range( 0, 9 ), range( 'a', 'z' ), range( 'A', 'Z' ), [ '_', '-', '.' ] );
            shuffle( $stack );
            return join( '', array_slice( $stack, 0, 32 ) );
        }

        static public function id() { return static::instance()->id; }

        protected function & getData() { return static::$data; }
		
	}
