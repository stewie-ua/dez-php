<?php

	namespace Dez\Auth;

    use Dez\Auth\Model;

	class Auth {

		static private
            $_access        = null,
            $_storage       = array(),
            $_authModel     = null,
            $_sessionModel  = null;
		
		public function __construct(){

            static::$_access            = Access::instance();

            static::$_authModel         = new Model\Main();
            static::$_sessionModel      = new Model\Sessions();

            static::$_sessionModel->deleteOldSessions();

            $uni_key = \Dez::app()->request->cookie( 'uni_key', false );

            if( ! $uni_key ) {
                $uni_key = self::getUniKey();
            }

            $session = self::$_sessionModel->getSessionByUniKey( $uni_key );
            if( $session !== false ){
                $auth = self::$_authModel->getAuthById( $session['user_id'] );
                if( $auth != false ) {
                    $_SESSION['auth'] = json_encode( $auth );
                    self::$_storage = $auth;
                    $this->updateOnline();
                } else {
                    throw new \Exception( 'AuthID: '. $session['user_id'] .' dont exists' );
                }
            } else {
                $this->logout();
            }

		}
		
		public function isLogged(){
			return (boolean) $this->get( 'id' );
		}
		
		public function get( $field = 'id' ){
			if( isset( self::$_storage[$field] ) ){
				return self::$_storage[$field];
			}else{
				return null;
			}
		}

        public function id() {
            return $this->get( 'id' );
        }
		
		public function login( array $auth_data ){
			$this->logout();

			$login 		= trim( $auth_data[0] );
			$password	= self::hashPassword( $auth_data[1] );

            if( empty( $login ) ) {
                throw new \Exception( 'Login is empty' );
            }

			$auth = self::$_authModel->getFullAuth( $login, $password );
			
			if( ! $auth ){
                throw new \Exception( 'Login or password is incorrect' );
			}else{
				$_SESSION['auth']   = json_encode( $auth );
				self::$_storage     = $auth;
				
				$expired_date 	= time() + ( 86400 * 30 );
				$uni_key 		= self::getUniKey();
                $token_key 		= self::getTokenKey( $this->get( 'id' ) );

                $sessionData    = array(
                    'user_id'       => $this->get( 'id' ),
                    'uni_key'       => $uni_key,
                    'token_key'     => $token_key,
                    'user_agent'    => \Dez::app()->request->http( 'user_agent' ),
                    'user_ip'       => ip2long( getRealIP() ),
                    'expired_date'  => date( 'Y-m-d H:i:s', $expired_date ),
                    'last_date'     => date( 'Y-m-d H:i:s' )
                );

                self::$_sessionModel->addSession( $sessionData );
				setcookie( 'uni_key', $uni_key, $expired_date, '/' );
                setcookie( 'token_key', $token_key, $expired_date, '/' );
				return true;
			}
		}
		
		public function logout(){
			unset( $_SESSION['auth'] );
			self::$_storage = array();
			setcookie( 'uni_key', null, -1, '/' );
            self::$_sessionModel->deleteSession( self::getUniKey() );
		}

        public function updateOnline() {
            self::$_sessionModel->updateOnline( $this->get( 'id' ), self::getUniKey() );
        }

        public function access( $level = -1 ) {
            return static::$_access->access( $level, $this->get( 'level_access' ) );
        }

        public function accessToString( array $access = [] ) {
            return static::$_access->accessToString( $access );
        }

		public function add( array $auth_data ){
			$login 		= trim( $auth_data[0] );
			$email 		= trim( $auth_data[1] );
			$password	= self::hashPassword( $auth_data[2] );

            if( empty( $login ) ) {
                throw new \Exception( 'Login is empty' );
            }

            if( empty( $email ) ) {
                throw new \Exception( 'E-mail is empty' );
            }

            if( empty( $password ) ) {
                throw new \Exception( 'Password is empty' );
            }

            if( self::$_authModel->isExists( $login, $email ) ) {
                throw new \Exception( 'User already registered' );
            } else {
                $authData           = array(
                    'login'     => $login,
                    'email'     => $email,
                    'password'  => $password,
                    'added_at'  => date( 'Y-m-d H:i:s' )
                );
                return (boolean) self::$_authModel->addNewAuth( $authData );
            }
		}
		
		static private function getUniKey(){
            $hash = md5( $_SERVER['HTTP_USER_AGENT'] . long2ip( ip2long( getRealIP() ) & 0xffffff00 ) );
			return $hash;
		}

        static private function getTokenKey( $authId = -1 ) {
            return md5( $authId . self::getUniKey() );
        }
		
		static private function hashPassword( $password ){
			return md5( sha1( $password ) );
		}
		
	}