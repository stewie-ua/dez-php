<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth             as AuthModel;
    use Dez\ORM\Common\Object;
    use Dez\ORM\Common\SingletonTrait;
    use Dez\ORM\Model\Table;

    class AuthIncorrect extends \Exception {}

    abstract class AuthAbstract extends Object implements AuthInterface {

        use SingletonTrait;

        protected
            $model  = null;

        protected function init() {
            $this->setModel( new AuthModel() );
        }

        /**
         * @return static
         */

        protected function setModel( AuthModel $model ) {
            $this->model     = $model;
        }

        /**
         * @return AuthModel $authModel
        */

        protected function getModel() {
            return $this->model;
        }

        /**
         * @return array $data
        */

        protected function getData() {
            return $this->getModel()->toArray();
        }

        /**
         * @return string $uni_key
         */

        protected function getUniKey( Table $model ) {
            return md5( implode( '::', $model->toArray() ) );
        }

        /**
         * @return string $value
         * @param $key string
         * @param $default mixed
         */

        public function get( $key = null, $default = null ) {
            $data   = $this->getData();
            return isset( $data[$key] ) ? $data[$key] : $default;
        }

    }