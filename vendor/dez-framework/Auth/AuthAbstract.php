<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth             as AuthModel;
    use Dez\ORM\Common\Object;
    use Dez\ORM\Common\SingletonTrait;

    class AuthIncorrect extends \Exception {}

    abstract class AuthAbstract extends Object implements AuthInterface {

        use SingletonTrait;

        protected
            $model  = null;

        protected function init() {
            $this->setModel( new AuthModel() );
        }

        protected function setModel( AuthModel $model ) {
            $this->model     = $model;
        }

        /**
         * @return AuthModel $authModel
        */

        protected function getModel() {
            return $this->model;
        }

        protected function getData() {
            return $this->getModel()->toArray();
        }

        public function get( $key = null, $default = null ) {
            $data   = $this->getData();
            return isset( $data[$key] ) ? $data[$key] : $default;
        }

    }