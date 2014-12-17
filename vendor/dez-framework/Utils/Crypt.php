<?php

    namespace Dez\Utils;

    class Crypt {

        private
            $converter  = null,
            $setting    = -1;

        protected function __construct( NumConv $converter, $setting = 0 ){
            $this->converter    = $converter;
            $this->setting      = $setting;
        }
        static public function instance() {
            static $instance;
            if( ! $instance ) $instance = new static( new NumConv(
                NumConv::USE_LOWERCASE | NumConv::USE_UPPERCASE | NumConv::USE_NUMS_WO_ZERO
            ) );
            return $instance;
        }

        public function encode( $data = null, $secretKey = null ){

            $secretKey  = str_pad( '', strlen( $data ), $this->_hash( $secretKey ) );
            $data       = $data ^ $secretKey;
            $output     = array();

            foreach( str_split( $data, 3 ) as $chunk ) {
                $tmp        = unpack( 'H*', $this->_randSymbol() . $chunk );
                $tmp        = $this->converter->encode( base_convert( $tmp[1], 16, 10 ) );
                $output[]   = str_pad( $tmp, 6, '0', STR_PAD_LEFT );
                unset( $tmp );
            }

            return join( '', $output );
        }

        public function decode( $data = null, $secretKey = null ){

            $output = null;

            foreach( str_split( $data, 6 ) as $chunk ) {
                $chunk  = base_convert( $this->converter->decode( ltrim( $chunk, '0' ) ), 10, 16 );
                $output .= substr( pack( 'H*', $chunk ), 1 );
            }

            $secretKey  = $this->_hash( $secretKey );
            $output     = $output ^ str_pad( '', strlen( $output ) * 2, $secretKey );

            return $output;
        }

        private function _hash( $data = null ) {
            return md5( $data );
        }

        private function _randSymbol() {
            return chr( rand( ord( 'A' ), ord( 'z' ) ) );
        }

    }