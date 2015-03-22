<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth     as AuthModel;
    use Dez\Auth\Model\Token    as TokenModel;

    class Api extends AuthAbstract {

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
            return TokenModel::query()->whereTokenKey( $token )->first();
        }

        /**
         * @return string $token
         */

        public function getToken( $login = null, $password = null ) {
            $auth = AuthModel::query()->whereEmail( $login )->wherePassword( $password )->first();
            if( $auth->id() > 0 ) {

            }
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