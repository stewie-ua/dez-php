<?php

	namespace Sy\Core\App;

    use \Sy\Core,
        \Sy\Error,
        \Sy\Utils,
        \Sy\Helper;

	class Web extends Core\App {

		public
			$environment = 'web';

		public function __construct( Core\Config $config ){

            parent::__construct();

			date_default_timezone_set( $config->path( 'base/time_zone' ) );

			$this->config = $config;
            $this->attachObject( 'session', Core\Session::instance() );

			$this->initErrorHandler();
			$this->addMainObjects();

            try {
                $this->init();
            } catch ( \Exception $e ) {
                Error\Error::fatal( $e->getMessage() );
            }

			$this->response->setTitle( $config->path( 'base/app_name' ) );
		}

		public function registerRoute( $route, $args ){
			return call_user_func_array( array( $this->action, 'registerHandlerOfRoute' ), array( $route, $args ) );
		}

        public function get() {
            $args = func_get_args();

            $route = array_shift( $args );
            $this->registerRoute( $route, $args );
        }

        public function post() {
            if( $this->request->isPost() ) {
                $args = func_get_args();
                $route = array_shift( $args );
                $this->registerRoute( $route, $args );
            }
        }

		public function stream( $streamFunction = null ){
			if( is_callable( $streamFunction ) ){
				$this->response->setType( 'stream' );
				$streamFunction();
				return;
			}
		}

		public function run(){
			$response 		= $this->response;

			if( $response->getType() == 'stream' ){
				ob_start();
					$this->action->execute( $this->request );
				ob_end_flush(); die;
			}else{

				try{
                    $content = $this->action->execute( $this->request );
				}catch( \Exception $e ){
                    Error\Error::fatal( $e->getMessage() );
				}

				$this->addMainVarsForLayout();

				if( $response->getType() == 'default' ){

                    $debugBlock = ( $this->config->path( 'debug/enable' ) ? Helper\Debug::instance()->render() : null );

					$response->set( 'error_block',      Error\Error::instance()->render() );
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

		public function redirect( $url ){
			$uri = Core\URI::getInstance( $url );
			$url = $uri->buildURL( 'scheme', 'host', 'path', 'query' );
			header( 'HTTP/1.1 302 Moved Temporarily' );
			header( 'Location: ' . $url );
			die;
		}

		public function page404(){
			header( 'HTTP/1.1 404 Not Found' ); die;
		}

		public function page500(){
			header( 'HTTP/1.1 500 Internal Server Error', true, 500 ); die;
		}

		private function init(){
			$this->response->setHeader( 'X-Content-By', \Sy::poweredBy() );
			$this->response->setHeader( 'X-Author', SY_AUTHOR );

            $this->initDatabase();

			$this->initRouter();

		}

		private function initDatabase() {
            \Sy\Autoloader::addIncludeDirs( SY_PATH . DS . 'Db' );
			\Sy\ORM::init( APP_PATH . DS . 'conf' . DS . 'app.ini', 'dev' );
			$this->attachObject( 'db', \Sy\ORM::connect() );
            if( \Sy::cfg()->path( 'debug/enable' ) ) {
                \Sy\ORM\Common\Event::instance()->attach( 'query', function( $query = null ){
                    Helper\Debug::instance()->sql( $query );
                } );
            }
		}

		protected function initErrorHandler() {

			register_shutdown_function( function(){
				$error      = error_get_last();
                $highlight = null;
                if( \Sy::app()->config->path( 'debug/enable_highlight' ) != 0 ) {
                    $highlight = Utils\Debug::highlight( $error );
                }
				switch( true ) {
					case ( $error['type'] & ( E_ERROR | E_WARNING | E_COMPILE_ERROR ) ): {
                        Error\Error::critical( $error['message'] . ' ['. $error['file'] .':'. $error['line'] .']' . $highlight );
						break;
					}
					case ( $error['type'] & ( E_NOTICE ) ): {
						Error\Error::notify( $error['message'] . ' ['. $error['file'] .':'. $error['line'] .']' . $highlight );
						break;
					}
					default :{
					    break;
					}
				}
			});
		}

		protected function initRouter() {
			$baseConf 	    = \Sy::cfg( 'base' );
			$debugConfig 	= \Sy::cfg( 'debug' );

			$cacheDir 		= APP_PATH . DS . 'cache' . DS . 'system';
			$xmlRoutes 		= APP_PATH . DS . 'conf' . DS . $baseConf['router_config'];

			if( file_exists( $xmlRoutes ) ){
				$this->attachObject( 'router', new Core\Router(
					$xmlRoutes,
					$cacheDir,
					( (int) $debugConfig['enable'] === 1 )
				));
			}
		}

		private function addMainObjects(){
			$this->attachObject( 'request', 	new Core\Request() );
			$this->attachObject( 'view', 		new Core\View( null, 'phtml' ) );
			$this->attachObject( 'action', 		new Core\Action() );
			$this->attachObject( 'response', 	new Core\Response() );
		}

		private function addMainVarsForLayout(){
			$config 	    = \Sy::cfg( 'base' );
			// System vars for layout
			$this->response->set( 'site_name', $config['app_name'] );
			$this->response->set( 'base_url', Core\URI::base( true ) );
		}

	}