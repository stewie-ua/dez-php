<?php

    namespace Dez\Auth;

    use Dez\ORM\Common\Object;
    use Dez\ORM\Common\SingletonTrait;

    abstract class AuthAbstract extends Object implements AuthInterface {

        use SingletonTrait;

        protected
            $user  = null;

        protected function init() {
            $this->user    = static::getUser();
        }

        abstract static protected function getUser();

    }