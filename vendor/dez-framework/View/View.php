<?php

    namespace Dez\View;

    use Dez\Error\Exception\RuntimeError,
        Dez\Core\SingletonTrait,
        Dez\Core\Object,
        Dez\Error\Error as ErrorMessage;

    class View extends Object {

        use SingletonTrait;

        private
            $path   = null;

        protected function init() {}

        public function setPath( $path = null ) {
            $this->path = $path;
            return $this;
        }

        public function getPath() {
            return $this->path;
        }

        public function render( $template = null, array $data = [] ) {
            $file = $this->path . DS . $template . '.php';
            ob_start();
            try {
                if( ! file_exists( $file ) || ! is_file( $file ) )
                    throw new RuntimeError( 'Template file not found ['. $file .']' );
                extract( $data );
                include $file;
            } catch ( \Exception $e ) {
                ob_end_clean();
                ErrorMessage::warning( $e->getMessage() );
            }
            $output = ob_get_contents(); ob_clean();
            return $output;
        }

    }