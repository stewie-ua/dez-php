<?php

    namespace Dez\Auth;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait;

    class Access extends Object {

        use SingletonTrait;

        protected function init() {}

        public function accessToString( array $access = [] ) {
            if( empty( $access ) ) return 0;
            $max            = max( $access );
            $accessGroups   = array_fill( 0, floor( $max / 32 ) + 1, 0 );
            foreach( $access as $a ) {
                $rowNum         = (int) floor( $a / 32 );
                $a              = $a - ( 32 * $rowNum );
                $accessGroups[$rowNum]
                    |= ( 1 << $a );
            }
            return join( '.', $accessGroups );
        }

        public function access( $target = -1, $userLevel = null ) {
            if( 0 >= $target ) return false;
            $access     = $userLevel;
            $line       = (int) floor( $target / 32 );
            $level      = $target - ( 32 * $line );
            $access     = array_map( 'intval', explode( '.', $access ) );
            return (bool) isset( $access[$line] ) ? $access[$line] & ( 1 << $level ) : false;
        }

    }