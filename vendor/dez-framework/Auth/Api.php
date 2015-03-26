<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth     as AuthModel;
    use Dez\Auth\Model\Token    as TokenModel;
    use Dez\Core\Request;
    use Dez\Core\Server;
    use Dez\ORM\Common\DateTime;

    class Api extends AuthAbstract {

        protected function init() {
            $this->setModel( new AuthModel() );
        }

        /**
         * @return static
         * @param string $token
        */

        public function authenticate( $token = null ) {
            $this->setModel( AuthModel::one( $this->getTokenModel( $token )->getAuthId() ) );
            return $this;
        }

        /**
         * @return TokenModel $tokenModel
         * @param string $token
         */

        public function getTokenModel( $token = null ) {
            TokenModel::query()->whereExpiredDate( ( new DateTime() )->mySQL(), '<=' )->find()->delete();
            $token = TokenModel::query()->whereTokenKey( $token )->first();
            if( $token->id() > 0 ) {
                $token->setLastDate( ( new DateTime() )->mySQL() )->save();
            }
            return $token;
        }

        /**
         * @return TokenModel $token
         */

        public function getToken( $login = null, $password = null ) {
            $auth   = AuthModel::query()->whereEmail( $login )->wherePassword( $password )->first();
            $token  = new TokenModel();
            if( $auth->id() > 0 ) {
                $token
                    ->setAuthId( $auth->id() )
                    ->setUserAgent( $this->userAgent )
                    ->setUserIp( $this->userIp )
                    ->setExpiredDate( ( new DateTime( '+ 30 days' ) )->mySQL() )
                    ->setLastDate( ( new DateTime( 'now' ) )->mySQL() );
                $token->setTokenKey( $this->getUniKey( $token ) )->save();
            }
            return $token;
        }

        /**
         * @return static
         */

        public function logout() {
            $this->setModel( new AuthModel() );
            return $this;
        }

        /**
         * @return int $id
         */

        public function id() {
            return $this->getModel()->id();
        }

    }