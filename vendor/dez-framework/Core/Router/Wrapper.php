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

        public function setModuleName( $module = null ) {
            $this->result['module'] = $module; return $this;
        }

        public function getControllerName() {
            return $this->result['controller'];
        }

        public function setControllerName( $controller = null ) {
            $this->result['controller'] = $controller; return $this;
        }

        public function getActionName() {
            return $this->result['action'];
        }

        public function setActionName( $action = null ) {
            $this->result['action'] = $action; return $this;
        }

        public function getParams() {
            return $this->result['params'];
        }

        public function setParams( array $params = [] ) {
            $this->result['params'] = $params; return $this;
        }

        public function getMethod() {
            return $this->result['method'];
        }

        public function setMethod( $method = null ) {
            $this->result['method'] = $method; return $this;
        }

    }