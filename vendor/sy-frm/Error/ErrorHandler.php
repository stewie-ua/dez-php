<?php

	namespace Sy\Error;

	use Sy\Core\SingletonTrait;

    abstract class ErrorHandler {

        use SingletonTrait;

		const
			FATAL 			= 1,
			CRITICAL 		= 2,
			WARNING         = 4,
			NOTIFY			= 8,
			DB 	            = 16;

		private
			$mask,
			$handlers = array();

		protected
			$priority = 0;

		public function init(){
			$this->mask     = func_get_args()[0];
		}

		public function message( $message, $priority = 1 ) {
			$this->priority = $priority;

			if( $this->mask & $this->priority ) {
				$this->_message( $message );
			}

			if( $this->haveHandlers() ) {
				foreach( $this->getHandlers() as $handler ) {
					$handler->message( $message, $priority );
				}
			}
		}

		public function addHandler( ErrorHandler $handler ) {
			$this->handlers[] = $handler;
		}

		public function getHandlers() {
			return $this->handlers;
		}

		public function haveHandlers() {
			return ( sizeOf( $this->handlers ) > 0 );
		}

		abstract protected function _message( $message );

	}