<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth     as AuthModel;
    use Dez\Auth\Model\Token    as TokenModel;

    class Api extends AuthAbstract {

        protected function initAuth( $data ) {
            $tokenKey   = $data;
            $token      = TokenModel::query()->whereTokenKey( $tokenKey )->first();
            if( $token->exists() ) {
                $this->setAuth( AuthModel::one( $token->getAuthId() ) );
            } else {
                $this->setAuth( new AuthModel() );
            }
            return $this;
        }

    }