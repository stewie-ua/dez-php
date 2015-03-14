<?php

    namespace Dez\ORM\Common;

    trait SingletonTrait {

        protected static
            $instances = [];

        /**
         * @return static
        */

        final static public function instance() {
            $args   = func_get_args();

            $names = [];
            if( isset( $args[0] ) && is_object( $args[0] ) ) {
                foreach( $args as $object ) {
                    $names[]    = get_class( $object );
                } unset( $object );
            }

            $hash   = md5( static::class . json_encode( [ $args, $names ] ) . count( $args, true ) );

            if( ! isset( static::$instances[$hash] ) ) {
                static::$instances[$hash] = ( new \ReflectionClass( static::class ) )
                    ->newInstanceArgs( $args );
            }

            return static::$instances[$hash];
        }

        final public function __construct() {
            call_user_func_array( [ $this, 'init' ], func_get_args() );
        }

        abstract protected function init();

    }