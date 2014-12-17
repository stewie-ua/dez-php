<?php

    namespace Sy\Core\Router;

    use Sy\Core\Object;
    use Sy\Core\SingletonTrait;

    class Result extends Object {

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

        public function getForceRun() {
            return $this->result['forceRun'];
        }

    }