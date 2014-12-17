<?php

    namespace Dez\Core;

    class Context extends Object {

        use SingletonTrait;

        protected
            $controller = null;

        public function getController() {
            return $this->controller;
        }

        protected function init() {}

    }