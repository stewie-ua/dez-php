<?php

    namespace Dez\Core\View\Drivers;

    use \Dez\Core\View\ViewAbstract;

    class Native extends ViewAbstract {

        public function render( $templateFile = null, array $templateData = array() ) {
            try {
                $file = $this->makeTemplateFile( $templateFile );
            } catch ( \Exception $e ) {
                return $e->getMessage();
            }

            return self::_render( $file, $templateData );
        }

        static private function _render( $file = null, array $data = array() ) {
            ob_start();
                extract( $data );
                include $file;
                $output = ob_get_contents();
            ob_clean();
            return $output;
        }

    }