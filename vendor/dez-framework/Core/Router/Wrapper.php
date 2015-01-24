<?php

    namespace Dez\Core\Router;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait;

    class Wrapper extends Object {

        use SingletonTrait;

        protected
            $result = [];

        protected function init( $result = [] ) {
            $this->result = $result;
        }

        public function getModuleName() {
            return $this->result['module'];
        }

        public function getControllerName() {
            return $this->result['controller'];
        }

        public function getActionName() {
            return $this->result['action'];
        }

        public function getParams() {
            return $this->result['params'];
        }

        public function getMethod() {
            return $this->result['method'];
        }

    }