<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth;
    use Dez\ORM\Common\Object;
    use Dez\ORM\Common\SingletonTrait;

    abstract class AuthAbstract extends Object implements AuthInterface {

        use SingletonTrait;

        protected
            $auth  = null;

        protected function init( $data ) {
            $this->initAuth( $data );
        }

        protected function setAuth( Auth $auth ) {
            $this->auth     = $auth;
        }

        protected function getAuth() {
            return $this->auth;
        }

        abstract protected function initAuth( $data );

    }