<?php

    namespace Dez\Utils;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait,
        Dez\Error\Exception;

    class File extends Object {

        use SingletonTrait;

        protected
            $filePath   = null;

        protected function init( $file = null ) {
            if( DataType::isString( $file ) && ! String::isEmpty( $file ) ) {
                $this->filePath = $file;
            } else if( DataType::isEmpty( $file ) ) {
                $this->filePath = null;
            } else {
                throw new Exception\InvalidArgs( __CLASS__ .' Wrong argument pass' );
            }
        }

        public function isFileExists() {
            return ( file_exists( $this->filePath ) && is_file( $this->filePath ) );
        }

        static public function fileExists( $filePath = null ) {
            return static::instance( $filePath )->isFileExists();
        }

    }