<?php

    namespace Sy\Utils;

    use Sy\Core\Object;
    use Sy\Core\SingletonTrait;

    class DataType extends Object {

        use SingletonTrait;

        protected function init() {}

        static public function isString( $data = null ) {
            return is_string( $data );
        }

        static public function isNull( $data = null ) {
            return is_null( $data );
        }

        static public function isEmpty( $data = null ) {
            return ( static::isNull( $data ) || empty( $data ) );
        }

    }