<?php
	
	namespace Sy\Core;
	
	class Request extends Object {

        use SingletonTrait;
		
		public 	$isAjax = false,
				$isPost = false,
                $isCli  = false,
				$method	= null,
				$post	= array(),
				$get	= array(),
                $cookie = array();

        protected function init(){

			$this->method = $_SERVER['REQUEST_METHOD'];
		
			if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ){
				$this->isAjax = true;
			}
			
			$this->isPost   = ( $this->method == 'POST' );
            $this->isCli    = ( php_sapi_name() == 'cli' );

			$gpc            = [ $_POST, $_GET, $_COOKIE ];
			
			if( get_magic_quotes_gpc() == true ){	
				$this->_stpipSlashesRecursive( $gpc );
			}
			
			$this->post 	= & $gpc[0];
			$this->get 		= & $gpc[1];
			$this->cookie	= & $gpc[2];

		}

        public function isPost(){
            return (boolean) $this->isPost;
        }

        public function isAjax(){
            return (boolean) $this->isAjax;
        }

        public function isCli(){
            return (boolean) $this->isCli;
        }

        public function getMethod() {
            return $this->method;
        }

        public function requestURI(){
			return $_SERVER['REQUEST_URI'];
		}

        public function server( $key = null, $default = null ) {
            $key    = strtoupper( $key );
            if( isset( $_SERVER[$key] ) ) {
                return $_SERVER[$key];
            } else {
                return $default;
            }
        }

        public function http( $key = null, $default = null ) {
            $key = 'HTTP_'. strtoupper( trim( $key ) );
            if( isset( $_SERVER[$key] ) ) {
                return $_SERVER[$key];
            } else {
                return $default;
            }
        }

        public function get( $key = null, $default = null ) {
            if( isset( $this->get[$key] ) ) {
                return $this->get[$key];
            } else if( $key === null ) {
                return $this->get;
            } else {
                return $default;
            }
        }

        public function post( $key = null, $default = null ) {
            if( isset( $this->post[$key] ) ) {
                return $this->post[$key];
            } else if( $key === null ) {
                return $this->post;
            } else {
                return $default;
            }
        }

        public function cookie( $key = null, $default = null ) {
            if( isset( $this->cookie[$key] ) ) {
                return $this->cookie[$key];
            } else if( $key === null ) {
                return $this->cookie;
            } else {
                return $default;
            }
        }

        public function file( $key = null, $default = null ) {
            if( isset( $_FILES[$key] ) ) {
                return $_FILES[$key];
            } else if( $key === null ) {
                return $_FILES;
            } else {
                return $default;
            }
        }

        public function request( $key, $default = null ) {
            $data = array_merge( $this->get, $this->post, $this->cookie );
            if( isset( $data[$key] ) ) {
                return $data[$key];
            } else {
                return $default;
            }
        }
				
		private function _stpipSlashesRecursive( array & $array ){
//			$array = is_array( $array ) ? $this->_stpipSlashesRecursive( $array ) : stripcslashes( $array );
		}
		
	}
