<?php

	namespace Sy\Core\App;

    use \Sy\Autoloader as Loader,
        \Sy\Core,
        \Sy\ORM,
        \Sy\Error,
        \Sy\Error\Error as ErrorMessage,
        \Sy\Utils,
        \Sy\Helper;

	class Web extends Core\App {

		public
			$environment = 'web';

		public function __construct( Core\Config $config ){

            parent::__construct();

            // @TODO do somethink
			date_default_timezone_set( $config->path( 'base.time_zone' ) );

			$this->attach( 'config', $config );

            try {
                $this->init();
            } catch ( \Exception $e ) {
                ErrorMessage::fatal( Utils\HTML::tag( 'b', get_class( $e ) ) .': '. $e->getMessage() );
            }

			$this->response->setTitle( $config->path( 'base.app_name' ) );
		}

        protected function initSession() {
            $this->attach( 'session', Core\Session::instance() );
        }

		public function run(){
			$response 		= $this->response;

			if( $response->getType() == 'stream' ){
				ob_start();
					$this->action->execute( $this->request );
				ob_end_flush(); die;
			}else{

				try{
                    $content = $this->action->execute();
				}catch( \Exception $e ){
                    ErrorMessage::fatal( $e->getMessage() );
				}

				$this->addMainVarsForLayout();

				if( $response->getType() == 'default' ){

                    $debugBlock = ( $this->config->path( 'debug.enable' ) ? Helper\Debug::instance()->render() : null );

					$response->set( 'error_block',      ErrorMessage::instance()->render() );
                    $response->set( 'message_block',    Core\Message::instance()->render() );
                    $response->set( 'debug_block',      $debugBlock );
					$response->set( 'content',          $content );
					$response->setHeader( 'Content-type', 'text/html' );
					$response->sendHeaders();
					return $response->render();
				}else{
                    Error\Handlers\System::emptyStack();
					$response->sendHeaders();
					die( $content );
				}
			}
		}

		private function init(){

            $this->initSession();

            $this->initError();

            $this->initRequest();

            $this->initRouter();

            $this->initAction();

            $this->initDatabase();

            $this->initView();

            $this->initResponse();

			$this->response->setHeader( 'X-Content-By', \Sy::poweredBy() );
			$this->response->setHeader( 'X-Author', SY_AUTHOR );

		}

		private function initDatabase() {

            Loader::addIncludeDirs( SY_PATH . DS . 'Db' );

			ORM::init( APP_PATH . DS . 'conf' . DS . 'app.ini', 'dev' );

            $this->attach( 'db', ORM::connect() );

            if( \Sy::cfg()->path( 'debug/enable' ) ) {

                ORM\Common\Event::instance()->attach( 'query', function( $query = null ){
                    Helper\Debug::instance()->sql( $query );
                } );

            }

		}

		protected function initError() {

			register_shutdown_function( function(){

				$error      = error_get_last();
                $highlight  = null;

                if( \Sy::app()->config->path( 'debug/enable_highlight' ) != 0 ) {
                    $highlight = Utils\Debug::highlight( $error );
                }
				switch( true ) {
					case ( $error['type'] & ( E_ERROR | E_WARNING | E_COMPILE_ERROR ) ): {
                        ErrorMessage::critical( $error['message'] . ' ['. $error['file'] .':'. $error['line'] .']' . $highlight );
						break;
					}
					case ( $error['type'] & ( E_NOTICE ) ): {
                        ErrorMessage::notify( $error['message'] . ' ['. $error['file'] .':'. $error['line'] .']' . $highlight );
						break;
					}
					default : {
					    break;
					}
				}
			});

		}

        protected function initRequest() {
            $this->attach( 'request', Core\Request::instance() );
        }

        protected function initRouter() {
            $config 	    = \Sy::cfg();

            $cacheDir 		= APP_PATH . DS . 'cache' . DS . 'system';
            $xmlRoutes 		= APP_PATH . DS . 'conf' . DS . $config->path( 'base.router_config' );

            $router = Core\Router::instance(  $xmlRoutes, $cacheDir, $config->path( 'debug.enable' ) );
            $this->attach( 'router', $router );
        }

        protected function initAction() {
            $this->attach( 'action', Core\Action::instance( $this->router, $this->request ) );
        }

        protected function initResponse() {
            $this->attach( 'response', 	Core\Response::instance() );
        }

        protected function initView() {
            $this->attach( 'view', Core\View::instance( null, 'phtml' ) );
        }

		private function addMainVarsForLayout(){
			$config 	    = \Sy::cfg();
			$this->response->set( 'site_name', $config->path( 'base.app_name' ) );
			$this->response->set( 'base_url', Core\URI::base( true ) );
		}

        public function redirect( $url = null ){
            $uri = Core\URI::getInstance( $url );
            $url = $uri->buildURL( 'scheme', 'host', 'path', 'query' );
            header( 'HTTP/1.1 302 Moved Temporarily' );
            header( 'Location: ' . $url );
            die;
        }

        public function errorCode404(){
            header( 'HTTP/1.1 404 Not Found' ); die;
        }

        public function errorCode500(){
            header( 'HTTP/1.1 500 Internal Server Error', true, 500 ); die;
        }

	}