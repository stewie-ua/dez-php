<?php

    use Dez\Core\Object,
        Dez\Hook\Hook;

    class initWebAssetHook extends Object {

        public function registerEvents() {
            Hook::instance()->attach( 'initApp',        [ $this, 'initApp' ] );
            Hook::instance()->attach( 'beforeRender',   [ $this, 'beforeRender' ] );
        }

        protected function initApp() {}

        protected function beforeRender() {}

    }