<?php

    namespace Dez\Utils;

    class NumConv {

        const
            USE_UPPERCASE       = 1,
            USE_LOWERCASE       = 2,
            USE_NUMS            = 4,
            USE_NUMS_WO_ZERO    = 8,
            USE_SYMBOLS         = 16;

        private
            $map        = array(),
            $mapSize    = 0;

        public function __construct( $mode = 0 ){

            $this->map = array( -1 );

            if( $mode & self::USE_LOWERCASE ) {
                $this->map = array_merge( $this->map, range( 'a', 'z' ) );
            }

            if( $mode & self::USE_UPPERCASE ) {
                $this->map = array_merge( $this->map, range( 'A', 'Z' ) );
            }

            if( $mode & self::USE_NUMS ) {
                $this->map = array_merge( $this->map, range( '0', '9' ) );
            } else if( $mode & self::USE_NUMS_WO_ZERO ) {
                $this->map = array_merge( $this->map, range( '1', '9' ) );
            }

            if( $mode & self::USE_SYMBOLS ) {
                $this->map = array_merge( $this->map, array( '_', '-' ) );
            }

            unset( $this->map[0] );

            $this->mapSize = count( $this->map );
        }

        static public function instance() {
            static $instance;
            if( ! $instance ) $instance = new static(
                static::USE_LOWERCASE | static::USE_UPPERCASE | static::USE_NUMS
            );
            return $instance;
        }

        public function addCustom( array $customData = array() ) {
            $this->map      = array_merge( $this->map, $customData );
            $this->mapSize  = count( $this->map );
        }

        public function getRatio() {
            return round( $this->mapSize / 10, 4 );
        }

        public function encode( $intData = 0 ) {
            $mapStr     = join( '', $this->map );
            $result     = '';

            if( 0 >= $intData ) return 0;

            do {
                $index      = bcmod( $intData, $this->mapSize );
                $result     = $mapStr[$index] . $result;
                $intData    = floor( bcdiv( $intData, $this->mapSize ) );
                if( $intData == 0 ) break;
            } while ( true );

            return $result;
        }

        public function decode( $data = '' ){
            $length     = strlen( $data ) - 1;
            $resultInt  = 0;
            $mapStr     = join( '', $this->map );

            if( 0 > $length ) return $resultInt;

            $data       = strrev( $data );

            for( $i = 0; $i <= $length; $i++ ){
                $symbol     = $data[$i];
                $position   = strpos( $mapStr, $symbol );
                $resultInt  = bcadd( $resultInt, bcmul( $position, bcpow( $this->mapSize, $i ) ) );
            }

            return $resultInt;
        }

}