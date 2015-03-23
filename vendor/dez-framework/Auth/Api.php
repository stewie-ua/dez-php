<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth     as AuthModel;
    use Dez\Auth\Model\Token    as TokenModel;
    use Dez\Core\Request;
    use Dez\Core\Server;

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
                $token  = new TokenModel();
                $token
                    ->setUserId( $auth->id() )
                    ->setUserAgent( Request::instance()->server( 'user_agent' ) )
                    ->setUserIp( Server::instance()->getUserIpLong() )
                    ->setExpiredDate( ( new \DateTime( '+ 30 days' ) )->format( 'Y-m-d H:i:s' ) )
                    ->setLastDate( ( new \DateTime( 'now' ) )->format( 'Y-m-d H:i:s' ) );
                return $token->save()->getTokenKey();
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