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
                    $this->setModel( AuthModel::one( $session->getAuthId() ) );
                }
            } else {
                $this->setModel( new AuthModel() );
            }

        }

        public function authenticate( $login = null, $password = null ) {
            $auth   = AuthModel::query()->whereEmail( $login )->wherePassword( $password )->first();

            if( $auth->id() > 0 ) {
                Cookie::set( Request::AUTH_COOKIE_KEY, $this->getHash( rand( 1, 1000000 ) ), ( new DateTime( '+ 30 days ' ) )->getTimestamp() );
                $this->setModel( $auth );
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
            return sha1( $this->userAgent . ( $this->userIp & 0xffffff00 ) . $salt );
        }

    }