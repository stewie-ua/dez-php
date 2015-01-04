<?php

    namespace Dez\Web;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait,
        Dez\Web\Asset\AssetTrait;

    class Asset extends Object {

        use SingletonTrait, AssetTrait;

        protected function init() {}

        public function addJS( $js ) {
            static::$stack['js'][] = $js;
        }

        public function addCSS( $css ) {
            static::$stack['css'][] = $css;
        }

        static public function js( $js ) {
            static::instance()->addJS( $js );
        }

        static public function css( $css ) {
            static::instance()->addCSS( $css );
        }

        static public function render() {
            return static::instance()->renderCss()
                . static::instance()->renderJs();
        }

    }