<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth     as AuthModel;
    use Dez\Auth\Model\Session  as SessionModel;

    class Web extends AuthAbstract {

        public function authenticate( $uniKey = null ) {
            $this->setModel( AuthModel::one( SessionModel::query()->whereUniKey( $uniKey )->first()->getAuthId() ) );
        }

        public function id() {
            return $this->getModel()->id();
        }

        public function logout() {
            $this->setModel( new AuthModel() );
            return $this;
        }

    }