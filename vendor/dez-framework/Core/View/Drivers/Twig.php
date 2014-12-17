<?php

    namespace Dez\Core\View\Drivers;

    use \Dez\Core\View\ViewAbstract;

    class Twig extends ViewAbstract {

        public function render( $templateFile = null, array $templateData = array() ) {
            try {
                $output = $this->_getTwig()->render( $templateFile .'.'. $this->templateExt, $templateData );
            } catch ( \Exception $e ) {
                $output = $e->getMessage();
            }
            return $output;
        }

        private function _getTwig() {
            static $instance;
            if( empty( $instance ) ) {
                import( 'libs.Twig:Autoloader' );
                \Twig_Autoloader::register();
                $loader     = new \Twig_Loader_Filesystem( $this->templateDirectory );
                $instance   = new \Twig_Environment( $loader, array(
                    'cache' => CACHE_DIR . DS . \Dez::app()->config->path( 'base/cache_tpl/twig' ),
                    'debug' => true
                ) );
                $this->_extTwig( $instance );
            }
            return $instance;
        }

        private function _extTwig( \Twig_Environment $twig ) {

        }

    }