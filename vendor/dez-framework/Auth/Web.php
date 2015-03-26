<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth     as AuthModel;
    use Dez\Auth\Model\Session  as SessionModel;
    use Dez\Cookie\Cookie;
    use Dez\Core\Request;
    use Dez\ORM\Common\DateTime;

    class Web extends AuthAbstract {

        protected function init() {
            parent::init();
            $authKey    = Request::instance()->cookie( Request::AUTH_COOKIE_KEY, null );
            if( null !== $authKey ) {
                $session = SessionModel::query()->whereUniKey( $this->getHash( $authKey ) )->first();
                if( $session->id() > 0 ) {
                    $this->setModel( AuthModel::one( $session->getAuthId() ) ); return;
                }
            }
            $this->setModel( new AuthModel() );
        }

        public function authenticate( $login = null, $password = null ) {

            $auth       = AuthModel::query()->whereEmail( $login )->wherePassword( static::hashPassword( $password ) )->first();

            if( $auth->id() > 0 ) {

                $cookieHash     = md5( rand( 1, 1000000 ) );

                $hash           = $this->getHash( $cookieHash );
                $currentDate    = new DateTime( '+ 30 days ' );

                Cookie::set( Request::AUTH_COOKIE_KEY, $cookieHash, $currentDate->getTimestamp() );

                SessionModel::insert( [
                    'auth_id'       => $auth->id(),
                    'uni_key'       => $hash,
                    'user_agent'    => $this->userAgent,
                    'user_ip'       => $this->userIp,
                    'expired_date'  => $currentDate->mySQL(),
                    'last_date'     => ( new DateTime() )->mySQL()
                ] );

                $this->setModel( $auth );

            } else {
                throw new \Exception( 'Incorrect email or password' );
            }

            return $auth;
        }

        public function id() {
            return $this->getModel()->id();
        }

        public function logout() {
            $this->setModel( new AuthModel() );
            return $this;
        }

        protected function getHash( $salt = '' ) {
            return md5( $this->userAgent . ( $this->userIp & 0xffffff00 ) . $salt );
        }

        static protected function hashPassword( $password = '' ) {
            return md5( sha1( $password ) );
        }

    }