<?php

    namespace Dez\Auth\ACL;

    use Dez\Core\Object,
        Dez\Core\SingletonTrait;

    class Role extends Object {

        use SingletonTrait;

        protected function init() {}

        static public function permissionsToString( array $permissions = [] ) {
            if( empty( $permissions ) ) return 0;
            $max            = max( $permissions );
            $accessGroups   = array_fill( 0, floor( $max / 32 ) + 1, 0 );
            foreach( $permissions as $a ) {
                $rowNum         = (int) floor( $a / 32 );
                $a              = $a - ( 32 * $rowNum );
                $accessGroups[$rowNum]
                    |= ( 1 << $a );
            }
            return join( '.', $accessGroups );
        }

        static public function hasPermission( $permission = -1, $permissions = null ) {
            if( 0 >= $permission ) return false;
            $line           = (int) floor( $permission / 32 );
            $level          = $permission - ( 32 * $line );
            $permissions    = array_map( 'intval', explode( '.', $permissions ) );
            return (bool) isset( $permissions[$line] ) ? $permissions[$line] & ( 1 << $level ) : false;
        }

    }