<?php

    namespace Dez\Core;

    use Dez\Cookie\Cookie;

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
                $this->set( 'csrf_token', $token );
                Cookie::set( 'csrf_token', $token, time() + ( 3600 * 15 ), '/' );
            }
            return $this->csrfToken;
        }

        public function getCsrfToken() {
            return $this->get( 'csrf_token', false );
        }

        public function generateCsrfToken() {
            $stack = array_merge( range( 0, 9 ), range( 'a', 'z' ), range( 'A', 'Z' ), [ '_', '-', '.' ] );
            shuffle( $stack );
            return join( '', array_slice( $stack, 0, 32 ) );
        }

        static public function id() { return static::instance()->id; }

        protected function & getData() { return static::$data; }
		
	}
