<?php

    namespace Sy\Core;

    class Context extends Object {

        use SingletonTrait;

        protected
            $controller = null;

        public function getController() {
            return $this->controller;
        }

        protected function init() {}

    }