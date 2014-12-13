<?php

    namespace Sy\Core;

	class Session {
		
        use SingletonTrait, HasDataTrait;

        static protected
            $data = [];

        protected function init() {
            session_name( \Sy::cfg()->path( 'base/session_name' ) );
            if( ! session_id() ){
                session_start();
            }
            static::$data = & $_SESSION;
        }

        protected function & getData() {
            return static::$data;
        }
		
	}
