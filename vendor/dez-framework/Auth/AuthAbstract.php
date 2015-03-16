<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth             as AuthModel;
    use Dez\ORM\Common\Object;
    use Dez\ORM\Common\SingletonTrait;

    abstract class AuthAbstract extends Object implements AuthInterface {

        use SingletonTrait;

        protected
            $auth  = null;

        protected function init( $data ) {
            return $this->initAuth( $data );
        }

        protected function setAuth( AuthModel $auth ) {
            $this->auth     = $auth;
        }

        public function getAuth() {
            return $this->auth;
        }

        abstract protected function initAuth( $data );

    }