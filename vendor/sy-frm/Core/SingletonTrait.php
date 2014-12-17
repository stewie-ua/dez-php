<?php

    namespace Sy\Core;

    trait SingletonTrait {

        protected static
            $instances = [];

        final static public function instance() {
            $args   = func_get_args();
            $hash   = md5( get_called_class() . json_encode( $args ) . count( $args, true ) );

            if( ! isset( static::$instances[$hash] ) ) {
                static::$instances[$hash] = ( new \ReflectionClass( get_called_class() ) )
                    ->newInstanceArgs( $args );
            }

            return static::$instances[$hash];
        }

        final public function __construct() {
            call_user_func_array( [ $this, 'init' ], func_get_args() );
        }

        abstract protected function init();

    }