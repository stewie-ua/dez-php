<?php

    namespace Dez\Core;

    class Context extends Object {

        use SingletonTrait;

        protected
            $child      = null,
            $parent     = null,
            $context    = null;

        public function setParentContext( static $context ) {
            $this->parent   = $context;
        }

        public function setChildContext( static $context ) {
            $this->child    = $context;
        }

        public function getParent() {
            return $this->parent;
        }

        public function getChild() {
            return $this->child;
        }

        protected function init() {

        }

    }