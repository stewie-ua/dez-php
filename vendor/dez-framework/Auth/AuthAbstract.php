<?php

    namespace Dez\Auth;

    use Dez\Auth\Model\Auth             as AuthModel;
    use Dez\Core\Request;
    use Dez\Core\Server;
    use Dez\ORM\Common\Object;
    use Dez\ORM\Common\SingletonTrait;
    use Dez\ORM\Model\Table;

    class AuthIncorrect extends \Exception {}

    abstract class AuthAbstract extends Object implements AuthInterface {

        use SingletonTrait;

        protected
            $model          = null,

            $userAgent      = null,
            $userIp         = null;

        protected function init() {
            $this->userIp       = Server::instance()->getUserIpLong();
            $this->userAgent    = Request::instance()->http( 'user_agent' );
        }

        /**
         * @return static
         * @param AuthModel $model
         */

        protected function setModel( AuthModel $model ) {
            $this->model     = $model;
            return $this;
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