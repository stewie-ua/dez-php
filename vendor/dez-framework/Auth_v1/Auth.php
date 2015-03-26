<?php

	namespace Dez\Auth;

    use Dez\Auth\Model,
        Dez\Auth\ACL\ACL,
        Dez\Core\Session,
        Dez\Cookie\Cookie;

	class Auth {

		static private

            $_storage       = [],
            $_authModel     = null,
            $_sessionModel  = null,
            $_session       = null;
		
		public function __construct(){

            static::$_authModel         = new Model\Main();
            static::$_sessionModel      = new Model\Sessions();

            static::$_session           = Session::instance();

            static::$_sessionModel->deleteOldSessions();

            $uniqueKey = \Dez::app()->request->cookie( 'unique_key', false );

            if( $uniqueKey ) {
                $uniqueKey = self::getUniKey( $uniqueKey );
            }

            $session = self::$_sessionModel->getSessionByUniKey( $uniqueKey );
            if( $session !== false ){
                $auth = self::$_authModel->getAuthById( $session['user_id'] );
                if( $auth != false ) {
                    static::$_session->set( 'auth', json_encode( $auth ) );
                    static::$_storage = $auth;
                    $this->updateOnline( $uniqueKey );
                } else {
                    throw new \Exception( 'AuthID: '. $session['user_id'] .' dont exists' );
                }
            } else {
                $this->logout();
            }

		}
		
		public function (){
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
		
		public function login( array $auth_data = [] ){

			$login 		= trim( $auth_data[0] );
			$password	= self::hashPassword( $auth_data[1] );

            if( empty( $login ) ) throw new \Exception( 'Login is empty' );

			$auth = self::$_authModel->getFullAuth( $login, $password );
			
			if( ! $auth ){
                throw new \Exception( 'Login or password is incorrect' );
			} else {
				static::$_session->set( 'auth', json_encode( $auth ) );
				self::$_storage     = $auth;

				$expiredDate 	    = time() + ( 86400 * 30 );
                $cookieRandomKey    = static::$_session->generateCsrfToken();
				$uniKey 		    = self::getUniKey( $cookieRandomKey );
                $tokenKey 		    = self::getTokenKey( $this->get( 'id' ) );

                $sessionData    = array(
                    'user_id'       => $this->get( 'id' ),
                    'uni_key'       => $uniKey,
                    'access_token'  => $tokenKey,
                    'user_agent'    => \Dez::app()->request->http( 'user_agent' ),
                    'user_ip'       => ip2long( getRealIP() ),
                    'expired_date'  => date( 'Y-m-d H:i:s', $expiredDate ),
                    'last_date'     => date( 'Y-m-d H:i:s' )
                );

                static::$_session->set( 'session_data', $sessionData );

                self::$_sessionModel->addSession( $sessionData );

				Cookie::set( 'unique_key',    $cookieRandomKey, $expiredDate, '/' );
                Cookie::set( 'access_token',  $tokenKey, $expiredDate, '/' );

				return true;
			}
		}
		
		public function logout(){
			static::$_session->set( 'auth', json_encode( [] ) );
			self::$_storage = array();
            self::$_sessionModel->deleteSession( self::getUniKey(
                \Dez::app()->request->cookie( 'unique_key', 0 )
            ) );
        }

        public function updateOnline( $uniqueKey ) {
            self::$_sessionModel->updateOnline( $this->get( 'id' ), $uniqueKey );
        }

        public function access( $level = -1 ) {
            return true;
        }

        public function has( $permission = null ) {
            return ACL::hasUserPermission( $this->id(), $permission );
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
		
		static private function getUniKey( $uniqueId = 0 ) {
            $hash = md5( $_SERVER['HTTP_USER_AGENT'] . long2ip( ip2long( getRealIP() ) & 0xffffff00 ) . $uniqueId );
			return $hash;
		}

        static private function getTokenKey( $authId = -1 ) {
            return md5( $authId . self::getUniKey( $authId ) );
        }
		
		static private function hashPassword( $password ){
			return md5( sha1( $password ) );
		}
		
	}