<?php

    namespace Dez\Core;

	class Session extends Object {
		
        use SingletonTrait, HasDataTrait;

        static protected
            $data = [];

        protected function init() {
            session_name( \Dez::cfg()->path( 'base.session_name' ) );
            if( ! session_id() ){
                session_start();
            }
            static::$data = & $_SESSION;
        }

        public function getId() { return session_id(); }

        static public function id() { return static::instance()->id; }

        protected function & getData() { return static::$data; }
		
	}
