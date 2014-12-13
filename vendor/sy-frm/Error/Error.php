<?php

	namespace Sy\Error;

    use Sy\Core\SingletonTrait;
    use Sy\Error\Handlers;

	class Error {

        use SingletonTrait;

		static private
			$errorHandler;

		protected function init() {
			if( empty( self::$errorHandler ) ) {
				self::$errorHandler = $this->_getHandler();
			}
		}

        public function render() {
            return Handlers\System::instance(
                ErrorHandler::CRITICAL | ErrorHandler::WARNING | ErrorHandler::NOTIFY
            )->render();
        }

		public function raise( $message, $priority = 1 ) {
			self::$errorHandler->message( $message, $priority );
		}

		static public function warning( $message ) {
            static::instance()->raise( $message, ErrorHandler::WARNING );
		}

		static public function notify( $message ) {
            static::instance()->raise( $message, ErrorHandler::NOTIFY );
		}

		static public function critical( $message ) {
            static::instance()->raise( $message, ErrorHandler::CRITICAL );
		}

		static public function fatal( $message ) {
            static::instance()->raise( $message, ErrorHandler::FATAL );
		}

		static public function db( $message ) {
            static::instance()->raise( $message, ErrorHandler::DB );
		}

		private function _getHandler() {

			$handler = Handlers\FileLog::instance(
                ErrorHandler::FATAL | ErrorHandler::CRITICAL | ErrorHandler::DB
            );

			$handler->addHandler(
				Handlers\EmailNotify::instance( ErrorHandler::FATAL )
			);
			$handler->addHandler(
			    Handlers\System::instance(
					ErrorHandler::CRITICAL | ErrorHandler::WARNING | ErrorHandler::NOTIFY
				)
			);

            if( \Sy::cfg()->path( 'base/errors/hide_die_message' ) != 1 ) {
                $handler->addHandler(
                    Handlers\DataBase::instance( ErrorHandler::DB )
                );
                $handler->addHandler(
                    Handlers\SystemFatal::instance( ErrorHandler::FATAL )
                );
            }

			return $handler;
		}

	}
