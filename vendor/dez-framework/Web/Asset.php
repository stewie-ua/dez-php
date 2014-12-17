<?php

    namespace Dez\Web;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait;

    class Asset extends Object {

        use AssetTrait, SingletonTrait;

        protected function init() {}

        public function addJs( $js ) {
            static::$stack['js'][] = $js;
        }

        public function addCss( $css ) {
            static::$stack['css'][] = $css;
        }

        static public function js( $js ) {
            static::instance()->addJs( $js );
        }

        static public function css( $css ) {
            static::instance()->addCss( $css );
        }

        static public function render() {
            return static::instance()->renderCss();
        }

    }